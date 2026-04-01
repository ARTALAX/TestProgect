<?php

namespace Modules\Order\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, \Closure $next): Response
    {
        $locale = $request->header(key: 'X-Locale') ?? session(key: 'locale') ?? config(key: 'app.locale');
        App::setLocale($locale);

        return $next($request);
    }
}
