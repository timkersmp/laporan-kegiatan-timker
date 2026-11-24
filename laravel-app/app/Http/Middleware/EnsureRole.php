<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Models\Petugas;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $role = $request->session()->get('role');
        if (!$request->session()->has('user_id') || !in_array($role, $roles, true)) {
            return redirect()->route('login')->with('error', 'Silakan masuk dengan akun yang sesuai.');
        }

        $account = $role === 'petugas'
            ? Petugas::find($request->session()->get('user_id'))
            : Admin::find($request->session()->get('user_id'));
        if (!$account || ($role !== 'petugas' && ($account->status ?? 'aktif') !== 'aktif')) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Akun Anda sudah tidak aktif.');
        }

        return $next($request);
    }
}
