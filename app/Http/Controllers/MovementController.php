<?php


namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Credit;
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
        $Movement->date = $request->Date;
        $Movement->previosDebt = $Client->creditAmount - $Client->availableCredit;
        $Movement->newDebt = $Client->creditAmount - $Client->availableCredit - $request->Abono;
        $Movement->type = 1; // 1 Abono 2 Cargo
        $Movement->save();
      
        $abono = $request->Abono;
        $credits = Credit::where('clientId', $request->clientId)
                    ->where('currentCredit','>', 0)  
                    ->orderBy('endDate')
                    ->get(); 

        foreach($credits as $credit ){
            
            if($abono > 0){
                
                if($credit->currentCredit > $abono){
                    //Se abona
                    $aAbonar = $abono;
                    $credit->currentCredit = $credit->currentCredit - $aAbonar ;
                    $credit->save();
                    $abono = $abono - $aAbonar;

                }
                else{
                    //Se liquida
                    $abono = $abono - $credit->currentCredit;
                    $credit->currentCredit = 0;
                    $credit->save();
                }
            }
            else{
                break;
            }
        }


        $Client->availableCredit = $Client->availableCredit + $request->Abono;
        $Client->save();
        
        return redirect('clients/pay/'.$request->clientId)->with('success','Abono guardado correctamente.');
    }
   
}
