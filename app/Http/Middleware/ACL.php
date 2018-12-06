<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

class ACL
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
        if(!Gate::allows('has-access', $request->route()->getName())) {
            return response()->json(['error' => 'Not authorized.'], 403);
        }
        return $next($request);
    }
}
