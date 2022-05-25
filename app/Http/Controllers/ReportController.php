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
        GROUP BY YEAR(date), MONTH(date)");

        $Ingresos = DB::select("SELECT YEAR(date) year, MONTH(date) month, SUM(total) total 
        FROM sales
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
        GROUP BY YEAR(date), MONTH(date)");

        $Ingresos = DB::select("SELECT YEAR(date) year, MONTH(date) month, SUM(total) total 
        FROM sales
        WHERE created_at BETWEEN '" . $fechaInicio . "' AND '" . $fechaFin . "'  
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

        $query = "SELECT p.name name, p.stock    
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
        WHERE currentCredit > 0 ";

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
        $data['credits'] = $Credits;

        $clients = Client::orderBy('name')->get();
        $data['clients'] = $clients;

        $Parameters = [
            "ClientId" => $clientId,
            "FechaVencimiento" => $fechaVencimiento,
            "Folio" => $folio
        ];

        $data["Parameters"] = $Parameters;

        return view('reports.activeCredits', $data);
    }


    public function partialpayments(){
        $query = "SELECT movements.id, movements.payment, movements.previosDebt, movements.newDebt, clients.name, movements.created_at 
        FROM movements JOIN clients on movements.clientId = clients.id
        WHERE type = 1 ";

        $movements = DB::select($query);
        $data['movements'] = $movements;

        $clients = Client::orderBy('name')->get();
        $data['clients'] = $clients;

        return view('reports.partialPayments', $data);
    }
}
