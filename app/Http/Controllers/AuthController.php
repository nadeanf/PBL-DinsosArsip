<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function register(Request $request)
{
    $request->merge([
        'email' => strtolower($request->email)
    ]);

    $request->validate([
        'name' => 'required',
        'email' => [
                        'required',
                        'email',
                        'unique:users',
                        'regex:/^[a-z0-9._%+-]+@gmail\.com$/'
                    ],
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
    $request->merge([
        'email' => strtolower($request->email)
    ]);

  //      $response = Http::asForm()->post(
    //'https://challenges.cloudflare.com/turnstile/v0/siteverify',
    //[
    //    'secret' => env('TURNSTILE_SECRET_KEY'),
      //  'response' => $request->input('cf-turnstile-response'),
        //'remoteip' => $request->ip(),
   // ]
//);
//if (! $response->json('success')) {
  //  return back()->withErrors([
    //    'email' => 'Captcha gagal, coba lagi.'
    //]);
//}
       // if (! $response->json('success')) {
         //   return back()->withErrors([
           //     'email' => 'Captcha gagal, coba lagi.'
            //]);
            //}

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {

        $user = Auth::user();

        // CEK USER AKTIF / NONAKTIF
        if (!$user->is_active) {

            Auth::logout();

            return back()->withErrors([
                'email' => 'Akun anda telah dinonaktifkan oleh Super Admin'
            ]);
        }

            // REDIRECT BERDASARKAN ROLE
            $role = $user->role;
            
            if ($role === 'superadmin') {
                return redirect('/super-admin/dashboard');
            } elseif ($role === 'pimpinan') {
                return redirect('/pimpinan/dashboard');
            } elseif ($role === 'admin') {
                return redirect('/admin/dashboard');
            }

        // DEFAULT USER
        return redirect('/dashboard');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah'
    ]);
}
}