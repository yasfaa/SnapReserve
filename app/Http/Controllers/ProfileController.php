<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ProfileController extends Controller
{

    public function showAuthPage()
    {
        return Inertia::render('Auth/Login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::attempt($request->only('email', 'password'));
        return redirect('/dashboard');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth')->with('success', 'Logout berhasil.');
    }

    public function getProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->back()->with('error', 'Profile tidak ditemukan.');
        }

        return Inertia::render('Profile', [
            'user' =>
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'bio' => $user->bio,
                    'path' => $user->path ? asset('storage/public/profile_images' . $user->path) : null,
                ]
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

        return redirect()->route('profile')->with('success', 'Profile berhasil diupdate.');
    }
}
