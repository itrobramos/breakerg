<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\EntryDetail;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\EntriesExport;
use App\Exports\EntryDetailsExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class EntryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $objects = Entry::orderByDesc('date')->get();
        $suppliers = Supplier::orderBy('name')->get();    

        return view('entries.index',compact('objects', 'suppliers'));
    }

    public function indexPost(Request $request){

        $fechaInicio = $request->FechaInicio;
        $fechaFin = $request->FechaFin;
        $supplierId = $request->supplierId;
        
        $objects = Entry::orderBy('date', 'desc');
        $suppliers = Supplier::orderBy('name')->get();    

        if(isset($request->FechaInicio)){
            $objects->where('date', '>=', $fechaInicio);
        }   

        if(isset($request->FechaFin)){
            $objects->where('date', '<=', $fechaFin);
        }   

        if(isset($request->supplierId)){
            $objects->where('supplierId',  $supplierId);
        }   

        $objects = $objects->get();
        
        $Parameters = ["FechaInicio" => $fechaInicio, 
                       "FechaFin" => $fechaFin,
                       "SupplierId" => $supplierId
                      ];
                       

        return view('entries.index',compact('objects', 'suppliers', 'Parameters'));
    }


    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();    
        $products = Product::orderBy('name')->get();    
        $types = ProductType::orderBy('name')->get();

        return view('entries.create', compact('suppliers', 'products', 'types'));
    }

    public function store(Request $request)
    {

        \DB::beginTransaction();
        try {

            $object = new Entry();
            $object->supplierId = $request->supplierId;
            $object->date = $request->date;
            $object->totalCost = 0;
            $object->folio = $request->folio;
            
            $object->save();

            $Total = 0;


            if(isset($request->product)){
                foreach($request->product as $product){

                    $EntryDetail = new EntryDetail();
                    $EntryDetail->entryId = $object->id;
                    $EntryDetail->productId = $product['productId'];
                    $EntryDetail->quantity = $product['quantity'];
                    $EntryDetail->unitPrice = $product['unitPrice'];
                    
                    $EntryDetail->save();

                    $Total = $Total +  ($EntryDetail->unitPrice * $EntryDetail->quantity);
                    
                    //Actualizamos existencias del producto

                    $Producto = Product::find($EntryDetail->productId);
                    $Producto->stock = $Producto->stock + $EntryDetail->quantity;
                    $Producto->save();                
                }

                $object->totalCost = $Total;
                $object->save();
            }

            \DB::commit();
            return redirect('entries')->with('success','Creada correctamente.');
        } catch (\Throwable $th) {
            dd($th);
            \DB::rollback();
            return redirect('entries')->with('warning', 'Error al crear la entrada.');
        }
    }

    public function show($id)
    {
        $object = Entry::findOrFail($id);
        $details = EntryDetail::where('entryId', $id)->get();

        return view('entries.show',compact('object', 'details'));
    }


    public function edit($id)
    {
        $object = Entry::findOrFail($id);
        return view('entries.edit',compact('object'));
    }

    
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $object = Entry::findOrFail($id);
        $object->name = $request->name;
        $object->save();

        return redirect('entries')->with('success','Editado correctamente.');
    }

   
    public function destroy($id)
    {
        $details = EntryDetail::where('entryId', $id)->get();

        foreach($details as $detail){
            $Product = Product::find($detail->productId);
            $Product->stock = $Product->stock - $detail->quantity;
            $Product->save();
        }

        Entry::destroy($id);
        return redirect('entries')->with('success','Eliminado correctamente.');
    }

    public function export(Request $request){

        $fechaInicio = $request->FechaInicio;
        $fechaFin = $request->FechaFin;
        $supplierId = $request->supplierId;

        return Excel::download(new EntriesExport($fechaInicio,$fechaFin,$supplierId), 'EntradaMercancia.xlsx');
    }

    public function products()
    {
        $objects = DB::select("SELECT e.folio, p.name product, ed.quantity, ed.unitPrice, s.name supplier, e.date
                    FROM entries e INNER JOIN entry_details ed ON e.id = ed.entryId
                                 INNER JOIN products p ON p.id = ed.productId
                                 INNER JOIN suppliers s ON s.id = e.supplierId
                    ");

        $suppliers = Supplier::orderBy('name')->withTrashed()->get();
        $products = Product::orderBy('name')->withTrashed()->get();

        return view('entries.products', compact('objects', 'suppliers', 'products'));
    }

    public function productsPost(Request $request)
    {

        $fechaInicio = $request->FechaInicio;
        $fechaFin = $request->FechaFin;
        $supplierId = $request->supplierId;
        $productId = $request->productId;

        $query = "SELECT e.folio, p.name product, ed.quantity, ed.unitPrice, s.name supplier, e.date
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

        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        $Parameters = [
            "FechaInicio" => $fechaInicio,
            "FechaFin" => $fechaFin,
            "SupplierId" => $supplierId,
            "ProductId" => $productId,
        ];

        return view('entries.products', compact('objects', 'suppliers', 'products', 'Parameters'));
    }


    public function productsExport(Request $request)
    {
        $fechaInicio = $request->FechaInicio;
        $fechaFin = $request->FechaFin;
        $supplierId = $request->supplierId;
        $productId = $request->productId;


        return Excel::download(new EntryDetailsExport($fechaInicio, $fechaFin, $supplierId, $productId), 'EntradasDetallado.xlsx');
    }

   
}
