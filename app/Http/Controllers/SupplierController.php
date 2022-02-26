<?php


namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class SupplierController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $query = Supplier::orderBy('name', 'asc');
        $suppliers = $query->paginate();
        return view('suppliers.index',compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.add');
    }

    public function store(Request $request)
    {
      

        $Supplier = new Supplier();
        $Supplier->name = $request->name;
        $Supplier->address = $request->address;
        $Supplier->phone = $request->phone;
        $Supplier->email = $request->email;
        $Supplier->contact = $request->contact;
        $Supplier->rfc = $request->rfc;

        $Supplier->save();
        
        return redirect('suppliers')->with('success','Proveedor creado correctamente.');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit',compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $Supplier = Supplier::find($id);
        $Supplier->name = $request->name;
        $Supplier->address = $request->address;
        $Supplier->phone = $request->phone;
        $Supplier->email = $request->email;
        $Supplier->contact = $request->contact;
        $Supplier->rfc = $request->rfc;
        $Supplier->save();

        return redirect('suppliers')->with('success','Proveedor editado correctamente.');
    }

    public function destroy($id)
    {
        Supplier::destroy($id);
        return redirect('suppliers')->with('success','Proveedor eliminado correctamente.');
    }


   
}
