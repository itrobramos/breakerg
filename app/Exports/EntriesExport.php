<?php

namespace App\Exports;

use App\Models\Entry;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use Illuminate\Support\Facades\Auth;

class EntriesExport implements FromCollection, WithHeadings
{

    public function __construct($FechaInicio, $FechaFin, $SupplierId)
    {
        $this->FechaInicio = $FechaInicio;
        $this->FechaFin = $FechaFin;
        $this->SupplierId = $SupplierId;
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'No Proveedor',
            'Nombre Proveedor',
            'Total',
        ];
    }

    public function collection()
    {
        $collection = [];
        $objects = Entry::orderBy('date', 'desc');
        $suppliers = Supplier::orderBy('name')->get();    

        if(isset($this->FechaInicio)){
            $objects->where('date', '>=', $this->FechaInicio);
        }   

        if(isset($this->FechaFin)){
            $objects->where('date', '<=', $this->FechaFin);
        }   

        if(isset($this->supplierId)){
            $objects->where('supplierId',  $this->SupplierId);
        }   

        $objects = $objects->get();

        foreach ($objects as $sale) {
            $collection[] = [
                'date' => $sale->date,
                'supplierId' => $sale->supplierId,
                'supplier' => $sale->supplier->name,
                'total' => $sale->totalCost
            ];
        }

        return collect($collection);
    }
}
