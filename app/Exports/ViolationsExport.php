<?php

namespace App\Exports;

use App\Models\Violation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ViolationsExport implements FromCollection, WithHeadings
{
    protected $filter;
    protected $date;


    public function __construct($filter = null, $date = null)
    {
        $this->filter = $filter;
        $this->date = $date;
    }



    public function collection()
    {

        $query = Violation::with([
            'driver',
            'vehicle',
            'violationType',
            'user'
        ]);



        // Filter today's violations
        if ($this->filter == 'today') {

            $query->whereDate(
                'violation_date',
                today()
            );

        }



        // Filter specific date
        if ($this->date) {

            $query->whereDate(
                'violation_date',
                $this->date
            );

        }



        return $query
            ->latest()
            ->get()
            ->map(function ($violation) {


                return [

                    'Ticket Number' =>
                        $violation->ticket_number,


                    'Violator Name' =>
                        $violation->driver
                        ? $violation->driver->first_name . ' ' .
                          $violation->driver->last_name
                        : 'N/A',


                    'License Number' =>
                        $violation->driver->license_number ?? 'N/A',


                    'Vehicle Plate' =>
                        $violation->vehicle->plate_number ?? 'N/A',


                    'Vehicle Model' =>
                        $violation->vehicle->vehicle_model ?? 'N/A',


                    'Violation Type' =>
                        $violation->violationType->name ?? 'N/A',


                    'Enforcer' =>
                        $violation->user->name ?? 'N/A',


                    'Violation Date' =>
                        $violation->violation_date,


                    'Location' =>
                        $violation->location,


                    'Remarks' =>
                        $violation->remarks,


                    'Status' =>
                        $violation->status,

                ];

            });

    }



    public function headings(): array
    {
        return [

            'Ticket Number',
            'Violator Name',
            'License Number',
            'Vehicle Plate',
            'Vehicle Model',
            'Violation Type',
            'Enforcer',
            'Violation Date',
            'Location',
            'Remarks',
            'Status'

        ];
    }

}