<?php

namespace App\Http\Middleware;

use Closure;

class PasswordExpired
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
        if(auth()->user()->isExpired())
        {
            return redirect(route("users.change-password-form"));
        }
        else
        {
            return $next($request);
        }
    }
}
