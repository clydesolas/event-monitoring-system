<?php

namespace App\Exports;

use App\Models\Transactions;
use Mpdf\Mpdf;

class StudentTransactionPdf
{
    protected $transaction_id;

    public function __construct($transaction_id)
    {
        $this->transaction_id = $transaction_id;
    }

    public function exportToPdfResponse()
    {
        $data = Transactions::query()
            ->where('transaction_id', '=', $this->transaction_id)
            ->where('status', '!=', 'ARCHIVED')
            ->first(); // Assuming you only want one row
        $pathToLogo = public_path('logo_white.jpg');

        // Create Mpdf instance
        $pdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $pdf->SetTitle('Transaction Report');
        $pdf->SetTopMargin(35);
        // Header content
        $header = '
            <div style="position: fixed; margin-right:600px ">
                <img src="' . $pathToLogo . '" alt="School Logo" style="max-width: 100px; max-height: 100px;"><br>
            </div>
            <div style="position: fixed; top: 10px; left: 120px; text-align: center; margin-bottom: 20px; margin-top:-70px">
                <strong style="font-size: 14px">Colegio de Porta Vaga</strong><br>
                Myp-Gby Bldg., Emilio Aguinaldo H-Way, Bayan Luma 7, Patindig Araw, Imus City, Cavite<br>
                &#128222; 0920 224 7246<br> <!-- Unicode for ☎ -->
                &#128231; cdportavaga@gmail.com <br> <!-- Unicode for 📧 -->
                &#128279; colegiodeportavaga.edu.ph<br> <!-- Unicode for 🔗 -->
            </div>
        ';


        // Set HTML header content
        $pdf->SetHeader($header);

        // Generate HTML content directly
        $html = '<html><head><meta charset="UTF-8">
        <title>Transaction Form</title></head>
        <body style="font-family: Arial, sans-serif;">';

        $html .= '<h2 style="text-align: center;">Transaction Report</h2>';
        $html .= '<h3>Student Information</h3>';
        $html .= '<div style="margin-bottom: 20px;">';
        $html .= '<strong>Fullname:</strong> ' . $data->fullname . '&emsp;';
        $html .= '<strong>Student Number:</strong> ' . $data->student_number . '<br>';
        $html .= '<strong>Academic Year:</strong> ' . $data->academic_year . '&emsp;';
        $html .= '<strong>Semester:</strong> ' . $data->semester . '<br>';
        $html .= '</div>';

        $html .= '<h3>Transaction Information</h3>';
        $html .= '<div>';
        $html .= '<strong>Transaction ID:</strong> ' . $data->transaction_id . '<br>';
        $html .= '<strong>Title:</strong> ' . $data->title . '<br>';
        $html .= '<strong>Transaction Date:</strong> ' . $data->created_at->format('M j, Y g:i a') . '<br>';
        $html .= '<strong>Total Fee:</strong> PHP' . $data->payment . '<br>';
        $html .= '<strong>Event Date:</strong> ' .  $data->duration_date . '<br>';
        $html .= '<strong>Paid by:</strong> ' . $data->paid_by. '<br>';
        $html .= '<strong>Relationship with student:</strong> ' . $data->relation;
        $html .= '</div>';

        $html .= '</body></html>';

        // Write HTML content to PDF
        $pdf->WriteHTML($html);
        $currentDate = now()->format('Y-m-d');

        // Return PDF as response
        return response($pdf->Output('transaction_report_' . $currentDate . '.pdf', 'S'), 200)
            ->header('Content-Type', 'application/pdf');
    }
}
