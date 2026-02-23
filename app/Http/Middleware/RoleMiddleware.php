<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        $user = auth()->user();

        // 1. If the user is a Super Admin, let them pass everything
        if ($user->role === UserRole::SUPER_ADMIN) {
            return $next($request);
        }

        // 2. If the route requires 'admin', check if user has admin privileges
        if ($role === 'admin' && $user->isAdmin()) {
            return $next($request);
        }

        // 3. Otherwise, check for an exact role match
        if ($user->role->value === $role) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
