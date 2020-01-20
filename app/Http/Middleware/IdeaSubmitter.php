<?php

namespace App\Http\Middleware;

use Closure;

class IdeaSubmitter
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
        if(auth()->user()->isSubmitter())
        {
            return $next($request);
        }
        else
        {
            return redirect("/");
        }
    }
}
