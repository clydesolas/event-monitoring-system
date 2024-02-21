<?php

namespace App\Exports;

use App\Models\Events;
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

class EventsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize 
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
        $query = Events::query();

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
            'Title',
            'Description',
            'Payment Total',
            'Payment Deadline',
            'Event Date',
            'Event Duration',
            'Academic Year',
            'Semester',
        ];
    }

    public function map($row): array
    {
        return [
            $row->title,
            $row->description,
            $row->payment,
            $row->due_date,
            $row->date,
            $row->duration_date,
            $row->academic_year,
            $row->semester,
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}