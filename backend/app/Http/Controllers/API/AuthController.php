<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:8'
            ]);
            if ($request->name) {
                DB::beginTransaction();
                $model = new User();
                $model->name = ucwords($request->name); // Membuat huruf awal pada kata menjadi kapital
                $model->email = $request->email;
                $model->password = Hash::make($request->password);
                $model->role_id = 2;
                $model->save();
                // $token = $model->createToken('auth_token')->plainTextToken;
                DB::commit();
                return response()->json([
                    'data' => $model,
                    // 'access_token' => $token,
                    'token_type' => 'Bearer'
                ]);
            }
            else {
                throw new \Exception("Pastikan isian sudah lengkap");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }


    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Harap mengisi email atau password'
            ], 401);
        }
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Username atau password salah'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function logout()
    {
        $user = User::find(Auth::user()->id);
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'logout success'
        ]);
    }
}
