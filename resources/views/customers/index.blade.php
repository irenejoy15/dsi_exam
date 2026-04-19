@extends('layouts.main')
    @section('css')
        <style>
            .table th, .table td {
                vertical-align: middle;
                text-align: center;
            }
        </style>
    @endsection
    @section('content')
    {!! html()->modelForm(null, null)->class('form')->id('search')->attribute('action',route('customer.products.index'))->attribute('method','GET')->open() !!}
    {!! html()->closeModelForm() !!}

    {!! html()->modelForm(null, null)->class('form')->id('add-to-cart')->attribute('action',route('customer.products.add-to-cart'))->attribute('method','POST')->open() !!}
    {!! html()->closeModelForm() !!}
    
    <div class="container mt-4" style="background-color: white; padding: 20px; border-radius: 5px;">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Products</h2>
            </div>
        </div>
        <div class="row">
             @include('includes.form_error')

            <div class="col-md-4">
                {{ html()->text('search')->class('form-control')->id('search')->placeholder('Search by name')->attribute('form','search')->value(request('search')) }}
            </div>
            <div class="col-md-4">
                {{ html()->select('filter')->class('form-control')->id('filter')->options(['' => 'All Categories'] + $categories->pluck('name', 'id')->toArray())->attribute('form','search')->value(request('filter')) }}
            </div>
            <div class="col-md-4">
                {{ html()->button('Search')->class('btn btn-primary w-50')->attribute('form','search') }}
            </div>
        </div>
        <hr style="margin: 20px 0;">
        <div class="row">
            @if($products->isEmpty())
                <div class="col-12">
                    <div class="alert alert-info text-center" role="alert">
                        No products found.
                    </div>
                </div>
            @else
                @foreach($products as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                        <div class="card h-100">
                            <img src="{{ asset('storage/uploads/products/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                <h4 class="card-title">{{ $product->name }}</h4>
                                <h5 style="color:rgb(0, 168, 28);">{{ $product->category->name }}</h5>
                                <p class="card-text">Price: ${{ number_format($product->price, 2) }} | Stock: {{ $product->stock }}</p>
                                <hr style="width: 100%; margin: 10px 0;">
                                <div class="container">
                                    <div class="row" style="padding:0px;">
                                        @if($product->stock <= 0)
                                            <div class="col-12">
                                                <button type="button" class="btn btn-outline-secondary w-100" disabled>OUT OF STOCK</button>
                                            </div>
                                        @else
                                            <div class="col-6">
                                                <button type="button" onclick="getToCart({{ $product->id }})" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#modalCart">ADD</button>
                                            </div>
                                            <div class="col-6">
                                                <button type="button" onclick="viewDetails({{ $product->id }})" class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#modalDetails">VIEW</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection

@section('modals')
{{-- ADD TO CART MODAL --}}
<div class="modal fade" id="modalCart" tabindex="-1" aria-labelledby="modalCartLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCartLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add to cart form or details will go here -->
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center">
                            <img id="modalCartImage" src="" alt="">
                        </div>
                        
                        <div class="col-12 mt-3">
                            {{ html()->hidden('product_id')->class('form-control')->id('product_id')->attribute('style','font-weight:bold;')->attribute('form','add-to-cart') }}
                            {{ html()->label('QTY TO ORDER')->attribute('style','font-weight:bold;')->attribute('for','qty') }}
                            {{ html()->number('qty',1)->class('form-control')->id('qty')->attribute('min','1')->attribute('style','font-weight:bold;')->attribute('form','add-to-cart') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                {{ html()->button('Add to Cart')->class('btn btn-primary')->attribute('form','add-to-cart') }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetails" tabindex="-1" aria-labelledby="modalDetailsLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailsLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add to cart form or details will go here -->
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center">
                            <img id="modalDetailsImage" src="" alt="">
                        </div>
                        
                        <div class="col-12 mt-3 text-center">
                            <h3 id="modalDetailsCategory" style="color:rgb(0, 168, 28); "></h3>
                            <hr style="width: 100%; margin: 10px 0;">
                            <h4 id="modalDetailsPrice"></h4>
                            <p id="modalDetailsDescription"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
               
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        function getToCart(productId) {
            fetch(`/customer/products/${productId}`)
                .then(response => response.json())
                .then(data => {
                    // Implement the logic to add the product to the cart
                    document.getElementById('modalCartLabel').innerHTML = data.name+` - Stock: ${data.stock}`;
                    document.getElementById('modalCartImage').src = `${data.image}`;
                    document.getElementById('product_id').value = data.id;
                })
                .catch(error => {
                    console.error('Error fetching product details:', error);
                });
        }

        function viewDetails(productId) {
            fetch(`/customer/products/${productId}`)
                .then(response => response.json())
                .then(data => {
                    // Implement the logic to display product details in the modal
                    document.getElementById('modalDetailsLabel').innerHTML = data.name;
                    document.getElementById('modalDetailsImage').src = `${data.image}`;
                    document.getElementById('modalDetailsCategory').innerHTML = `Category: ${data.category}`;
                    document.getElementById('modalDetailsPrice').innerHTML = `Price: $${data.price}` + ` | Stock: ${data.stock}`;
                    document.getElementById('modalDetailsDescription').innerHTML = `Description: ${data.description}`;
                })
                .catch(error => {
                    console.error('Error fetching product details:', error);
                });

        }
    </script>
@endsection