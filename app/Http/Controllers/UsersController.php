<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
}
