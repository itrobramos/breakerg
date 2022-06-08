<?php

namespace App\Exports;

use App\Models\Sale;
use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DateTime;
use Illuminate\Support\Facades\Auth;
use DB;

class PartialPaymentsExport implements FromCollection, WithHeadings
{

    public function __construct($ClientId, $fechaInicio, $fechaFin)
    {
        $this->clientId = $ClientId;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    public function headings(): array
    {
        return [
            'Cliente',
            'Fecha',
            'Pago',
            'Saldo'
        ];
    }

    public function collection()
    {
        $collection = [];
        $clientId = $this->clientId;
        $fechaInicio = $this->fechaInicio;
        $fechaFin = $this->fechaFin;

        $query = "SELECT movements.id, movements.payment, movements.previosDebt, movements.newDebt, clients.name, movements.date
        FROM movements
        LEFT JOIN sales on movements.saleId = sales.id
        JOIN clients on movements.clientId = clients.id
        WHERE type = 1 ";

        if (isset($clientId)) {
            $query = $query . " AND clients.id = " . $clientId;
        }

        if (isset($fechaInicio)) {
            $query = $query . " AND movements.date >= '" . $fechaInicio . "'";
        }

        if (isset($fechaFin)) {
            $query = $query . " AND movements.date <= '" . $fechaFin . "'";
        }

        $query = $query . " ORDER BY movements.date";
        $objects = DB::select($query);

        foreach ($objects as $object) {

            $collection[] = [
                'Cliente' => $object->name,
                'Fecha' => $object->date,
                'Pago' => $object->payment,
                'Saldo' => $object->newDebt,
            ];
        }

        return collect($collection);
    }
}
