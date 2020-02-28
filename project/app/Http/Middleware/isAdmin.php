<?php

namespace App\Http\Middleware;

use Closure;

class isAdmin
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
        if(auth()->check() && $request->user()->status != 2){
            // JIKA BUKAN ADMIN 
            return redirect('/home');
        }
        // JIKA ADMIN
        return $next($request);

    }
}
