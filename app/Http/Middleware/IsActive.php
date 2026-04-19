<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class IsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::user()->is_active()){
            return $next($request);
        }
        return redirect('/login')->with('danger','YOUR ACCOUNT IS ALREADY INACTIVE');
    }
}
