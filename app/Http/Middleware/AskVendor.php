<?php

namespace App\Http\Middleware;

use App\Models\CompressImage;
use Closure;
use Illuminate\Support\Facades\Auth;

class AskVendor
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
        $config = CompressImage::where('path', 'askVendor')
            ->first();

        if (isset($config)) {
            Auth::logout();
        }

        return $next($request);
    }
}
