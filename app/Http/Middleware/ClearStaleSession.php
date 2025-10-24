<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ClearStaleSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if session has been inactive for too long or is invalid
        if (Auth::check() && Session::has('last_activity')) {
            $lastActivity = Session::get('last_activity');
            $sessionLifetime = config('session.lifetime') * 60; // Convert to seconds
            
            // If session is older than lifetime, logout
            if (time() - $lastActivity > $sessionLifetime) {
                Auth::logout();
                Session::flush();
                return redirect('/')->with('info', 'Sesi Anda telah berakhir. Silakan login kembali.');
            }
        }
        
        // Update last activity time
        Session::put('last_activity', time());
        
        return $next($request);
    }
}
