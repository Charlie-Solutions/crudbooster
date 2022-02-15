<?php

namespace charlie\crudbooster\middlewares;

use Closure;
use charlie\crudbooster\helpers\CRUDBooster;

class CBAuthAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {


        CRUDBooster::authAPI();

        return $next($request);
    }
}
