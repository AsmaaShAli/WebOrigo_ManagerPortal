<?php

namespace App\Http\Middleware;

use App\Models\Device;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next) {
        $device = Device::where('id',$request->deviceId)->first();
        if ($request->header('X-API-KEY') !== ($device->remember_token ?? null )) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
