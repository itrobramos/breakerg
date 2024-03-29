<?php


namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Credit;
use App\Models\Movement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class ClientController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $clients = Client::orderBy('name', 'asc')->get();
        return view('clients.index',compact('clients'));
    }

    public function create()
    {
        return view('clients.add');
    }

    public function pay($id){
        $client = Client::findOrFail($id);
        $data['client'] = $client;

        $NextDate = Credit::where('clientId', $id)
                    ->where('currentCredit', '>', 0)
                    ->min('endDate');

        $data['nextDate'] = $NextDate;

        $Movements = Movement::where('clientId', $id)->orderBy('date')->get();
        $data['Movements'] = $Movements;
        
        return view('clients.pay', $data);
    }

    public function store(Request $request)
    {
        $Client = new Client();
        $Client->name = $request->name;
        $Client->address = $request->address;
        $Client->phone = $request->phone;
        $Client->email = $request->email;
        $Client->contact = $request->contact;
        $Client->rfc = $request->rfc;

        if(isset($request->credit)){
            $Client->credit = true;
            $Client->days = $request->days;
            $Client->creditAmount = $request->creditAmount;
            $Client->availableCredit = $request->creditAmount;
        }
        else{
            $Client->credit = false;
            $Client->creditAmount = 0;
            $Client->availableCredit = 0;
            $Client->days = 0;
        }

        $Client->save();
        
        return redirect('clients')->with('success','Cliente creado correctamente.');
    }

    public function storeAjax(Request $request)
    {

        $Client = new Client();
        $Client->name = $request->name;
        $Client->address = $request->address;
        $Client->phone = $request->phone;
        $Client->email = $request->email;
        $Client->contact = $request->contact;
        $Client->rfc = $request->rfc;

        if($request->credit == 1){
            $Client->credit = true;
            $Client->creditAmount = $request->creditAmount;
            $Client->availableCredit = $request->creditAmount;
            $Client->days = $request->days;
        }
        else{
            $Client->credit = false;
            $Client->creditAmount = 0;
            $Client->availableCredit = 0;
            $Client->days = 0;
        }
        $Client->save();
        return $Client->toJson();
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.edit',compact('client'));
    }

    public function update(Request $request, $id)
    {
        $Client = Client::find($id);
        $Client->name = $request->name;
        $Client->address = $request->address;
        $Client->phone = $request->phone;
        $Client->email = $request->email;
        $Client->contact = $request->contact;
        $Client->rfc = $request->rfc;


        if(isset($request->credit)){
            $CreditAmount = Credit::where('clientId', $id)->where('currentCredit', '>', 0)->sum('currentCredit');

            $Client->credit = true;
            $Client->creditAmount = $request->creditAmount;
            $Client->days = $request->days;
            $Client->availableCredit = $request->creditAmount - $CreditAmount;
        }
        else{
            $Client->credit = false;
            $Client->creditAmount = 0;
            $Client->availableCredit = 0;
            $Client->days = 0;
        }

        $Client->save();

        return redirect('clients')->with('success','Cliente editado correctamente.');
    }

    public function destroy($id)
    {
        Client::destroy($id);
        return redirect('clients')->with('success','Cliente eliminado correctamente.');
    }


   
}
