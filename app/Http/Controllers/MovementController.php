<?php


namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Movement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class MovementController extends Controller
{
    public function store(Request $request)
    {
        $Client = Client::find($request->clientId);
        $Movement = new Movement();
        $Movement->clientId = $request->clientId;
        $Movement->payment = $request->Abono;
        $Movement->previosDebt = $Client->creditAmount - $Client->availableCredit;
        $Movement->newDebt = $Client->creditAmount - $Client->availableCredit - $request->Abono;
        $Movement->type = 1; // 1 Abono 2 Cargo
        $Movement->save();

        #TO_DO
        //Saldar ventas a credito de la mas antigua a la mas nueva del cliente
        //Segun permita el abono  

        $Client->availableCredit = $Client->availableCredit + $request->Abono;
        $Client->save();
        
        return redirect('clients/pay/'.$request->clientId)->with('success','Abono guardado correctamente.');
    }
   
}
