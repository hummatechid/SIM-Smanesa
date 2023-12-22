<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
    public function login(Request $request){
        // check request send
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Tidak terautentikasi'
            ], 401);
        }

        // check email user
        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response()->json([
                'message' => 'Akun tidak ditemukan'
            ], 404);
        }

        // check password user
        if(!Hash::check($request->password,$user->password)){
            return response()->json([
                'message' => 'Password yang anda masukkan salah'
            ], 400);
        }

        $check_roles = Role::where("id",$user->role_id)->where("name","pimpinan")->first();
        if(!$check_roles){
            return response()->json([
                'message' => 'Akun ini tidak terautentikasi, silahkan login dengan akun yang sesuai'
            ], 401);
        }

        if(!$user->device_token) $user->device_token = $request->device_token;

        // get detail data user
        $pengguna = Pengguna::where("user_id",$user->id)->first();
        $user->pengguna = $pengguna;

        // set auth token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $user
        ]);
    }

    public function logout(Request $request){
        if(!Auth::guard("sanctum")->check()) {
            return response()->json([
                'message' => 'Tidak terautentikasi'
            ], 401);
        }
        // 
        $id = Auth::guard("sanctum")->id();
        $user = User::find($id);
        $user->tokens()->delete();
        // $user->update(['device_token' => null]);

        return response()->json([
            'message' => 'logout success'
        ], 200);
    }

    public function getToken(){
        if(!Auth::guard("sanctum")->check()) {
            return response()->json([
                'message' => 'Tidak terautentikasi'
            ], 401);
        }
        // 
        $id = Auth::guard("sanctum")->id();

        $token_access = DB::table('personal_access_tokens')->where("tokenable_id", $id)->first();
        if(!$token_access){
            return response()->json([
                "status" => "error",
                'message' => 'Token user tidak terdaftar'
            ], 404);
        }

        return response()->json([
            "status" => "success",
            'message' => 'Berhasil akses',
            "data" => $token_access
        ], 200);
    }
}
