<?php

namespace App\Exports;

use App\Models\Sale;
use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use Illuminate\Support\Facades\Auth;
use DB;

class InventaryExport implements FromCollection, WithHeadings
{

    public function __construct($Product)
    {
        $this->Product = $Product;
    }

    public function headings(): array
    {
        return [
            'Producto',
            'Descripción',
            'Stock',
        ];
    }

    public function collection()
    {
        $collection = [];
        $Product = $this->Product;

        $query = "SELECT p.name name, p.description, p.stock    
                     FROM products p 
                     WHERE deleted_at IS NULL ";

        if (isset($Product)) {
            $query = $query . " AND  p.name LIKE '%" . $Product . "%'";
        }

        $objects = DB::select($query);

        foreach ($objects as $object) {
            $collection[] = [
                'Producto' => $object->name,
                'Descripción' => $object->description,
                'Stock' => $object->stock,
            ];
        }

        return collect($collection);
    }
}
