<?php


namespace App\Http\Controllers;

use App\Models\Client;
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
        $query = Client::orderBy('name', 'asc');
        $clients = $query->paginate();
        return view('clients.index',compact('clients'));
    }

    public function create()
    {
        return view('clients.add');
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
        $Client->save();

        return redirect('clients')->with('success','Cliente editado correctamente.');
    }

    public function destroy($id)
    {
        Client::destroy($id);
        return redirect('clients')->with('success','Cliente eliminado correctamente.');
    }


   
}
