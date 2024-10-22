<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::get();

        if ($products->count() > 0) {
            return response()->json(["status" => 200, "products" => $products], 200);
        } else {
            return response()->json(["status" => 404, "message" => "No Records Found"], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);



        if ($validator->fails()) {
            return response()->json(["status" => 400, "message" => $validator->errors()], status: 400);
        } else {
            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
            ]);

            return response()->json(["status" => 200, "products" => $product, "message" => "Product Created Successfully",], 200);
        }
    }

    public function show($id)
    {
        $product = Product::find($id);
        if ($product) {
            return response()->json(["status" => 200, "product" => $product], 200);
        } else {
            return response()->json(["status" => 404, "message" => "Product Not Found"], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'price' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $product = Product::find($id);
        if ($product) {
            if ($validator->fails()) {
                return response()->json(["status" => 400, "message" => $validator->errors()], status: 400);
            } else {
                $product->update([
                    'name' => $request->name,
                    'price' => $request->price,
                    'description' => $request->description,
                ]);
                return response()->json(["status" => 200, "products" => $product, "message" => "Product Updated Successfully",], 200);
            }
        } else {
            return response()->json(["status" => 404, "message" => "Product Not Found",], 404);
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            return response()->json(["status" => 200, "message" => "Product Deleted Successfully",], 200);
        } else {
            return response()->json(["status" => 404, "message" => "Product Not Found",], 404);
        }

    }
}
