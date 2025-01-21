<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsBuyerOrFarmer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Check if the user is a buyer or farmer
        if ($user && in_array($user->role, [User::BUYER, User::FARMER])) {
            return $next($request);
        }

        // Deny access if the user is neither a buyer nor a farmer
        abort(403, 'Unauthorized');
    }
}
