<?php

namespace App\Http\Middleware;

use Closure;

class Linguagem
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

        // $lang = \Auth::user()->lang;

        // //dd($lang);

        // \App::setLocale($lang);

        return $next($request);
    }
}
