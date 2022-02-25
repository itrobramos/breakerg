<?php

namespace App\Exports;

use App\Models\Sale;
use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use Illuminate\Support\Facades\Auth;

class SalesExport implements FromCollection, WithHeadings
{

    public function __construct($FechaInicio, $FechaFin, $clientId, $Folio)
    {
        $this->FechaInicio = $FechaInicio;
        $this->FechaFin = $FechaFin;
        $this->ClientId = $clientId;
        $this->Folio = $Folio;
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Remisión',
            'No Cliente',
            'Nombre Cliente',
            'Total',
        ];
    }

    public function collection()
    {
        $collection = [];
        $objects = Sale::with('client')
            ->orderBy('date', 'desc');

        if (isset($this->FechaInicio)) {
            $objects->where('date', '>=', $this->FechaInicio);
        }

        if (isset($this->FechaFin)) {
            $objects->where('date', '<=', $this->FechaFin);
        }

        if (isset($this->ClientId)) {
            $objects->where('clientId',  $this->ClientId);
        }

        if (isset($this->Folio)) {
            $objects->where('folio',  $this->Folio);
        }

        $objects = $objects->get();

        foreach ($objects as $sale) {
            $collection[] = [
                'date' => $sale->date,
                'folio' => $sale->folio,
                'clientId' => $sale->clientId,
                'client' => $sale->client->name,
                'total' => $sale->total
            ];
        }

        return collect($collection);
    }
}
