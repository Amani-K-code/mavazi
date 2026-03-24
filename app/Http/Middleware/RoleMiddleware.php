<?php

namespace App\Http\Middleware;

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
     * @param string ...$roles <-- The required role for access -->
     */
    public function handle(Request $request, Closure $next, ...$roles): Response{
        // Check if user is logged in
        if(!Auth::check()) {
            return redirect('/login');
        }
        
        $user = Auth::user();

        if(!$user->is_active){
            $name = $user->name;
            Auth::logout();
            return redirect('/login')->with('error', "Sorry $name, your account is currently deactivated. Please contact support.");
        }

        if(in_array($user->role, $roles)) {
            return $next($request);
        }

        // If unauthorized they are redirected to their specific landing page
        return match($user->role){
            'Admin' => redirect()->route('admin.dashboard')->with('error','Unauthorized area.'),
            'Storekeeper' => redirect()->route('storekeeper.dashboard')->with('error', 'Unauthorized area.'),
            default       => redirect()->route('cashier.dashboard')->with('error', 'Unauthorized area.'),
        };
        
    }

}
