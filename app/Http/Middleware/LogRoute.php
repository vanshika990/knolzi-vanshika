<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiResponseTimes;

class LogRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        return $next($request);
    }

    public function terminate($request, $response) {
        if (defined('LARAVEL_START') and $request instanceof Request) {
            ApiResponseTimes::create([
                'api_name' => $request->getRequestUri(),
                'method' => $request->getMethod(),
                'total_time' =>  microtime(true) - LARAVEL_START,
            ]);
        }
    }
}
