<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
class CustomerOrderController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        if($month && $year):
            $orders = Order::where('user_id', auth()->id())
                ->whereMonth('order_date', $month)
                ->whereYear('order_date', $year)
                ->orderBy('order_date', 'desc')
                ->paginate(5);
        elseif($month):
            $orders = Order::where('user_id', auth()->id())
                ->whereMonth('order_date', $month)
                ->orderBy('order_date', 'desc')
                ->paginate(5);
        elseif($year):
            $orders = Order::where('user_id', auth()->id())
                ->whereYear('order_date', $year)
                ->orderBy('order_date', 'desc')
                ->paginate(5);
        else:
            $orders = Order::where('user_id', auth()->id())->orderBy('order_date', 'desc')->paginate(5);
        endif;
        return view('customerorders.index', compact('orders','month','year'));
    }
}
