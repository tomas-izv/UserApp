<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response

    // Handles non verified Users

    {
        $user = $request->user();

        if($user === null ) {
            return redirect()->route('login');
        }

        if(!$user->hasVerifiedEmail()){
             return redirect()->route('verify');
        }

        return $next($request);
    }
}
