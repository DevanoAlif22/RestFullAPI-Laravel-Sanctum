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
    public function checkToken() {
        return response()->json([
            'success' => true,
            'message' => 'Token Masih Aktif'
        ],200);
    }

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
            'password' => 'required'
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
        $arrayRole = [$user->roles->name];
        $newToken = $user->createToken('user-token',$arrayRole,Carbon::now()->addMinutes(30))->plainTextToken;

        if($token != null) {
            $now = Carbon::now();
            if($token->expires_at > $now) {
                return response()->json([
                    'success' => true,
                    'message' => 'Token login anda masih aktif. Silakan tunggu hingga kadaluarsa (30 menit dari saat anda login).',
                    'data' => [
                        'role_id' => $user->role_id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'token' => $newToken
                    ]
                ], 200);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Anda berhasil login',
            'data' => [
                'role_id' => $user->role_id,
                'name' => $user->name,
                'email' => $user->email,
                'token' => $newToken
            ]
        ],200);

        // TOKEN AKAN DI SIMPAN DI TABEL PERSONAL ACCESS TOKEN
    }

    public function logout(Request $request)
    {
    // Mendapatkan user yang sedang terautentikasi
    $user = $request->user();
        if ($user) {
            // Menghapus token akses yang digunakan saat ini
            $user->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Anda berhasil logout.',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada pengguna yang sedang login.',
            ], 401);
        }
    }

}
