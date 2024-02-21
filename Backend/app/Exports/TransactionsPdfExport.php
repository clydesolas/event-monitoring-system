<?php

namespace App\Exports;

use App\Models\Transactions;
use Mpdf\Mpdf;

class TransactionsPdfExport
{
    protected $academic_year;
    protected $semester;
    protected $event_id;

    public function __construct($academicYear, $semester, $event_id)
    {
        $this->academic_year = $academicYear;
        $this->semester = $semester;
        $this->event_id = $event_id;
    }

    public function exportToPdfResponse()
    {
        $data = Transactions::query()
            ->when($this->academic_year && $this->academic_year !== 'all', function ($query) {
                $query->where('academic_year', $this->academic_year);
            })
            ->when($this->semester && $this->semester !== 'all', function ($query) {
                $query->where('semester', $this->semester);
            })
            ->when($this->event_id && $this->event_id !== 'all', function ($query) {
                $query->where('event_id', $this->event_id);
            })
            ->where('status', '!=', 'ARCHIVED')
            ->get();

        // Generate HTML content directly
        $html = '<html><head><title>Events</title></head><body>';
        $html .= '<h2>Transaction Report </h2>';
        $html .= '<p>Academic Year: <u>'.$this->academic_year.'
        </u>&emsp; Semester: <u>'.$this->semester.'</u>  
        </u>&emsp; Event: <u>'.$this->event_id.'</u> </p>';
        $html .= '<table border="1">';
        $html .= '<thead><tr><th>Transaction ID</th><th>Title</th><th>Transaction Date</th><th>Total Fee</th><th>Event Date</th><th>Event Duration</th><th>Academic Year</th><th>Semester</th><th>Student Number</th><th>Fullname</th><th>Paid by</th><th>Relationship with student</th></tr></thead>';
        $html .= '<tbody>';
        
        foreach ($data as $row) {
            $html .= '<tr>';
            $html .= '<td>' . $row->transaction_id . '</td>';
            $html .= '<td>' . $row->title . '</td>';
            $html .= '<td>' . $row->created_at->format('M j, Y g:i a') . '</td>';
            $html .= '<td>' . $row->payment . '</td>';
            $html .= '<td>' . $row->date . '</td>';
            $html .= '<td>' . $row->duration_date . '</td>';
            $html .= '<td>' . $row->academic_year . '</td>';
            $html .= '<td>' . $row->semester . '</td>';
            $html .= '<td>' . $row->student_number . '</td>';
            $html .= '<td>' . $row->fullname . '</td>';
            $html .= '<td>' . $row->paid_by . '</td>';
            $html .= '<td>' . $row->relation . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></body></html>';

        // Create Mpdf instance
        $pdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $pdf->SetTitle('Events Export');
        
        // Write HTML content to PDF
        $pdf->WriteHTML($html);
        $currentDate = now()->format('Y-m-d');

        // Return PDF as response
        return response($pdf->Output('events_'.$currentDate.'.pdf', 'S'), 200)
            ->header('Content-Type', 'application/pdf');
    }
}
