<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthKey
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
        $token = $request->header('APP_TOKEN');
        if ($token != config('key.app_key')) {
            return response()->json(['message' => 'You are not authorized to access this content'], 401);
        }
        return $next($request);
    }
}
