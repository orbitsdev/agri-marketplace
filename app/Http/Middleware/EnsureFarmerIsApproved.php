<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Farmer;
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


        if (!$user || !$user->isFarmer() || !$user->farmer) {
            abort(403, 'Access denied. Only farmers can access this resource.');
        }

      
        if ($user->farmer->status !== Farmer::STATUS_APPROVED) {
            return redirect()->route('farmer.waiting-for-approval');
        }
        return $next($request);
    }
}
