<?php

namespace App\Exports;

use App\Models\RequestDemo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RequestDemoExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return RequestDemo::get();

        $column = [['Contact Name', 'Email', 'Phone Number', 'Institute Name', 'No Of Students', 'Hear About Us', 'State', 'Message']];

        return array_merge($column, $request_demo);
        
    }

    public function headings(): array
    {
        return ['Contact Name', 'Email', 'Phone Number', 'Institute Name', 'No Of Students', 'Hear About Us', 'State', 'Message'];
    }

    public function map($request_demo): array
    {
        return [
            $request_demo->contact_name,
            $request_demo->email,
            $request_demo->phone_number,
            $request_demo->institute_name,
            $request_demo->no_of_students,
            $request_demo->hear_about_us,
            $request_demo->state,
            $request_demo->message,
        ];
    }
}

