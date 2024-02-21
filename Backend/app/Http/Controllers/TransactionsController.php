<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;
use Illuminate\Support\Str; 
use Illuminate\Validation\ValidationException;
use App\Exports\TransactionsExport;
use App\Exports\TransactionsPdfExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon;
use App\Models\Students;
use App\Models\Events;


class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Transactions::all();
    }

    public function getArchived()
    {
        return Transactions::where('status', '=', 'ARCHIVED')
                    ->orderBy('date', 'desc')
                    ->get();
    }
    public function getCountByMonthCurrentYear()
    {
        $currentYear = Carbon::now()->year;

        $eventCounts = Transactions::select(DB::raw('MONTHNAME(created_at) as month'), DB::raw('count(*) as count'))
            ->where('status', '<>', 'ARCHIVED')
            ->whereYear('date', $currentYear)
            ->groupBy('month')
            ->get();

        return response()->json(['event_counts' => $eventCounts]);
    }

    public function getTotalPaidAndNotPaid()
    {
        // Count all rows from events model
        $totalEvents = Events::count();
    
        // Get non-archived events
        $eventSelect = Events::where('status', '!=', 'ARCHIVED')->get();
    
        // Count total paid, total population, and total not paid yet by event
        $eventsData = collect(); // Initialize an empty collection
    
        foreach ($eventSelect as $event) {
            $totalPaid = Transactions::where('status', 'ACTIVE')
                ->where('academic_year', $event->academic_year)
                ->where('semester', $event->semester)
                ->where('event_id', $event->event_id)
                ->count();
    
            $totalPopulation = Students::where('academic_year', $event->academic_year)
                ->where('semester', $event->semester)
                ->where('status', '!=', 'ARCHIVED')
                ->distinct()
                ->count();
    
            $totalNotPaid = $totalPopulation - $totalPaid;
    
            $eventsData->push([
                'title' => $event->title, // Assuming event_id is a title
                'total_paid' => $totalPaid,
                'total_not_paid' => $totalNotPaid,
                'total_students' => $totalPopulation,
            ]);
        }
    
        return response()->json(['total_events' => $totalEvents, 'total_count_event' => $eventsData]);
    }
    /**
     * Store a newly created resource in storage.
     */
   
     public function exportExcel(Request $request)
     {
         $request->validate([
             'academic_year' => 'sometimes|required|string',
             'semester' => 'sometimes|required|string',
             'event_id' => 'sometimes|required|string',
         ]);
 
         $academicYear = $request->post('academic_year');
         $semester = $request->post('semester');
         $event_id = $request->post('event_id');
         $currentDate = now()->format('Y-m-d');

         return Excel::download(new TransactionsExport($academicYear, $semester, $event_id), "transactions_{$currentDate}.xlsx");
     }

     public function exportToPdfApi(Request $request)
     {
         $requestData = $request->json()->all();
     
         $academicYear = $requestData['academic_year'] ?? null;
         $semester = $requestData['semester'] ?? null;
         $event_id = $requestData['event_id'] ?? null;
     
         $pdfExport = new TransactionsPdfExport($academicYear, $semester, $event_id);
         return $pdfExport->exportToPdfResponse();
     }
     
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'event_id' => 'required',
            'payment' => 'required',
            'date' => 'required',
            'duration_date' => 'required',
            'academic_year' => 'required',
            'semester' => 'required',
            'student_number' => 'required',
            'fullname' => 'required',
            'paid_by' => 'required',
            'relation' => 'required',
        ]);

        $existingRecord = Transactions::where([
            'student_number' => $request->input('student_number'),
            'event_id' => $request->input('event_id'),
            'academic_year' => $request->input('academic_year'),
            'semester' => $request->input('semester'),
        ])->where('status', '<>', 'ARCHIVED')->first();

        // If an existing record is found, throw an exception
        if ($existingRecord) {
            throw ValidationException::withMessages([
                'student_number' => ['Already paid.'],
            ]);
        }

        // If no existing record is found, create the transaction
        return Transactions::create($request->all());
    }
    /**
     * Display the specified resource.
     */
    public function show(string $event_id)
    {
        return Transactions::where('event_id', $event_id)
            ->where('status', '!=', 'ARCHIVED')
            ->orderBy('date', 'desc')
            ->get();
    }


    public function displayReport(string $event_id, string $academicYear, string $semester)
    {
        $query = Transactions::query();
    
        if ($academicYear && $academicYear !== 'all') {
            $query->where('academic_year', $academicYear);
        }
    
        if ($semester && $semester !== 'all') {
            $query->where('semester', $semester);
        }
    
        if ($event_id && $event_id !== 'all') {
            $query->where('event_id', $event_id);
        }
    
        $query->where('status', '!=', 'ARCHIVED');
        
        $results = $query->get(); // or use other methods like first(), paginate(), etc.
    
        // Assuming you want to return the data as JSON
        return response()->json($results);
    }
    

    public function check(Request $request)
{
    $student_number = $request->input('student_number');
    $event_id = $request->input('event_id');

    $transactions = Transactions::where('student_number', $student_number)
        ->where('status', '!=', 'ARCHIVED')
        ->where('event_id', $event_id)
        ->get();

    if ($transactions->isEmpty()) {
        return response()->json(['status' => 'not_paid'], 200);
    } else {
        return response()->json(['status' => 'paid'], 200);
    }
}
    
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $transaction = Transactions::find($id);
        $transaction->update($request->all());
        return $transaction;
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
