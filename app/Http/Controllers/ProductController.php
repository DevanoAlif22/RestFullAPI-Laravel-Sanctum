<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class productController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Product::orderBy('id','asc')->get();
        return response()->json([
            'success' => true,
            'message' => 'Data ditemukan',
            'data' => $data
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeProduct(Request $request)
    {


        $validasi = Validator::make($request->all(),[
            'name' => 'required',
            'price' => 'required',
            'description' => 'required'
        ]);

        if($validasi->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data',
                'data' => $validasi->errors()
            ],400);
        }

        $data = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data',
            'data' => $data
        ],200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateProduct(Request $request, $id)
    {
        $validasi = Validator::make($request->all(),[
            'name' => 'sometimes|nullable',
            'price' => 'sometimes|nullable',
            'description' => 'sometimes|nullable'
        ]);

        if($validasi->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi edit gagal',
                'data' => $validasi->errors()
            ],401);
        }

        $product = Product::where('id',$id)->first();
        if($product == null) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ],400);
        }

        if($request->name != null) {
            $product->name = $request->name;
        }
        if($request->price != null) {
            $product->price = $request->price;
        }
        if($request->description != null) {
            $product->description = $request->description;
        }

        $product->save();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil edit product',
            'data' => $product
        ],200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyProduct(Request $request)
    {

        $id = $request->id;
        if($id == null) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi edit gagal',
                'data' => $validasi->errors()
            ],401);
        }

        $product = Product::where('id',$id)->first();
        if($product == null) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ],400);
        }

        $data = $product;
        $product->delete();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil hapus product',
            'data' => $data
        ],200);

    }
}
