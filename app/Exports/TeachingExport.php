<?php

namespace App\Exports;

use App\Models\Teaching;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TeachingExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Teaching::get();

        $column = [['Contact Name', 'Email', 'Phone Number', 'Online Teaching Experience', 'Own Audience', 'Hear About Us', 'Teaching Provide']];

        return array_merge($column, $teaching);
    }

    public function headings(): array
    {
        return ['Contact Name', 'Email', 'Phone Number', 'Online Teaching Experience', 'Own Audience', 'Hear About Us', 'Teaching Provide'];
    }

    public function map($teaching): array
    {
        return [
            $teaching->contact_name,
            $teaching->email,
            $teaching->phone_number,
            $teaching->online_teaching_experience,
            $teaching->own_audience,
            $teaching->hear_about_us,
            $teaching->teaching_provide,
        ];
    }
}
