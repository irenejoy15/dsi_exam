<?php $user_auth = Auth::user(); ?>
@extends('layouts.main')
@section('css')
    <style>
        td,th{
            text-align: center;
            vertical-align: middle;
        }
    </style>
@endsection
@section('content')
{!! html()->modelForm(null, null)->class('form')->id('update_qty')->attribute('action',route('customer.products.update'))->attribute('method','POST')->open() !!}
{!! html()->closeModelForm() !!}

{!! html()->modelForm(null, null)->class('form')->id('remove_from_cart')->attribute('action',route('customer.products.remove-from-cart'))->attribute('method','POST')->open() !!}
{!! html()->closeModelForm() !!}

{!! html()->modelForm(null, null)->class('form')->id('checkout')->attribute('action',route('customer.products.checkout'))->attribute('method','POST')->open() !!}
{!! html()->closeModelForm() !!}
<div class="container mt-4" style="background-color: white; padding: 20px; border-radius: 5px;">
    <div class="row">
        <div class="col-sm-9 col-12">
            <h1>CHECKOUT</h1>
            <hr>
            @include('includes.form_error')
            <div class="container-fluid">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($carts as $cart)
                        <tr>
                            <td><img src="{{ asset('storage/uploads/products/' . $cart->product->image) }}" alt="{{ $cart->product->name }}" width="50"></td>
                            <td>{{ $cart->product->name }}</td>
                            <td>{{ $cart->qty }}</td>
                            <td>P {{ $cart->product->price }}</td>
                            <td>P {{ $cart->qty * $cart->product->price }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateQtyModal" onclick="changeQty('{{ $cart->id }}', '{{ $cart->qty }}', '{{ $cart->product->name }}')">Change</button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#removeFromCartModal" onclick="deleteCart('{{ $cart->id }}')">Remove</button>
                            </td>
                        </tr>
                        @endforeach
                        <tr class="text-right">
                            <td colspan="4" class="text-end"><strong>Total Amount:</strong></td>
                            <td><strong>P {{ number_format($carts->sum(function($cart) { return $cart->qty * $cart->product->price; }), 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-3 col-12">
            <h1 class="text-center">Summary</h1>
            <hr>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" value="{{ $user_auth->name }}" readonly>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" value="{{ $user_auth->email }}" readonly>
            </div>
            <div class="mb-3">
                <label for="shipping_address" class="form-label">Shipping Address</label>
                {{ html()->text('shipping_address',$user_auth->address)->class('form-control')->id('shipping_address')->attribute('style','font-weight:bold;')->attribute('form','checkout') }}
            </div>
            <div class="mb-3">
                <label for="contact_number" class="form-label">Contact Number</label>
                {{ html()->text('contact_number',$user_auth->phone)->class('form-control')->id('contact_number')->attribute('style','font-weight:bold;')->attribute('form','checkout') }}
            </div>
            <p class="text-center"><strong>Total Amount:</strong> P {{ number_format($carts->sum(function($cart) { return $cart->qty * $cart->product->price; }), 2) }}</p>
            {{ html()->submit('Checkout')->class('btn btn-success w-100 mt-2')->attribute('form','checkout') }}
        </div>
    </div>
</div>


@endsection

@section('modals')
{{-- MODAL UPDATE QTY --}}
<div class="modal fade" id="updateQtyModal" tabindex="-1" aria-labelledby="updateQtyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateQtyModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="cart_id" id="cart_id" form="update_qty">
                <div class="mb-3">
                    {{ html()->label('Quantity')->attribute('style','font-weight:bold;')->attribute('for','qty') }}
                    {{ html()->number('qty')->class('form-control')->id('quantity')->attribute('min',1)->attribute('style','font-weight:bold;')->attribute('form','update_qty') }}
                </div>
                {{ html()->submit('Update')->class('btn btn-primary')->attribute('form','update_qty') }}

            </div>
        </div>
    </div>
</div>
{{-- MODAL UPDATE QTY --}}    

{{-- MODAL REMOVE FROM CART --}}
<div class="modal fade" id="removeFromCartModal" tabindex="-1" aria-labelledby="removeFromCartModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeFromCartModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="cart_id" id="remove_cart_id" form="remove_from_cart">
                <p class="text-center" style="fonbt-size:11px; font-weight:bold;">Are you sure you want to remove this item from your cart?</p>   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                {{ html()->submit('Remove')->class('btn btn-danger')->attribute('form','remove_from_cart') }}
            </div>
        </div>
    </div>
</div>
{{-- MODAL REMOVE FROM CART --}}
@endsection

@section('js')
    <script>
        function changeQty(cartId, currentQty, productName) {
            document.getElementById('cart_id').value = cartId;
            document.getElementById('quantity').value = currentQty;
            document.getElementById('updateQtyModalLabel').innerText = 'Update Quantity for ' + productName;
        }

        function deleteCart(cartId) {
            document.getElementById('remove_cart_id').value = cartId;
            document.getElementById('removeFromCartModalLabel').innerText = 'Remove Item from Cart';

        }
    </script>
@endsection