<?php

namespace App\Exports;

use App\Models\Transactions;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize 
{
    use Exportable;

    protected $academicYear;
    protected $semester;
    protected $event_id;

    public function __construct($academicYear, $semester, $event_id)
    {
        $this->academicYear = $academicYear;
        $this->semester = $semester;
        $this->event_id = $event_id;
    }
 

    public function query()
    {
        $query = Transactions::query();

        if ($this->academicYear && $this->academicYear !== 'all') {
            $query->where('academic_year', $this->academicYear);
        }

        if ($this->semester && $this->semester !== 'all') {
            $query->where('semester', $this->semester);
        }

        if ($this->event_id && $this->event_id !== 'all') {
            $query->where('event_id', $this->event_id);
        }
        $query->where('status', '!=', 'ARCHIVED');
        
        return $query;
    }

    public function headings(): array
    {
        return [
            'Transaction ID',
            'Title',
            'Transaction Date',
            'Total Fee',
            'Event Date',
            'Event Duration',
            'Academic Year',
            'Semester',
            'Student Number',
            'Fullname',
            'Paid by',
            'Relationship with student',
        ];
    }

    public function map($row): array
    {
        return [
            $row->transaction_id,
            $row->title,
            $row->created_at->format('M j, Y g:i a'), 
            $row->payment,
            $row->date,
            $row->duration_date,
            $row->academic_year,
            $row->semester,
            $row->student_number,
            $row->fullname,
            $row->paid_by,
            $row->relation,
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}