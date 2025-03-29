<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsCustomer
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
        // if (!Auth::check() || Auth::user()->role !== 'customer') {
        //     return redirect('/')->with('error', 'You must be logged in as a customer to access this area.');
        // }

        return $next($request);
    }
}