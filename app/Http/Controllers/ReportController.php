<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Product;
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

    public function cashflow(){
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

    public function cashflowDate(Request $request){

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
      
        $Parameters = ["FechaInicio" => $fechaInicio, 
                       "FechaFin" => $fechaFin];
                       
        $data["CashFLowGraph2"] = $Egresos;
        $data["CashFLowGraph"] = $Ingresos;
        $data["Parameters"] = $Parameters;

        return view('reports.cashflow', $data);
    }

    
    public function inventary(Request $request){
        $objects = Product::orderBy('name')->get();
        return view('reports.products', compact('objects'));
    }

    public function inventaryPost(Request $request){
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

    public function inventaryExport(Request $request){
        $Product = $request->Product;

        return Excel::download(new InventaryExport($Product), 'Inventario.xlsx');
    }
  
}
