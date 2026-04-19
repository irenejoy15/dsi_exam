<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Cart;
use Auth;
class GetCart
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check()):
            $carts = Cart::where('user_id', Auth::id())->with('product')->get();
            view()->share('carts', $carts);
        endif;
        return $next($request);
    }
}
