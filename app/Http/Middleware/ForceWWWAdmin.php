<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceWWWAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (
            $request->is('admin*') &&
            ! str_starts_with($request->getHost(), 'www.')
        ) {
            return redirect()->to(
                $request->getScheme().'://www.'.$request->getHost().$request->getRequestUri(),
                301
            );
        }

        return $next($request);
    }
}
