<?php

namespace App\Http\Middleware;

use Closure;

class BusinessUsers
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
        if(auth()->user()->isIdeaProcessor())
        {
            return $next($request);
        }
        else
        {
            return redirect("/");
        }
    }
}
