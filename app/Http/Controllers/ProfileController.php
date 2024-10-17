<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        $posts = $this->getAllMyPosts();

        return Inertia::render('Profile', [
            'user' =>
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'bio' => $user->bio,
                    'path' => $user->path ? asset('storage/profile_images/' . $user->path) : null,
                    'posts' => $posts
                ],
            'postsCount' => count(json_decode($posts->getContent())->posts),
        ]);
    }

    public function getAllMyPosts()
    {
        $user = auth()->user();

        $posts = Post::where('user_id', $user->id)->where('status', 'active')->get(['id', 'path']);

        // Map to include the full path
        $imageUrls = $posts->map(function ($post) {
            return [
                'id' => $post->id, // Include ID
                'path' => asset('storage/post_images/' . $post->path),
            ];
        });

        return response()->json([
            'message' => 'Posts retrieved successfully',
            'posts' => $imageUrls->toArray(), // Convert to array
        ], 200);
    }

    public function edit()
    {
        $user = Auth::user();
        return Inertia::render('EditProfile', [
            'user' => $user
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
