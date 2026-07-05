<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        // Skip for superadmin or unauthenticated
        if (!$user || $user->isSuperadmin()) {
            return $next($request);
        }

        // Allow access to subscription expired page and logout
        if ($request->routeIs('subscription.expired') || $request->routeIs('logout')) {
            return $next($request);
        }

        // Check if RT exists and has subscription
        $rt = $user->rt;
        if ($rt) {
            $subscription = $rt->subscription;
            if (!$subscription || \Carbon\Carbon::parse($subscription->end_date)->isPast()) {
                // Subscription is expired
                return redirect()->route('subscription.expired');
            }
        }

        return $next($request);
    }
}
