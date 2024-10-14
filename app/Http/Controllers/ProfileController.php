<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'name' => 'required'
        ]);

        User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Akun telah berhasil dibuat'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email atau password salah.'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role' => $user->role,
            'name' => $user->name,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil!'
        ], 200);
    }

    public function getProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Profil tidak ditemukan'], 404);
        }

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'bio' => $user->bio,
            'path' => $user->path ? asset('storage/public/profile_images' . $user->path) : null,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'bio' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $user = Auth::user();

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('public/profile_images');
            $fileName = basename($path);

            $user->path = $fileName;
        }

        $user->update([
            'name' => $request->name,
            'bio' => $request->bio,
        ]);

        $user->save();

        return response()->json([
            'message' => 'Berhasil memperbarui data pengguna',
            'image_url' => $user->path ? asset('storage/public/menu_images/' . $user->path) : null,
        ], 200);
    }
}
