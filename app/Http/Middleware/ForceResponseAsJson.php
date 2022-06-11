<?php

namespace App\Http\Middleware;

use Closure;

class ForceResponseAsJson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $request->headers->set('Accept', 'application/json');
        $request->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
}
