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
    {!! html()->modelForm(null, null)->class('form')->id('search')->attribute('action',route('products.index'))->attribute('method','GET')->open() !!}
    {!! html()->closeModelForm() !!}

    {!! html()->modelForm(null, null)->class('form')->id('store')->attribute('action',route('products.store'))->attribute('method','POST')->acceptsFiles()->open() !!}
    {!! html()->closeModelForm() !!}

    {!! html()->modelForm(null, null)->class('form')->id('update')->attribute('action',route('products.update'))->attribute('method','POST')->acceptsFiles()->open() !!}
    {!! html()->closeModelForm() !!}

    
    {!! html()->modelForm(null, null)->class('form')->id('delete')->attribute('action',route('products.destroy'))->attribute('method','POST')->open() !!}
    {!! html()->closeModelForm() !!}
   

    <div class="container mt-4" style="background-color: white; padding: 20px; border-radius: 5px;">
        <div class="row">
            <div class="col-12">
                <h1>Products</h1>
                <hr>
                @include('includes.form_error')
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-8">
                            {!! html()->text('search',$search)->class('form-control')->placeholder('Search...')->attribute('form','search') !!}
                        </div>
                        <div class="col-sm-2">
                            {{ html()->submit('SEARCH')->class('btn btn-outline-success')->attribute('style','width:100%;')->attribute('form','search')}}
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-primary mt-2 mt-xl-0" data-bs-toggle="modal" data-bs-target="#modalCreate">CREATE PRODUCT</button> 
                        </div>
                    </div>
                </div>
                
                <div class="container-fluid">
                    <div class="row">
                        <table class="table table-bordered mt-2 px-4" style="background-color: white;">
                            <thead>
                                <tr>
                                    <th>Images</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td class="text-center">
                                            <img src="{{ asset('storage/uploads/products/' . $product->image) }}" alt="{{ $product->name }}" width="150">
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->description }}</td>
                                        <td>{{ $product->price }}</td>
                                        <td>{{ $product->stock }}</td>
                                        <td>{!! $product->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' !!}</td>
                                        <td>

                                            <button class="btn btn-sm btn-warning" onclick="edit('{!! $product->id !!}', '{!! $product->name !!}', '{!! $product->description !!}','{!! $product->price !!}','{!! $product->stock !!}','{!! $product->category_id !!}' , '{!! $product->is_active !!}')" data-bs-toggle="modal" data-bs-target="#modalEdit">Edit</button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteProduct('{!! $product->id !!}')" data-bs-toggle="modal" data-bs-target="#modalDelete">Delete</button>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('modals')
{{-- MODAL CREATE PRODUCT --}}
    <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Create Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ html()->label('PRODUCT NAME:')->attribute('style','font-weight:bold;')->attribute('for','name') }}
                                    {{ html()->text('name')->class('form-control')->id('name')->attribute('style','font-weight:bold;')->attribute('form','store') }}
                                </div>
                                <div class="form-group mt-3">
                                    {{ html()->label('DESCRIPTION:')->attribute('style','font-weight:bold;')->attribute('for','description') }}
                                    {{ html()->textarea('description')->class('form-control')->id('description')->attribute('style','font-weight:bold;')->attribute('form','store') }}
                                </div>
                                <div class="form-group mt-3">
                                    {{ html()->label('PRICE:')->attribute('style','font-weight:bold;')->attribute('for','price') }}
                                    {{ html()->number('price')->class('form-control')->id('price')->attribute('min', '0')->attribute('style','font-weight:bold;')->attribute('form','store') }}
                                </div>
                                <div class="form-group mt-3">
                                    {{ html()->label('STOCK:')->attribute('style','font-weight:bold;')->attribute('for','stock') }}
                                    {{ html()->number('stock')->class('form-control')->id('stock')->attribute('min', '0')->attribute('max', '100')->attribute('style','font-weight:bold;')->attribute('form','store') }}
                                </div>
                                <div class="form-group mt-3">
                                    {{ html()->label('CATEGORY:')->attribute('style','font-weight:bold;')->attribute('for','category_id') }}
                                    {{ html()->select('category_id', $categories->pluck('name', 'id'))->class('form-control')->id('category_id')->attribute('style','font-weight:bold;')->attribute('form','store') }}
                                </div>
                                <div class="form-group mt-3">
                                    {{ html()->label('PRODUCT IMAGE:')->attribute('style','font-weight:bold;')->attribute('for','image') }}
                                    {{ html()->file('image')->class('form-control')->id('image')->attribute('style','font-weight:bold;')->attribute('form','store') }}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <table class="table">
                                    <thead class="text-center">
                                        <th>-</th>
                                        <th>YES</th>
                                        <th>NO</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" style="font-weight: bold;">IS ACTIVE?</td>
                                            <td class="text-center">
                                                <input type="radio" name="is_active" id="is_active_yes" value="1" form="store">
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="is_active" id="is_active_no" value="0" form="store" checked>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">CANCEL</button>
                    {{ html()->submit('CREATE')->class('btn btn-outline-success')->attribute('form','store')}}
                </div>
            </div>
        </div>
    </div>
{{--  MODAL CREATE PRODUCT --}}


{{-- MODAL EDIT PRODUCT --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ html()->hidden('update_id')->attribute('form','update')->attribute('id','update_id') }}
                                    {{ html()->label('PRODUCT NAME:')->attribute('style','font-weight:bold;')->attribute('for','update_name') }}
                                    {{ html()->text('update_name')->class('form-control')->id('update_name')->attribute('style','font-weight:bold;')->attribute('form','update') }}
                                </div>
                                <div class="form-group mt-3">
                                    {{ html()->label('DESCRIPTION:')->attribute('style','font-weight:bold;')->attribute('for','update_description') }}
                                    {{ html()->textarea('update_description')->class('form-control')->id('update_description')->attribute('style','font-weight:bold;')->attribute('form','update') }}
                                </div>
                                <div class="form-group mt-3">
                                    {{ html()->label('PRICE:')->attribute('style','font-weight:bold;')->attribute('for','update_price') }}
                                    {{ html()->number('update_price')->class('form-control')->id('update_price')->attribute('min', '0')->attribute('style','font-weight:bold;')->attribute('form','update') }}
                                </div>
                                <div class="form-group mt-3">
                                    {{ html()->label('STOCK:')->attribute('style','font-weight:bold;')->attribute('for','update_stock') }}
                                    {{ html()->number('update_stock')->class('form-control')->id('update_stock')->attribute('min', '0')->attribute('max', '100')->attribute('style','font-weight:bold;')->attribute('form','update') }}
                                </div>
                                <div class="form-group mt-3">
                                    {{ html()->label('CATEGORY:')->attribute('style','font-weight:bold;')->attribute('for','update_category_id') }}
                                    {{ html()->select('update_category_id', $categories->pluck('name', 'id'))->class('form-control')->id('update_category_id')->attribute('style','font-weight:bold;')->attribute('form','update') }}
                                </div>
                                <div class="form-group mt-3">
                                    {{ html()->label('PRODUCT IMAGE:')->attribute('style','font-weight:bold;')->attribute('for','update_image') }}
                                    {{ html()->file('update_image')->class('form-control')->id('update_image')->attribute('style','font-weight:bold;')->attribute('form','update') }}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <table class="table">
                                    <thead class="text-center">
                                        <th>-</th>
                                        <th>YES</th>
                                        <th>NO</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" style="font-weight: bold;">IS ACTIVE?</td>
                                            <td class="text-center">
                                                <input type="radio" name="update_is_active" id="is_active_yes_update" value="1" form="update">
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="is_active" id="is_active_no_update" value="0" form="update" checked>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">CANCEL</button>
                    {{ html()->submit('UPDATE')->class('btn btn-outline-success')->attribute('form','update')}}
                </div>
            </div>
        </div>
    </div>
{{--  MODAL EDIT PRODUCT --}}

{{-- MODAL DELETE PRODUCT --}}
    <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Delete Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center">Are you sure you want to delete this product?</p>
                    {{ html()->hidden('delete_id')->attribute('form','delete')->attribute('id','delete_id') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">CANCEL</button>
                    {{ html()->submit('DELETE')->class('btn btn-outline-danger')->attribute('form','delete')}}
                </div>
            </div>
        </div>
    </div>
{{-- END MODAL DELETE PRODUCT --}}
@endsection

@section('js')
    <script>
        function edit(id, name, description, price, stock, category_id, is_active) {
            document.getElementById('update_id').value = id;
            document.getElementById('update_name').value = name;
            document.getElementById('update_description').value = description;
            document.getElementById('update_price').value = price;
            document.getElementById('update_stock').value = stock;
            document.getElementById('update_category_id').value = category_id;
            if (is_active) {
                document.getElementById('is_active_yes_update').checked = true;
            } else {
                document.getElementById('is_active_no_update').checked = true;
            }
        }

        function deleteProduct(id) {
            document.getElementById('delete_id').value = id;
        }
    </script>
@endsection