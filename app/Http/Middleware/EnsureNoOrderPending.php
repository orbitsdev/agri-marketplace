<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureNoOrderPending
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        $user = Auth::user();
    
        if($user->role == User::BUYER && $user->orders()->byStatus(Order::PROCESSING)->exists()){
            return redirect()->route('place.order',['name'=> $user->fullNameSlug()]);
        }
        return $next($request);
    }
}
