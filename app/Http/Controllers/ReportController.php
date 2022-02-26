<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
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

    


  
}
