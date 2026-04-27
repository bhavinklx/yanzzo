<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceWWW
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->header('host');

        // Only apply in production or if not on localhost/IP
        if (!app()->environment('local') && !str_starts_with($host, 'www.') && !filter_var($host, FILTER_VALIDATE_IP)) {
            return redirect()->to($request->getScheme() . '://www.' . $host . $request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
