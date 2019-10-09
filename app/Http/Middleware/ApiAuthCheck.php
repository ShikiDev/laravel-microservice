<?php

namespace App\Http\Middleware;

use Closure;

class ApiAuthCheck
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
        $auth_key = env('API_AUTH_KEY');
        $bearer_token = $request->bearerToken();
        $encoded_auth_key = base64_encode(trim($auth_key));

        if($bearer_token == null or $encoded_auth_key !== $bearer_token) {
            abort(403, 'Wrong Auth key. Correct it and try again');
        }
        return $next($request);
    }
}
