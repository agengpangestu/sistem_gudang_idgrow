<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();
        return response()->json([
            'status'    => true,
            'message'   => 'OK',
            'code'      => 200,
            'data'      => $user
        ], 200);
    }

    public function show($id)
    {
        try {
            $user = User::find($id);

            if(!$user) {
                return response()->json([
                    'status'  => false,
                    'message' => 'User not found',
                    'code'    => 404
                ], 404);
            }

            return response()->json([
                'status'  => true,
                'message' => 'OK',
                'code'    => 200,
                'data'    => $user
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

    public function history($id)
    {
        try {
            $user = User::with('mutationRelation')->find($id);

            if (!$user) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'User not found',
                    'code'      => 404
                ], 404);
            }

            return response()->json([
                'status'  => true,
                'message' => 'OK',
                'code'    => 200,
                'data' => [
                    'name'      => $user->name,
                    'email'     => $user->email,
                    'history'   => $user->mutationRelation
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'An error occurred',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
