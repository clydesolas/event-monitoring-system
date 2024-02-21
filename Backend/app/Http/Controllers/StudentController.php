<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Models\Students;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Students::where('status', '!=', 'ARCHIVED')
        ->orderBy('created_at', 'desc')
        ->get();
    }

    public function getArchivedStudents()
    {
        return Students::where('status', '=', 'ARCHIVED')
        ->orderBy('created_at', 'desc')
        ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(([
            'student_number'=>'required',
            'first_name'=>'required',
            'middle_name'=>'required',
            'last_name'=>'required',
            'course'=>'required',
            'academic_year'=>'required',
            'semester'=>'required',
        ]));

        return Students::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $studentNumber)
{
    return Students::where('student_number', $studentNumber)->first();
}

    public function getDistinctAcademicYears()
    {
        return Students::distinct()->pluck('academic_year')->all();
    }
    public function getDistinctSemesters()
    {
        return Students::distinct()->pluck('semester')->all();
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Students::find($id);
        $student->update($request->all());
        return $student;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Students::destroy($id);
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
            'academic_year' => 'required|string',
            'semester' => 'required|string',
        ]);
    
        $file = $request->file('excel_file');
        $academicYear = $request->input('academic_year');
        $semester = $request->input('semester');
     
        $data = Excel::toArray([], $file);
    
        // Extract header and data from the Excel file
        $headerRow = $data[0][0];  
        $rows = array_slice($data[0], 1); 
    
        // Validate header and data as needed
    
        foreach ($rows as $row) {
            // Map Excel header columns to database table columns
            $rowData = array_combine($this->mapHeadersToDatabaseColumns($headerRow), $row);
            // Check if the record already exists based on student_number, semester, and academic_year
            $existingRecord = Students::where([
                'student_number' => $rowData['student_number'],
                'semester' => $semester,
                'academic_year' => $academicYear,
            ])->first();

            // If the record already exists, skip the insertion
            if ($existingRecord) {
                continue;
            }
            
            // Filter out unnecessary columns
            $filteredData = array_intersect_key($rowData, array_flip([
                'student_number',
                'last_name',
                'first_name',
                'middle_name',
                'course',
            ]));
    
            // Add academic year, semester, and status to the data
            $filteredData['academic_year'] = $academicYear;
            $filteredData['semester'] = $semester;
    
            // Insert data into the database using your model
            Students::create($filteredData);
        }
    
        return response()->json(['message' => 'Data inserted successfully']);
    }
    
    private function mapHeadersToDatabaseColumns($headers)
    {
        // Map Excel headers to corresponding database column names
        $headerMap = [
            'Student Number' => 'student_number',
            'Last Name' => 'last_name',
            'First Name' => 'first_name',
            'Middle Name' => 'middle_name',
            'Course' => 'course',
        ];
    
        // Convert Excel headers to lowercase and map to database columns
        return array_map(function ($header) use ($headerMap) {
            return $headerMap[ucwords(strtolower($header))] ?? $header;
        }, $headers);
    }

    public function listDistinctSemestersAndYears()
    {
        $semesters = Students::distinct('semester')->pluck('semester')->toArray();
        $academicYears = Students::distinct('academic_year')->pluck('academic_year')->toArray();
        $status = Students::distinct('status')->pluck('status')->toArray();

        return response()->json([
            'semesters' => $semesters,
            'academic_years' => $academicYears,
            'status' => $status,
        ]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'academic_year' => 'required|string',
            'semester' => 'required|string',
            'status' => 'required|string',
        ]);

        $academicYear = $request->input('academic_year');
        $semester = $request->input('semester');
        $status = $request->input('status');

        Students::where('academic_year', $academicYear)
                ->where('semester', $semester)
                ->update(['status' => $status]);

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function archiveStudents()
    {
        // Calculate the date 5 months ago
        $sixMonthsAgo = Carbon::now()->subMonths(5);

        // Archive students based on the criteria
        Students::where('created_at', '<', $sixMonthsAgo)
            ->where('status', 'ACTIVE') // Only archive active records
            ->update(['status' => 'ARCHIVED']);

        return response()->json(['message' => 'Student records archived successfully']);
    }
    public function getEnrolledStudentsCount()
    {
        $enrolledStudentsCount = Students::where('status', 'ENROLLED')
            ->DISTINCT('student_number')
            ->count();

        return response()->json(['total_enrolled_students' => $enrolledStudentsCount]);
    }
}
