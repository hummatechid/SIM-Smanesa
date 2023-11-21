<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    use HasApiTokens;
    
    public function login(Request $request){
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Tidak terautentikasi'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response()->json([
                'message' => 'Akun tidak ditemukan'
            ], 404);
        }

        if($user->password != bcrypt($request->password)){
            return response()->json([
                'message' => 'Password yang anda masukkan salah'
            ], 400);
        }

        $pengguna = Pengguna::where("user_id",$user->id)->first();
        $user->pengguna = $pengguna;

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $pengguna
        ]);
    }

    public function logout(Request $request){
        if(!Auth::check()) {
            return;
        }
        $id = Auth::id();
        $user = User::find($id);
        $user->tokens()->delete();
        return response()->json([
            'message' => 'logout success'
        ]);
    }
}
