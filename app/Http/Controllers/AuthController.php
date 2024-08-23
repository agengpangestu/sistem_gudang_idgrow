<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'      => 'required|string',
                'email'     => 'required|email|unique:users|string',
                'password'  => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Validation Error',
                    'code'      => 422,
                    'data'      => $validator->errors()
                ], 422);
            }

            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => bcrypt($request->password),
            ]);

            return response()->json([
                'status'    => true,
                'message'   => 'Create user successfully',
                'code'      => 201,
                'data'      => $user
            ], 201);
        } catch (Exception $e) {
            error_log($e);
            return response()->json([
                'status'  => false,
                'message' => 'An error occurred',
                'error'   => $e->getMessage(),
                'code'    => $e->getCode(),
            ], $e->getCode());
        }
    }

    public function login(Request $request)
    {
        try {

        $validator = $request->validate([
            'email'    => 'required|email|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $validator['email'])->first();

        if (!$user) {
            return response()->json([
                'success'   => false,
                'message'   => 'Email not registered',
                'code'      => 422
            ], 422);
        }
        if (!Hash::check($validator['password'], $user->password)) {
            return response()->json([
                'success'   => false,
                'message'   => 'Wrong password',
                'code'      => 422
            ], 422);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $data['name'] = $user->name;

        return response()->json([
                'status'        => true,
                'message'       => 'Success login',
                'token_type'    => 'Bearer',
                'data'          => $data,
                'access_token'  => $token
            ]);

        } catch (\Illuminate\Validation\ValidationException $ve){
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'error'   => $ve->errors(),
                'code'    => 422,
            ], 422);
        }
        catch (Exception $e) {
            error_log($e);
            return response()->json([
                'status'  => false,
                'message' => 'An error occurred',
                'error'   => $e->getMessage(),
                'code'    => $e->getCode(),
            ], $e->getCode());
        }
    }
}
