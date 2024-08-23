<?php

namespace App\Http\Controllers;

use App\Models\Mutation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MutationController extends Controller
{
    public function index()
    {
        try {
        $mutation = Mutation::with(['userRelation', 'productRelation'])->get();
        return response()->json([
                'status'  => true,
                'message' => 'OK',
                'code'    => 200,
                'data'    => $mutation
            ], 200);
        } catch (Exception $e) {
            error_log($e);
            return response()->json([
                'status'  => false,
                'message' => 'An error occurred',
                'code'    => 500,
            ], 500);
        }

    }

    public function show($id)
    {
        try {
            $mutation = Mutation::find($id);

            if (!$mutation){
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
                'data'    => $mutation
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'An error occurred',
                'error'   => $e->getMessage(),
                'code'    => $e->getCode(),
            ], $e->getCode());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = $request->validate([
                'user_id'       => 'required|exists:users,id',
                'product_id'    => 'required|exists:products,id',
                'date'          => 'required|date_format:Y-m-d',
                'mutation_type' => 'required|in:in,out',
                'amount'        => 'required|integer',
            ]);

            $mutation = Mutation::create($validator);
            return response()->json([
                'status'  => true,
                'message' => 'OK',
                'code'    => 201,
                'data'    => $mutation
            ], 201);
        } catch(\Illuminate\Validation\ValidationException $ve){
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'error'   => $ve->errors(),
                'code'    => 422,
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'An error occurred',
                'error'   => $e->getMessage(),
                'code'    => $e->getCode(),
            ], $e->getCode());
        }
    }
}

