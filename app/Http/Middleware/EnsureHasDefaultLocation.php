<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasDefaultLocation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = Auth::user();

        // redirect if no location
        if($user->role == User::BUYER && !$user->locations()->hasDefault()->exists()){

            return redirect()->route('address.index', ['name' => $user->fullNameSlug()])
            ->with('message', 'No default location found. Please set a default location or create a new one if none exists. before placing item to cart or placing order');
        
        }
        return $next($request);
    }
}
