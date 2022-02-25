<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProductController extends Controller
{
    public function index()
    {
        $objects = Product::orderBy('name')->get();
        return view('products.index', compact('objects'));
    }

    public function create()
    {
        $types = ProductType::orderBy('name')->get();
        return view('products.create', compact('types'));;
    }

    public function store(Request $request)
    {

        $object = new Product();
        $object->name = $request->name;
        $object->description = $request->description;
        $object->stock = $request->stock;
        $object->productTypeId = $request->productTypeId;
        $object->price = $request->price;
        $object->save();

        return redirect('products')->with('success', 'Creado correctamente.');
    }

    public function storeAjax(Request $request)
    {

        $object = new Product();
        $object->name = $request->name;
        $object->description = $request->description;
        $object->stock = $request->stock;
        $object->productTypeId = $request->productTypeId;
        $object->price = $request->price;

        $object->save();

        return $object->toJson();

        
    }

    public function edit($id)
    {
        $object = Product::findOrFail($id);
        $types = ProductType::orderBy('name')->get();
        return view('products.edit', compact('object', 'types'));;
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $object = Product::findOrFail($id);
        $object->name = $request->name;
        $object->description = $request->description;
        $object->stock = $request->stock;
        $object->productTypeId = $request->productTypeId;
        $object->productTypeId = $request->productTypeId;
        $object->price = $request->price;

        $object->save();
        return redirect('products')->with('success', 'Editado correctamente.');
    }

    public function destroy($id)
    {
        Product::destroy($id);
        return redirect('products')->with('success', 'Eliminado correctamente.');
    }

   
}
