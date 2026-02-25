<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCustomerLogin
{
    public function handle(Request $request, Closure $next)
    {
        // Check if 'customer_id' exists in session and is not 0
        if (!$request->session()->has('customer_id') || $request->session()->get('customer_id') == 0) {
            // Not logged in as customer
            return redirect('/');
        }

        return $next($request);
    }
}