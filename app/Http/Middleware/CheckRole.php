<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Jika user belum login, redirect ke login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Log untuk debugging
        \Log::info('CheckRole Middleware', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'required_role' => $role,
            'path' => $request->path()
        ]);

        // Simple role check - langsung bandingkan dengan kolom role di database
        if ($user->role !== $role) {
            \Log::warning('Access denied - Role mismatch', [
                'user_role' => $user->role,
                'required_role' => $role
            ]);
            
            return $this->redirectBasedOnRole($user);
        }

        return $next($request);
    }

    /**
     * Redirect user berdasarkan role mereka
     */
    protected function redirectBasedOnRole($user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Akses ditolak. Hanya venue owner yang dapat mengakses halaman tersebut.');
            
            case 'user':
                return redirect()->route('beranda')
                    ->with('error', 'Akses ditolak. Hanya venue owner yang dapat mengakses halaman tersebut.');
            
            case 'owner':
                // Jika sudah owner tapi masih di-redirect, berarti ada masalah lain
                return redirect()->route('venue.dashboard')
                    ->with('error', 'Terjadi kesalahan akses.');
            
            default:
                return redirect()->route('beranda')
                    ->with('error', 'Akses ditolak. Role tidak dikenali.');
        }
    }
}