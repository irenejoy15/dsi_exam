<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        if($month && $year):
            $orders = Order::whereMonth('order_date', $month)
                ->whereYear('order_date', $year)
                ->orderBy('order_date', 'desc')
                ->paginate(5);
        elseif($month):
            $orders = Order::whereMonth('order_date', $month)
                ->orderBy('order_date', 'desc')
                ->paginate(5);
        elseif($year):
            $orders = Order::whereYear('order_date', $year)
                ->orderBy('order_date', 'desc')
                ->paginate(5);
        else:
            $orders = Order::orderBy('order_date', 'desc')->paginate(5);
        endif;
        return view('adminorders.index', compact('orders','month','year'));
    }

    public function update(Request $request)
    {
        $id = $request->input('order_id');
        $order = Order::findOrFail($id);
        $status = $request->input('status');
        
        if(in_array($status, ['pending', 'accepted', 'completed', 'cancelled'])):
            $order->status = $status;
            $order->save();
            return redirect()->back()->with('success', 'Order status updated successfully.');
        else:
            return redirect()->back()->with('danger', 'Invalid status value.');
        endif;
    }

    public function create(Request $request)
    {
        //
        $id = $request->input('order_id');
        $order = Order::findOrFail($id);
        $status = $request->input('status');
        echo $status;
    }
}
