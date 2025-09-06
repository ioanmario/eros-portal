<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware to switch roles for authenticated users.
 *
 * This allows an admin to temporarily "view as user" or return back to admin mode.
 */
class RoleSwitcher
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
        $user = Auth::user();

        // ✅ Ensure we have an authenticated user
        if ($user) {
            /**
             * @var \App\Models\User $user
             *
             * @method bool hasRole(string|array $roles)
             * @method $this syncRoles(...$roles)
             */

            // If "view_as_user" flag is set in session, downgrade to "user" role
            if (session('view_as_user', false)) {
                if ($user->hasRole('admin')) {
                    $user->syncRoles(['user']);
                }
            } else {
                // Otherwise, make sure admin keeps "admin" role
                if (! $user->hasRole('admin')) {
                    $user->syncRoles(['admin']);
                }
            }
        }

        return $next($request);
    }
}
