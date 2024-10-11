<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LangueMiddleware
{
    /**
     * Summary of handle
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
{
    $defaultLocale = config('app.locale');
    $value = is_string($defaultLocale) ? $defaultLocale : 'default_locale';

    $cookieLocale = $request->cookie('locale', $value);
    $locale = is_string($cookieLocale) ? $cookieLocale : $value;

    App::setLocale($locale);

    return $next($request);
}


}
