<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->get('isAdmin')) {
            return \redirect(url('admin'));
        } else {
            return back();
        }
        return $next($request);
    }
}
