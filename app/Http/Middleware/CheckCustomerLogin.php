<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCustomerLogin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('customer_id') || $request->session()->get('customer_id') == 0) {
            return redirect('/');
        }

        return $next($request);
    }
}