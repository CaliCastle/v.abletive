<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfNotATutor
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
        if ($request->user()->isTutor() || $request->user()->isManager()) {
            return $next($request);
        }
        return redirect('/');
    }
}
