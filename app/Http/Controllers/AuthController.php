<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'nip' => 'required',
            'bagian' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nip' => $request->nip,
            'bagian' => $request->bagian,
            'role' => 'user'
        ]);

        return redirect('/login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            // 🔥 AUTO DETECT PIMPINAN
            if ($user->email === 'pimpinan@gmail.com') {
                $user->role = 'pimpinan';
                $user->save();

                return redirect('/pimpinan/dashboard');
            }

            return redirect('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah'
        ]);
    }
}