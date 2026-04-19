<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Http\Resources\ProductResource;
use Auth;
class CustomerProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter');
        $products = Product::query();
        if(empty($filter)||$filter == 'all'):
            $filter = '';
        else:
            $products->where('category_id', $filter);
        endif;
        if($search):
            $products->where('name', 'like', "%{$search}%");
        endif;
        $products = $products->paginate(12)->withQueryString();
        $categories = Category::select('name','id')->get();
        return view('customers.index', compact('products', 'search', 'filter', 'categories'));
    }   

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return new ProductResource($product);
    }

    public function addToCart(Request $request)
    {
        $user = Auth::user();
        $product_id = $request->input('product_id');
        $qty = $request->input('qty');
        $product = Product::findOrFail($product_id);
        if($qty > $product->stock):
            return redirect()->back()->with('danger', 'Requested quantity exceeds available stock.');
        endif;

        $cartItem = Cart::where('user_id', $user->id)->where('product_id', $product_id)->first();
        if ($cartItem):
            $cartItem->qty += $qty;
            $cartItem->save();
        else:
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product_id,
                'qty' => $qty,
            ]);
        endif;
        // MINUS STOCK
        $product->stock -= $qty;
        $product->save();
        return redirect()->back()->with('success', 'Product added to cart successfully.');

    }

    public function updateQty(Request $request)
    {
        $cart_id = $request->input('cart_id');
        $qty = $request->input('qty');
        $cartItem = Cart::findOrFail($cart_id);
        $product = Product::findOrFail($cartItem->product_id);
        if($qty > $product->stock + $cartItem->qty):
            return redirect()->back()->with('danger', 'Requested quantity exceeds available stock.');
        endif;
        // UPDATE STOCK
        $product->stock += ($cartItem->qty - $qty);
        $product->save();
        // UPDATE CART
        $cartItem->qty = $qty;
        $cartItem->save();
        return redirect()->back()->with('success', 'Cart updated successfully.');
    }

    public function destroy(Request $request)
    {
        $cart_id = $request->input('cart_id');
        $cartItem = Cart::findOrFail($cart_id);
        $product = Product::findOrFail($cartItem->product_id);
        // RESTORE STOCK
        $product->stock += $cartItem->qty;
        $product->save();
        // DELETE CART ITEM
        $cartItem->delete();
        return redirect()->back()->with('success', 'Item removed from cart successfully.');
    }
}
