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

class ActiveCreditsExport implements FromCollection, WithHeadings
{

    public function __construct($Folio, $ClientId, $fechaVencimiento)
    {
        $this->folio = $Folio;
        $this->clientId = $ClientId;
        $this->fechaVencimiento = $fechaVencimiento;
    }

    public function headings(): array
    {
        return [
            '# Remisión',
            'Cliente',
            'Días restantes',
            'Fecha Vencimiento',
            'Monto de venta',
            'Monto pendiente'
        ];
    }

    public function collection()
    {
        $collection = [];
        $folio = $this->folio;
        $clientId = $this->clientId;
        $fechaVencimiento = $this->fechaVencimiento;

      

        $query = "SELECT sales.folio, clients.name, credits.endDate, credits.total, credits.currentCredit
            FROM credits JOIN sales on credits.saleId = sales.id
                         JOIN clients on credits.clientId = clients.id
            WHERE currentCredit > 0 ";

        if (isset($clientId)) {
            $query = $query . " AND clients.id = " . $clientId;
        }

        if (isset($fechaVencimiento)) {
            $query = $query . " AND credits.endDate >= '" . $fechaVencimiento . "'";
        }

        if (isset($folio)) {
            $query = $query . " AND sales.folio = " . $folio;
        }

        $Credits = DB::select($query);

        foreach ($Credits as $object) {
            $fechahoy = new DateTime(); 
            $endDate = new DateTime($object->endDate); 

            $collection[] = [
                '# Remisión' => $object->folio,
                'Cliente' => $object->name,
                'Días restantes' => $fechahoy->diff($endDate)->format('%a'),
                'Fecha Vencimiento' => $endDate->format('d-m-Y') ,
                'Monto de venta' => $object->total, 
                'Monto pendiente' => $object->currentCredit
            ];
        }

        return collect($collection);
    }
}
