<?php

namespace App\Exports;

use App\Models\Entry;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

use Illuminate\Support\Facades\Auth;

class EntryDetailsExport implements FromCollection, WithHeadings
{

    public function __construct($FechaInicio, $FechaFin, $SupplierId, $productId)
    {
        $this->FechaInicio = $FechaInicio;
        $this->FechaFin = $FechaFin;
        $this->SupplierId = $SupplierId;
        $this->ProductId = $productId;
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Proveedor',
            'Producto',
            'Cantidad',
            'Precio Unitario',
            'Total',
        ];
    }

    public function collection()
    {
        $collection = [];

        $fechaInicio = $this->FechaInicio;
        $fechaFin = $this->FechaFin;
        $supplierId = $this->SupplierId;
        $productId = $this->ProductId;

        $query = "SELECT p.name product, ed.quantity, ed.unitPrice, s.name supplier, e.date
        FROM entries e INNER JOIN entry_details ed ON e.id = ed.entryId
                     INNER JOIN products p ON p.id = ed.productId
                     INNER JOIN suppliers s ON s.id = e.supplierId
                     WHERE 1 = 1 ";


        if (isset($fechaInicio)) {
            $query = $query . " AND  DATE(e.date) >= '" . $fechaInicio . "'";
        }

        if (isset($fechaFin)) {
            $query = $query . " AND  DATE(e.date) <= '" . $fechaFin . "'";
        }

        if (isset($supplierId)) {
            $query = $query . " AND  e.supplierId = " . $supplierId;
        }

        if (isset($productId)) {
            $query = $query . " AND  ed.productId = " . $productId;
        }

        $objects = DB::select($query);

        foreach ($objects as $entry) {
            $collection[] = [
                'Fecha' => $entry->date,
                'Proveedor' => $entry->supplier,
                'Producto' => $entry->product,
                'Cantidad' => $entry->quantity,
                'Precio Unitario' => $entry->unitPrice,
                'Total' => $entry->unitPrice * $entry->quantity,
            ];
        }


        return collect($collection);
    }
}
