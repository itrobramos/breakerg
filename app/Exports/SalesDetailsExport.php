<?php

namespace App\Exports;

use App\Models\Sale;
use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

use Illuminate\Support\Facades\Auth;

class SalesDetailsExport implements FromCollection, WithHeadings
{

    public function __construct($FechaInicio, $FechaFin, $clientId, $productId, $Folio)
    {
        $this->FechaInicio = $FechaInicio;
        $this->FechaFin = $FechaFin;
        $this->ClientId = $clientId;
        $this->ProductId = $productId;
        $this->Folio = $Folio;
    }

    public function headings(): array
    {
        return [
            'Folio',
            'Fecha',
            'Fecha Vencimiento',
            'Cliente',
            'Producto',
            'Precio Unitario',
            'Total',
        ];
    }

    public function collection()
    {
        $collection = [];

        $fechaInicio = $this->FechaInicio;
        $fechaFin = $this->FechaFin;
        $clientId = $this->ClientId;
        $productId = $this->ProductId;
        $folio = $this->Folio;

        $query = "SELECT p.name product, cr.endDate, sd.quantity, sd.price, c.name client, s.date, s.folio
        FROM sales s INNER JOIN sale_details sd ON s.id = sd.saleId
                     INNER JOIN products p ON p.id = sd.productId
                     INNER JOIN clients c ON c.id = s.clientId
                     LEFT JOIN credits cr ON s.id = cr.saleId
                     WHERE 1 = 1 ";


        if (isset($fechaInicio)) {
            $query = $query . " AND  DATE(s.date) >= '" . $fechaInicio . "'";
        }

        if (isset($fechaFin)) {
            $query = $query . " AND  DATE(s.date) <= '" . $fechaFin . "'";
        }

        if (isset($clientId)) {
            $query = $query . " AND  s.clientId = " . $clientId;
        }

        if (isset($productId)) {
            $query = $query . " AND  sd.productId = " . $productId;
        }

        if (isset($folio)) {
            $query = $query . " AND  s.folio = " . $folio;
        }

        $objects = DB::select($query);


        foreach ($objects as $sale) {
            $collection[] = [
                'Folio' => $sale->folio,
                'Fecha' => $sale->date,
                'Fecha Vencimiento' => $sale->endDate,
                'Cliente' => $sale->client,
                'Producto' => $sale->product,
                'Precio Unitario' => $sale->price,
                'Total' => $sale->price * $sale->quantity,
            ];
        }


        return collect($collection);
    }
}
