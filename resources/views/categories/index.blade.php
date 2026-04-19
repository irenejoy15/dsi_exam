@extends('layouts.main')

    @section('content')
    {!! html()->modelForm(null, null)->class('form')->id('search')->attribute('action',route('categories.index'))->attribute('method','GET')->open() !!}
    {!! html()->closeModelForm() !!}

    {!! html()->modelForm(null, null)->class('form')->id('store')->attribute('action',route('categories.store'))->attribute('method','POST')->open() !!}
    {!! html()->closeModelForm() !!}

    {!! html()->modelForm(null, null)->class('form')->id('update')->attribute('action',route('categories.update'))->attribute('method','POST')->open() !!}
    {!! html()->closeModelForm() !!}

    {!! html()->modelForm(null, null)->class('form')->id('delete')->attribute('action',route('categories.destroy', ['id' => 'delete_id']))->attribute('method','POST')->open() !!}
    {!! html()->closeModelForm() !!}

    <div class="container mt-4" style="background-color: white; padding: 20px; border-radius: 5px;">
        <div class="row">
            <div class="col-12">
                <h1>Categories</h1>
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
                            <button class="btn btn-primary mt-2 mt-xl-0" data-bs-toggle="modal" data-bs-target="#modalCreate">CREATE CATEGORY</button> 
                        </div>
                    </div>
                </div>
                
                <div class="container-fluid">
                    <div class="row">
                        <table class="table table-bordered mt-2 px-4" style="background-color: white;">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->description }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="edit('{!! $category->id !!}', '{!! $category->name !!}', '{!! $category->description !!}')" data-bs-toggle="modal" data-bs-target="#modalEdit">Edit</button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteCategory('{!! $category->id !!}')" data-bs-toggle="modal" data-bs-target="#modalDelete">Delete</button>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('modals')
{{-- MODAL CREATE CATEGORY --}}
    <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Create Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {{ html()->label('CATEGORY NAME:')->attribute('style','font-weight:bold;')->attribute('for','name') }}
                        {{ html()->text('name')->class('form-control')->id('name')->attribute('style','font-weight:bold;')->attribute('form','store') }}
                    </div>
                    <div class="form-group mt-3">
                        {{ html()->label('DESCRIPTION:')->attribute('style','font-weight:bold;')->attribute('for','description') }}
                        {{ html()->textarea('description')->class('form-control')->id('description')->attribute('style','font-weight:bold;')->attribute('form','store') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">CANCEL</button>
                    {{ html()->submit('CREATE')->class('btn btn-outline-success')->attribute('form','store')}}
                </div>
            </div>
        </div>
    </div>
{{--  MODAL CREATE CATEGORY --}}

{{-- MODAL EDIT CATEGORY --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {{ html()->hidden('update_id')->attribute('form','update')->attribute('id','update_id') }}
                        {{ html()->label('CATEGORY NAME:')->attribute('style','font-weight:bold;')->attribute('for','update_name') }}
                        {{ html()->text('update_name')->class('form-control')->id('update_name')->attribute('style','font-weight:bold;')->attribute('form','update') }}
                    </div>
                    <div class="form-group mt-3">
                        {{ html()->label('DESCRIPTION:')->attribute('style','font-weight:bold;')->attribute('for','update_description') }}
                        {{ html()->textarea('update_description')->class('form-control')->id('update_description')->attribute('style','font-weight:bold;')->attribute('form','update') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">CANCEL</button>
                     {{ html()->submit('UPDATE')->class('btn btn-outline-success')->attribute('form','update')}}
                </div>
            </div>
        </div>
    </div>
{{-- END MODAL EDIT CATEGORY --}}

{{-- MODAL DELETE CATEGORY --}}
    <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Delete Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this category?</p>
                    {{ html()->text('delete_id')->attribute('form','delete')->attribute('id','delete_id') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">CANCEL</button>
                     {{ html()->submit('DELETE')->class('btn btn-outline-danger')->attribute('form','delete')}}
                </div>
            </div>
        </div>
    </div>
{{-- END MODAL DELETE CATEGORY --}}
@endsection

@section('js')
    <script>
        function edit(id, name, description) {
            document.getElementById('update_id').value = id;
            document.getElementById('update_name').value = name;
            document.getElementById('update_description').value = description;
        }

        function deleteCategory(id) {
            document.getElementById('delete_id').value = id;
        }
    </script>
@endsection