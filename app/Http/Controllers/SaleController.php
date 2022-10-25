<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Auth;
use App\Exports\SalesExport;
use App\Exports\SalesDetailsExport;
use App\Models\Credit;
use App\Models\Movement;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class SaleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $objects = Sale::orderBy('date', 'desc')->get();
        $clients = Client::orderBy('name')->get();
        return view('sales.index', compact('objects', 'clients'));
    }

    public function indexPost(Request $request)
    {
        $fechaInicio = $request->FechaInicio;
        $fechaFin = $request->FechaFin;
        $clientId = $request->clientId;
        $folio = $request->Folio;

        $objects = Sale::orderBy('date', 'desc');
        $clients = Client::orderBy('name')->get();

        if (isset($request->FechaInicio)) {
            $objects->where('date', '>=', $fechaInicio);
        }

        if (isset($request->FechaFin)) {
            $objects->where('date', '<=', $fechaFin);
        }

        if (isset($request->clientId)) {
            $objects->where('clientId',  $clientId);
        }

        if (isset($request->Folio)) {
            $objects->where('folio',  $folio);
        }

        $objects = $objects->get();

        $Parameters = [
            "FechaInicio" => $fechaInicio,
            "FechaFin" => $fechaFin,
            "ClientId" => $clientId,
            "Folio" => $folio
        ];


        return view('sales.index', compact('objects', 'clients', 'Parameters'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $types = ProductType::orderBy('name')->get();

        return view('sales.create', compact('clients', 'products', 'types'));
    }

    public function store(Request $request)
    {

        \DB::beginTransaction();
        try {

            $object = new Sale();
            $object->clientId = $request->clientId;
            $object->date = $request->date;
            $object->total = 0;
            $object->folio = $request->folio;
            $object->userId = Auth::user()->id;

            $object->save();
            $Total = 0;

            //Guardado de imagen
            if ($request->hasFile('file')) {

                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension(); // getting image extension
                $filename = time() . '.' . $extension;
                $file->move('salesimg/', $filename);
                $object->imageUrl = "salesimg/" . $filename;
            }
            
            if (isset($request->product)) {
                foreach ($request->product as $product) {

                    $SaleDetail = new SaleDetail();
                    $SaleDetail->saleId = $object->id;
                    $SaleDetail->productId = $product['productId'];
                    $SaleDetail->quantity = $product['quantity'];
                    $SaleDetail->price = $product['unitPrice'];

                    $SaleDetail->save();

                    $Total = $Total +  ($SaleDetail->price   * $SaleDetail->quantity);

                    //Actualizamos existencias del producto

                    $Producto = Product::find($SaleDetail->productId);
                    $Producto->stock = $Producto->stock - $SaleDetail->quantity;
                    $Producto->save();
                }

                $object->total = $Total;
                $object->save();
            }

            if($request->paymentType == "Credito"){
                $Client = Client::find($request->clientId);

                $credit = new Credit();
                $credit->saleId = $object->id;
                $credit->clientId = $request->clientId;
                $credit->initialPayment = $request->PagoInicial;
                $credit->credit = $request->montoCredito;
                $credit->total = $request->TotalVenta;

                $credit->currentCredit = $request->montoCredito;
                $credit->beginDate = date("Y-m-d");
                $credit->endDate = date('Y-m-d', strtotime("+" . $Client->days . " days"));
                $credit->save();

           
                $Movement = new Movement();
                $Movement->clientId = $request->clientId;
                $Movement->payment = $request->montoCredito;
                $Movement->previosDebt = $Client->creditAmount - $Client->availableCredit;
                $Movement->newDebt = $Client->creditAmount - $Client->availableCredit + $request->montoCredito;
                $Movement->type = 2; // 1 Abono 2 Cargo
                $Movement->saleId =  $object->id;
                $Movement->date = $request->date;
                $Movement->save();
           
                $Client->availableCredit = $Client->availableCredit -  $request->montoCredito;
                $Client->save();

            }
            \DB::commit();
            return redirect('sales/add')->with('success', 'Venta creada correctamente.');
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect('sales/add')->with('danger', 'Error al crear la venta.');
        }
    }

    


    public function show($id)
    {
        $object = Sale::findOrFail($id);
        $details = SaleDetail::where('saleId', $id)->get();

        return view('sales.show', compact('object', 'details'));
    }


    public function edit($id)
    {
        $object = Sale::findOrFail($id);
        return view('sales.edit', compact('object'));
    }


    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $object = Sale::findOrFail($id);
        $object->name = $request->name;
        $object->save();

        return redirect('sales')->with('success', 'Editado correctamente.');
    }


    public function destroy($id)
    {
        $details = SaleDetail::where('saleId', $id)->get();

        foreach($details as $detail){
            $Product = Product::find($detail->productId);
            $Product->stock = $Product->stock + $detail->quantity;
            $Product->save();
        }

        Sale::destroy($id);
        return redirect('sales')->with('success', 'Eliminado correctamente.');
    }

    public function export(Request $request)
    {

        $fechaInicio = $request->FechaInicio;
        $fechaFin = $request->FechaFin;
        $clientId = $request->clientId;
        $folio = $request->Folio;

        return Excel::download(new SalesExport($fechaInicio, $fechaFin, $clientId, $folio), 'Ventas.xlsx');
    }

    public function products()
    {
        $objects = DB::select("SELECT p.name product, cr.endDate,sd.quantity, sd.price, c.name client, s.date, s.folio
                    FROM sales s INNER JOIN sale_details sd ON s.id = sd.saleId
                                 INNER JOIN products p ON p.id = sd.productId
                                 INNER JOIN clients c ON c.id = s.clientId
                                 LEFT JOIN credits cr ON s.id = cr.saleId
                                 WHERE s.deleted_at IS NULL 
                    ");

        $clients = Client::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('sales.products', compact('objects', 'clients', 'products'));
    }

    public function productsPost(Request $request)
    {

        $fechaInicio = $request->FechaInicio;
        $fechaFin = $request->FechaFin;
        $clientId = $request->clientId;
        $productId = $request->productId;
        $folio = $request->Folio;

        $query = "SELECT p.name product, cr.endDate, sd.quantity, sd.price, c.name client, s.date, s.folio
        FROM sales s INNER JOIN sale_details sd ON s.id = sd.saleId
                     INNER JOIN products p ON p.id = sd.productId
                     INNER JOIN clients c ON c.id = s.clientId
                     LEFT JOIN credits cr ON s.id = cr.saleId
                     WHERE 1 = 1 
                     AND s.deleted_at IS NULL ";


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

        $clients = Client::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        $Parameters = [
            "FechaInicio" => $fechaInicio,
            "FechaFin" => $fechaFin,
            "ClientId" => $clientId,
            "ProductId" => $productId,
            "Folio" => $folio
        ];

        return view('sales.products', compact('objects', 'clients', 'products', 'Parameters'));
    }


    public function productsExport(Request $request)
    {
        $fechaInicio = $request->FechaInicio;
        $fechaFin = $request->FechaFin;
        $clientId = $request->clientId;
        $productId = $request->productId;
        $folio = $request->Folio;


        return Excel::download(new SalesDetailsExport($fechaInicio, $fechaFin, $clientId, $productId, $folio), 'VentasDetallado.xlsx');
    }
}
