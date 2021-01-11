<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        $products = auth()->user()->products;

        return response()->json([
            'success' => true, 
            "data" => $products
        ]);
    }

    public function show($id) {
        $product = auth()->user()->products()->find($id);

        if(!$product) {
            return response()->json([
                'success' => false,
                'data' => 'Product with'. $id . 'not found',
            ], 400);
        }

        return response()->json([
            'success' => true, 
            "data" => $product->toArray(),
        ], 400);
    } 

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|integer'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;

        if(auth()->user()->products()->save($product)){
            return response()->json([
                'success' => true,
                'data' => $product->toArray(),
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Product could not be found',
            ], 500);   
        }     
    }

    public function update(Request $request, $id){
        $product = auth()->user()->products()->find($id);

        if(!$product){
            return response()->json([
                'success' => false,
                'message' => 'Product with'. $id . 'not found',
            ], 500);
        }

        $updated = $product->fill($request->all())-save();

        if($updated){
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Product could not be updated',
            ], 500);
        }
    } 

    public function destroy($id){
        $product = auth()->user()->products()->find($id);

        if(!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product with'. $id . 'not found',
            ], 500);
        }
        
        if($product->delete()){
            return response()->json([
                'success' => true,
                'message' => 'Product successfully deleted',
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Product could not be deleted',
            ], 500);
        }
    }
}
