<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureFarmerIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   

        $user = Auth::user();

        // Check if the user is a farmer and is not approved
        if ($user && $user->isFarmer() && !$user->farmer->is_approved) {
            // Redirect to a page with the remarks (if any)
            return redirect()->route('farmer.waiting-for-approval');
        }
        return $next($request);
    }
}
