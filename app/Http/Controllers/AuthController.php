<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function registerUser(Request $request) {

        $validasi = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirmPassword' => 'required|same:password'
        ]);

        if($validasi->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Proses validasi gagal',
                'data' => $validasi->errors()
            ],401);
        }
        $request->password = Hash::make($request->password);
        $user = User::create([
            'role_id' => 2,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil membuat user baru'
        ],200);
    }

    public function loginUser(Request $request) {

        $validasi = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
            'confirmPassword' => 'required|same:password'
        ]);

        if($validasi->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Proses login gagal',
                'data' => $validasi->errors()
            ],401);
        }

        if(!Auth::attempt($request->only('email','password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password anda salah'
            ],401);
        }

        $user = User::with('roles')->where('email',$request->email)->first();
        $token = Token::where('tokenable_id',$user->id)->first();
        if($token != null) {
            $now = Carbon::now();
            if($token->expires_at > $now) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token login anda masih aktif. Silakan tunggu hingga kadaluarsa (5 menit dari saat anda login).'
                ], 401);
            } else {
                $token->delete();
            }
        }
        $arrayRole = [$user->roles->name];
        $newToken = $user->createToken('user-token',$arrayRole,Carbon::now()->addMinutes(5))->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Anda berhasil login',
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
                'token' => $newToken
            ]
        ],200);

        // TOKEN AKAN DI SIMPAN DI TABEL PERSONAL ACCESS TOKEN


    }
}
