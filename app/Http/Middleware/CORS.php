<?php

namespace App\Http\Middleware;

use Closure;

class CORS
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
        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Access-Control-Allow-Origin,Access-Control-Allow-Headers,Content-Type'
        ];

        $response = $next($request);
        foreach($headers as $key => $value) {
            $response->header($key, $value);
        }
        return $response;
    }
}
