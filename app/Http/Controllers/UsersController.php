<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index()
    {
        return view('users.auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'age' => 'required|integer',
            'province' => 'required|string',
            'city' => 'required|string',
            'kelurahan' => 'required|string',
            'kecamatan' => 'required|string',
            'gender' => 'required'
        ]);

        // Create the user
        $user = User::create([
            'age' => $request->input('age'),
            'province' => $request->input('province'),
            'city' => $request->input('city'),
            'kelurahan' => $request->input('kelurahan'),
            'kecamatan' => $request->input('kecamatan'),
            'gender' => $request->input('gender'),
            'role' => 'user',
        ]);
        session(['user_id' => $user->id]);


        return redirect('/straus-survei')->with('success', 'User created successfully');
    }

    public function auth()
    {
        return view('users.auth.login');
    }
    public function login(Request $request)
    {
        // Validasi input form login
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Mencari user berdasarkan username
        $user = User::where('username', $request->username)->first();

        // Jika user ditemukan dan password cocok
        if ($user && Hash::check($request->password, $user->password)) {
            // Login pengguna
            Auth::login($user);

            // Jika user adalah admin, redirect ke dashboard
            if ($user->role === 'admin') {
                return redirect()->route('dashboard');
            } else {
                Auth::logout();
                return redirect()->back()->with('error', 'You do not have access to this page.');
            }
        } else {
            // Jika username atau password salah
            return redirect()->back()->with('error', 'Invalid username or password.');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}
