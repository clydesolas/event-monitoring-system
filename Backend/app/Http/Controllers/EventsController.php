<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Events;
use App\Models\Transactions;
use App\Exports\EventsExport;
use App\Exports\EventsPdfExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Events::where('status', '!=', 'ARCHIVED')
                    ->orderBy('date', 'desc')
                    ->get();
    }
    
    public function getArchived()
    {
        return Events::where('status', '=', 'ARCHIVED')
                    ->orderBy('date', 'desc')
                    ->get();
    }
    public function getActiveEventsCount()
    {
        $activeEventsCount = Events::where('status', '!=', 'ARCHIVED')->count();

        return response()->json(['active_events_count' => $activeEventsCount]);
    }
    public function getCountByMonthCurrentYear()
    {
        $eventCounts = Events::select(
            DB::raw('MONTH(date) as monthnamedate'),
            DB::raw('YEAR(date) as yeardate'),
            DB::raw('CONCAT(MONTHNAME(date), " ", YEAR(date)) as month_year'),
            DB::raw('COUNT(CONCAT(MONTH(date), YEAR(date))) as count')
        )
        ->where('status', '=', 'ACTIVE')
        ->groupBy('monthnamedate', 'yeardate', 'month_year')
        ->orderByDesc('yeardate')
        ->orderByDesc('monthnamedate')
        ->get();
    
        return response()->json(['event_counts' => $eventCounts]);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(([
            'title'=>'required',
            'description'=>'required',
            'payment'=>'required',
            'date'=>'required',
            'due_date'=>'required',
            'duration_date'=>'required',
            'academic_year'=>'required',
            'semester'=>'required',
        ]));

        return Events::create($request->all());
    }
    
    public function exportExcel(Request $request)
    {
        $request->validate([
            'academic_year' => 'sometimes|required|string',
            'semester' => 'sometimes|required|string',
            'title' => 'sometimes|required|string',
        ]);

        $academicYear = $request->post('academic_year');
        $semester = $request->post('semester');
        $title = $request->post('title');
        dd($academicYear, $semester, $title);
        return Excel::download(new EventsExport($academicYear, $semester, $title), "events_export.xlsx");
    }

    public function exportToPdfApi(Request $request)
    {
        $request->validate([
            'academic_year' => 'sometimes|required|string',
            'semester' => 'sometimes|required|string',
            'title' => 'sometimes|required|string',
        ]);

        $academicYear = $request->post('academic_year');
        $semester = $request->post('semester');
        $title = $request->post('title');
    
        $pdfExport = new EventsPdfExport($academicYear, $semester, $title);
        return $pdfExport->exportToPdfResponse();
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Events::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event = Events::find($id);
        $event->update($request->all());

        // If the 'status' column is updated, update the Transactions model
        if ($request->has('status')) {
            Transactions::where('event_id', $id)->update(['status' => $request->status]);
        }

        return $event;
    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
