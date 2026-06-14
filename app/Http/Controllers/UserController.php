<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (auth()->check() && auth()->user()->role !== 'superadmin') {
                    abort(403);
                }
                return $next($request);
            }),
        ];
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        $user->is_active = !$user->is_active;

        $user->save();

        return back();
    }
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
        'nip' => 'required',
        'bagian' => $request->role === 'user' ? 'required' : 'nullable',
        'role' => 'required',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'nip' => $request->nip,
        'bagian' => $request->role === 'user'
        ? $request->bagian
        : null,
        'role' => $request->role,
        'is_active' => true,
    ]);

    return back()->with('success', 'User berhasil ditambahkan');
}
public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'nip' => 'required',
        'bagian' => $request->role === 'user'
        ? 'required'
        : 'nullable',
        'role' => 'required',
    ]);

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'nip' => $request->nip,
        'bagian' => $request->role === 'user'
        ? $request->bagian
        : null,
        'role' => $request->role,
    ]);

    return back()->with('success', 'User berhasil diupdate');
}
}