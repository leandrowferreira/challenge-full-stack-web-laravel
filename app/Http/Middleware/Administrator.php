<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Administrator
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
        if (!$request->user()->tokenCan('admin')) {
            //403: Forbidden
            return response()->json(['message' => 'The client does not have access rights'], 403);
        }

        return $next($request);
    }
}
