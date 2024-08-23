<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::all();
        return response()->json([
            'status'    => true,
            'message'   => 'OK',
            'code'      => 200,
            'data'      => $product
        ], 200);
    }

    public function show($id)
    {

        try {
            $product = Product::find($id);

            if (!$product){
                return response()->json([
                    'status'  => false,
                    'message' => 'Product not found',
                    'code'    => 404
                ], 404);
            }

            return response()->json([
                'status'  => true,
                'message' => 'OK',
                'code'    => 200,
                'data'    => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'An error occurred',
                'error'   => $e->getMessage(),
                'code'    => $e->getCode(),
            ], $e->getCode());
        }
    }

    public function history($id)
    {
        try {
            $product = Product::with('mutationRelation')->find($id);

            if (!$product) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Product not found',
                    'code'      => 404
                ], 404);
            }

            return response()->json([
                'status'  => true,
                'message' => 'OK',
                'code'    => 200,
                'data'    => [
                    'name'      => $product->product_name,
                    'code'      => $product->product_code,
                    'location'  => $product->location,
                    'price'     => $product->price,
                    'history'   => $product->mutationRelation
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'An error occurred',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_name' => 'required|string',
                'product_code' => 'required|string|unique:products|max:10',
                'location'     => 'required|string|max:10',
                'price'        => 'required|integer'
            ]);

            if ($validator->fails()){
                return response()->json([
                    'status'    => false,
                    'message'   => 'Validation Error',
                    'code'      => 422,
                    'data'      => $validator->errors()
                ], 422);
            }

            $product = Product::create($request->all());

            return response()->json([
                'status'    => true,
                'message'   => 'Create product successfully',
                'code'      => 201,
                'data'      => $product
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'An error occurred',
                'error'   => $e->getMessage(),
                'code'    => $e->getCode(),
            ], $e->getCode());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Product not found',
                    'code'    => 404
                ], 404);
            }

            $validator = $request->validate([
                'product_name' => 'required|string|max:255',
                'location'     => 'required|string|max:10',
                'price'        => 'required|integer'
            ]);

            $product->update($validator);
            return response()->json([
                    'status'  => true,
                    'message' => 'Product update successfully',
                    'code'    => 201
                ], 201);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation Error',
                'code'    => 422,
                'error'   => $ve->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'An error occurred',
                'error'   => $e->getMessage(),
                'code'    => $e->getCode(),
            ], $e->getCode());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $product = Product::find($id);
            if(!$product) {
                return response()->json([
                'status'  => false,
                'message' => 'Product not found',
                'code'    => 404,
            ], 404);
            }

            $product->delete();
            return response()->json([
                'status'  => false,
                'message' => 'Delete product successfully',
                'code'    => 200,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'An error occurred',
                'error'   => $e->getMessage(),
                'code'    => $e->getCode(),
            ], $e->getCode());
        }
    }
}
