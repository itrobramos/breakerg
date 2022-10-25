<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Client;
use App\Models\Credit;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventaryExport;
use App\Exports\ActiveCreditsExport;
use App\Exports\PartialPaymentsExport;
use DB;

class ReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('reports.index');
    }

    public function cashflow()
    {
        $Egresos = DB::select("SELECT YEAR(date) year, MONTH(date) month, SUM(totalcost) total
        FROM entries
        WHERE deleted_at IS NULL
        GROUP BY YEAR(date), MONTH(date)");

        $Ingresos = DB::select("SELECT YEAR(date) year, MONTH(date) month, SUM(total) total
        FROM sales
        WHERE deleted_at IS NULL
        GROUP BY YEAR(date), MONTH(date)");

        $data["CashFLowGraph2"] = $Egresos;
        $data["CashFLowGraph"] = $Ingresos;

        return view('reports.cashflow', $data);
    }

    public function cashflowDate(Request $request)
    {

        $fechaInicio = $request->FechaInicio;
        $fechaFin = $request->FechaFin;

        $Egresos = DB::select("SELECT YEAR(date) year, MONTH(date) month, SUM(totalcost) total
        FROM entries
        WHERE created_at BETWEEN '" . $fechaInicio . "' AND '" . $fechaFin . "'
        AND deleted_at IS NULL
        GROUP BY YEAR(date), MONTH(date)");

        $Ingresos = DB::select("SELECT YEAR(date) year, MONTH(date) month, SUM(total) total
        FROM sales
        WHERE created_at BETWEEN '" . $fechaInicio . "' AND '" . $fechaFin . "'
        AND deleted_at IS NULL
        GROUP BY YEAR(date), MONTH(date)");

        $Parameters = [
            "FechaInicio" => $fechaInicio,
            "FechaFin" => $fechaFin
        ];

        $data["CashFLowGraph2"] = $Egresos;
        $data["CashFLowGraph"] = $Ingresos;
        $data["Parameters"] = $Parameters;

        return view('reports.cashflow', $data);
    }


    public function inventary(Request $request)
    {
        $objects = Product::orderBy('name')->get();
        return view('reports.products', compact('objects'));
    }

    public function inventaryPost(Request $request)
    {
        $Product = $request->Product;

        $query = "SELECT p.name name, p.description, p.stock
                     FROM products p
                     WHERE 1 = 1 ";

        if (isset($Product)) {
            $query = $query . " AND  p.name LIKE '%" . $Product . "%'";
        }

        $objects = DB::select($query);

        $Parameters = [
            "Product" => $Product
        ];

        return view('reports.products', compact('objects', 'Parameters'));
    }

    public function inventaryExport(Request $request)
    {
        $Product = $request->Product;
        return Excel::download(new InventaryExport($Product), 'Inventario.xlsx');
    }

    public function clientscredit()
    {
        $query = "SELECT sales.folio, clients.name, credits.endDate, credits.total, credits.currentCredit
        FROM credits JOIN sales on credits.saleId = sales.id
                     JOIN clients on credits.clientId = clients.id
        WHERE currentCredit > 0 
        AND sales.deleted_at IS NULL ";

        $Credits = DB::select($query);
        $data['credits'] = $Credits;

        $clients = Client::orderBy('name')->get();
        $data['clients'] = $clients;


        return view('reports.activeCredits', $data);
    }

    public function clientsCreditPost(Request $request)
    {
        $folio = $request->Folio;
        $clientId = $request->clientId;
        $fechaVencimiento = $request->fechaVencimiento;
        $fechaInicio = $request->fechaInicio;

        $query = "SELECT sales.folio, clients.name, credits.endDate, credits.total, credits.currentCredit
            FROM credits JOIN sales on credits.saleId = sales.id
                         JOIN clients on credits.clientId = clients.id  
            WHERE currentCredit > 0 
            AND sales.deleted_at IS NULL ";

        if (isset($clientId)) {
            $query = $query . " AND clients.id = " . $clientId;
        }

        if(isset($fechaInicio)){
            $query = $query . "AND credits.endDate >= '" . $fechaInicio . "'";
        }

        if (isset($fechaVencimiento)) {
            $query = $query . " AND credits.endDate <= '" . $fechaVencimiento . "'";
        }

        if (isset($folio)) {
            $query = $query . " AND sales.folio = " . $folio;
        }

        $Credits = DB::select($query);
        $data['credits'] = $Credits;

        $clients = Client::orderBy('name')->get();
        $data['clients'] = $clients;

        $Parameters = [
            "ClientId" => $clientId,
            "FechaVencimiento" => $fechaVencimiento,
            "FechaInicio" => $fechaInicio,
            "Folio" => $folio
        ];

        $data["Parameters"] = $Parameters;

        return view('reports.activeCredits', $data);
    }


    public function partialpayments(){
        $query = "SELECT movements.id, movements.payment, movements.previosDebt, movements.newDebt, clients.name, movements.date
        FROM movements JOIN clients on movements.clientId = clients.id
        WHERE type = 1 
        AND movements.deleted_at IS NULL
        ORDER BY movements.date";

        $movements = DB::select($query);
        $data['movements'] = $movements;

        $clients = Client::orderBy('name')->get();
        $data['clients'] = $clients;

        return view('reports.partialPayments', $data);
    }

    public function partialpaymentsPost(Request $request){

        // $folio = $request->Folio;
        $clientId = $request->clientId;
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;

        $query = "SELECT movements.id, movements.payment, movements.previosDebt, movements.newDebt, clients.name, movements.date
        FROM movements
        LEFT JOIN sales on movements.saleId = sales.id
        JOIN clients on movements.clientId = clients.id
        WHERE type = 1 
        AND sales.deleted_at IS NULL 
        AND movements.deleted_at IS NULL
        ";

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

        // if (isset($folio)) {
        //     $query = $query . " AND sales.folio = " . $folio;
        // }

        $movements = DB::select($query);
        $data['movements'] = $movements;

        $clients = Client::orderBy('name')->get();
        $data['clients'] = $clients;

        $Parameters = [
            "ClientId" => $clientId,
            "FechaInicio" => $fechaInicio,
            "FechaFin" => $fechaFin,
            // "Folio" => $folio
        ];

        $data["Parameters"] = $Parameters;

        return view('reports.partialPayments', $data);
    }

    public function clientscreditExport(Request $request)
    {
        $fechaVencimiento = $request->fechaVencimiento;
        $fechaInicio = $request->fechaInicio;
        $clientId = $request->clientId;
        $folio = $request->Folio;

        
        return Excel::download(new ActiveCreditsExport($folio, $clientId, $fechaVencimiento, $fechaInicio), 'Creditos Activos.xlsx');
    }

    public function partialPaymentsExport(Request $request)
    {
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;
        $clientId = $request->clientId;
        
        return Excel::download(new PartialPaymentsExport($clientId, $fechaInicio, $fechaFin), 'Pagos parciales.xlsx');
    }
}
