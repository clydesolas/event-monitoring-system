<?php

namespace App\Exports;

use App\Models\Events;
use Mpdf\Mpdf;

class EventsPdfExport
{
    protected $academic_year;
    protected $semester;
    protected $title;

    public function __construct($academicYear, $semester, $title)
    {
        $this->academic_year = $academicYear;
        $this->semester = $semester;
        $this->title = $title;
    }

    public function exportToPdfResponse()
    {
        $data = Events::query()
        ->when($this->title && $this->title !== 'all', function ($query) {
            $query->where('title', $this->title);
        })
        ->when($this->academic_year && $this->academic_year !== 'all', function ($query) {
            $query->where('academic_year', $this->academic_year);
        })
        ->when($this->semester && $this->semester !== 'all', function ($query) {
            $query->where('semester', $this->semester);
        })
        ->where('status', '!=', 'ARCHIVED')
       
        ->get();
    

        // Generate HTML content directly
        $html = '<html><head><title>Events</title></head><body>';
        $html .= '<h2>Events Report </h2>';
        $html .= '<p>Academic Year: <u>'.$this->academic_year.'
                    </u>&emsp; Semester: <u>'.$this->semester.'</u>  
                    </u>&emsp; Event Title: <u>'.$this->title.'</u> </p>';
        $html .= '<table border="1">';
        $html .= '<thead><tr><th>Title</th><th>Description</th><th>Payment Total</th><th>Payment Deadline</th><th>Event Date</th><th>Event Duration</th><th>Academic Year</th><th>Semester</th></tr></thead>';
        $html .= '<tbody>';

        foreach ($data as $row) {
            $html .= '<tr>';
            $html .= '<td>' . $row->title . '</td>';
            $html .= '<td>' . $row->description . '</td>';
            $html .= '<td>' . $row->payment . '</td>';
            $html .= '<td>' . $row->due_date . '</td>';
            $html .= '<td>' . $row->date . '</td>';
            $html .= '<td>' . $row->duration_date . '</td>';
            $html .= '<td>' . $row->academic_year . '</td>';
            $html .= '<td>' . $row->semester . '</td>';
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
