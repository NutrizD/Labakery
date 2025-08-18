<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Anda harus login terlebih dahulu.');
        }

        $user = Auth::user();
        
        // Only super admin can access user management
        if (!$user->canManageUsers()) {
            return redirect()->back()->with('error', 'Hanya Super Admin yang dapat mengakses fitur ini.');
        }

        return $next($request);
    }
}
