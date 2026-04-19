<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Auth;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
class CheckoutController extends Controller
{
    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())->with('product')->get();

        return view('checkout.index');
    }

    public function checkout(Request $request)
    {
        $carts = Cart::where('user_id', Auth::id())->with('product')->get();

        // Process the checkout logic here (e.g., create an order, reduce stock, etc.)
        $data_header = [
            'user_id' => Auth::id(),
            'status' => 'pending',
            'order_date' => Carbon::now(),
            'payment_method' => 'cash_on_delivery',
            'shipping_address' => $request->input('shipping_address'),
            'contact_number' => $request->input('contact_number'),
            'total_amount' => $carts->sum(function($cart) {
                return $cart->qty * $cart->product->price;
            }),
        ];
        $order = Order::create($data_header);
        foreach($carts as $cart):
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'quantity' => $cart->qty,
                'price' => $cart->product->price,
            ]);
        endforeach;

        // Clear the cart after checkout
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('customer.products.index')->with('success', 'Checkout successful!');
    }
}
