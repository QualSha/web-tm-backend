<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);
        $user = User::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password)]);
        return response()->json(['message' => 'Registrasi berhasil', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), ['email' => 'required|email', 'password' => 'required|string']);
        if ($validator->fails()) return response()->json($validator->errors(), 422);
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Email atau Password salah'], 401);
        }
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['message' => 'Login berhasil', 'access_token' => $token, 'token_type' => 'Bearer', 'user' => $user]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }
}