<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfFarmerApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = Auth::user();

        // Check if the user is a farmer
        if ($user && $user->isFarmer()) {
            // If the farmer is approved, redirect to the dashboard
            if ($user->farmer->is_approved) {
                return redirect()->route('dashboard');
            }
        }
        return $next($request);
    }
}
