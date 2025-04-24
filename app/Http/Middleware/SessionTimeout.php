<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // If user is authenticated
        if (Auth::check()) {
            // Get the last activity timestamp from the session
            $lastActivity = Session::get('lastActivityTime');
            
            // Set timeout duration in minutes (adjust as needed)
            $sessionTimeout = config('session.lifetime', 30); // Default 30 minutes
            
            // Check if we have a last activity timestamp
            if ($lastActivity) {
                // Calculate time passed since last activity
                $lastActivityTime = Carbon::parse($lastActivity);
                $currentTime = Carbon::now();
                
                // If user exceeded the timeout period, log them out
                if ($currentTime->diffInMinutes($lastActivityTime) > $sessionTimeout) {
                    Auth::logout();
                    Session::flush();
                    
                    // Redirect to login with timeout message
                    return redirect()->route('login')
                        ->with('timeout', 'Your session has expired due to inactivity. Please log in again.');
                }
            }
            
            // Update last activity timestamp for current request
            Session::put('lastActivityTime', Carbon::now());
        }
        
        return $next($request);
    }
} 