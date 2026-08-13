<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $attendees;

    public function __construct($attendees)
    {
        $this->attendees = $attendees;
    }

    public function collection()
    {
        return $this->attendees;
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Attended At',
            'Affiliation',
            'Designation',
            'Email',
            'Contact No.',
            'Sex',
            'Age',
            '4Ps',
            'PWD',
            'IP',
        ];
    }

    public function map($attendee): array
    {
        static $index = 0;
        $index++;

        $affiliation = $attendee->participant->detail->affiliation ?? null;
        $affiliationText = ($affiliation?->name === 'Others')
            ? $attendee->participant->detail->others
            : $affiliation?->name;

        return [
            $index,
            trim(($attendee->participant->firstname ?? '') . ' ' . ($attendee->participant->lastname ?? '')),
            $attendee->attended_at ?? '',
            $affiliationText ?? '',
            $attendee->participant->detail->designation ?? '',
            $attendee->participant->email ?? '',
            $attendee->participant->mobile ?? '',
            $attendee->participant->detail->sex->name ?? '',
            $attendee->participant->detail->age ?? '',
            $attendee->is_4ps ? 'Yes' : 'No',
            $attendee->is_pwd ? 'Yes' : 'No',
            $attendee->is_ip ? 'Yes' : 'No',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
