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
    {!! html()->modelForm(null, null)->class('form')->id('search')->attribute('action',route('users.index'))->attribute('method','GET')->open() !!}
    {!! html()->closeModelForm() !!}

    {!! html()->modelForm(null, null)->class('form')->id('store')->attribute('action',route('users.store'))->attribute('method','POST')->acceptsFiles()->open() !!}
    {!! html()->closeModelForm() !!}

    {!! html()->modelForm(null, null)->class('form')->id('update')->attribute('action',route('users.update'))->attribute('method','POST')->acceptsFiles()->open() !!}
    {!! html()->closeModelForm() !!}
   
    {!! html()->modelForm(null, null)->class('form')->id('delete')->attribute('action',route('users.destroy'))->attribute('method','POST')->open() !!}
    {!! html()->closeModelForm() !!}

    {!! html()->modelForm(null, null)->class('form')->id('security_questions')->attribute('action',route('users.update-security-questions'))->attribute('method','POST')->open() !!}
    {!! html()->closeModelForm() !!}


    <div class="container mt-4" style="background-color: white; padding: 20px; border-radius: 5px;">
        <div class="row">
            <div class="col-12">
                <h1>Users</h1>
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
                            <button class="btn btn-primary mt-2 mt-xl-0" data-bs-toggle="modal" data-bs-target="#modalCreate">CREATE USER</button> 
                        </div>
                    </div>
                </div>
                
                <div class="container-fluid">
                    <div class="row">
                        <table class="table table-bordered mt-2 px-4" style="background-color: white;">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td><img src="{{ $user->photo ? asset('storage/uploads/users/' . $user->photo) : asset('images/default-user.png') }}" alt="User Photo" width="50" height="50" class="rounded-circle"></td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{!! $user->is_admin == 1 ? '<span class="badge bg-success">Admin</span>' : '<span class="badge bg-secondary">Customer</span>' !!}</td>
                                        <td>
                                            <!-- Action buttons (Edit, Delete) can be added here -->
                                            <button class="btn btn-sm btn-warning" onclick="edit('{!! $user->id !!}', '{!! $user->name !!}', '{!! $user->email !!}', '{!! $user->address !!}', '{!! $user->phone !!}', '{!! $user->is_admin !!}', '{!! $user->is_active !!}')" data-bs-toggle="modal" data-bs-target="#modalEditUser">Edit</button>
                                            <button class="btn btn-sm btn-danger" onclick="destroy('{!! $user->id !!}')" data-bs-toggle="modal" data-bs-target="#modalDeleteUser">Delete</button>
                                            <button class="btn btn-sm btn-info" onclick="getSecurityQuestions('{!! $user->id !!}')" data-bs-toggle="modal" data-bs-target="#modalSecurityQuestions" style="color:white;">Security Questions</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('modals')
{{-- MODAL CREATE USERS --}}
    <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Create User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ html()->label('Name:')->attribute('style','font-weight:bold;')->attribute('for','name') }}
                                    {{ html()->text('name')->class('form-control')->id('name')->attribute('style','font-weight:bold;')->attribute('form','store') }}
                                </div>
                                <div class="form-group pt-1">
                                    {{ html()->label('Email:')->attribute('style','font-weight:bold;')->attribute('for','email') }}
                                    {{ html()->email('email')->class('form-control')->id('email')->attribute('style','font-weight:bold;')->attribute('form','store') }}
                                </div>
                                <div class="form-group pt-1">
                                    {{ html()->label('Password:')->attribute('style','font-weight:bold;')->attribute('for','password') }}
                                    {{ html()->password('password')->class('form-control')->id('password')->attribute('style','font-weight:bold;')->attribute('form','store') }}
                                </div>
                                <div class="form-group pt-1">
                                    {{ html()->label('Address:')->attribute('style','font-weight:bold;')->attribute('for','address') }}
                                    {{ html()->text('address')->class('form-control')->id('address')->attribute('style','font-weight:bold;')->attribute('form','store') }}
                                </div>
                                <div class="form-group pt-1">
                                    {{ html()->label('Phone:')->attribute('style','font-weight:bold;')->attribute('for','phone') }}
                                    {{ html()->text('phone')->class('form-control')->id('phone')->attribute('style','font-weight:bold;')->attribute('form','store') }}
                                </div>
                                <div class="form-group pt-1 mt-3">
                                    {{ html()->label('Photo:')->attribute('style','font-weight:bold;')->attribute('for','photo') }}
                                    {{ html()->file('photo')->class('form-control')->id('photo')->attribute('style','font-weight:bold;')->attribute('form','store') }}
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
                                            <td class="text-center" style="font-weight: bold;">IS ADMIN?</td>
                                            <td class="text-center">
                                                <input type="radio" name="is_admin" id="is_admin_yes" value="1" form="store"  checked>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="is_admin" id="is_admin_no" value="0" form="store">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center" style="font-weight: bold;">IS ACTIVE?</td>
                                            <td class="text-center">
                                                <input type="radio" name="is_active" id="is_active_yes" value="1" form="store" checked>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="is_active" id="is_active_no" value="0" form="store">
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
{{--  MODAL CREATE USER --}}
{{-- MODAL EDIT USER --}}
    <div class="modal fade" id="modalEditUser" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{ html()->hidden('id')->id('edit_user_id')->attribute('form','update') }}
                                    
                                    {{ html()->label('Name:')->attribute('style','font-weight:bold;')->attribute('for','name') }}
                                    {{ html()->text('name_update')->class('form-control')->id('name_update')->attribute('style','font-weight:bold;')->attribute('form','update') }}
                                </div>
                                <div class="form-group pt-1">
                                    {{ html()->label('Email:')->attribute('style','font-weight:bold;')->attribute('for','email') }}
                                    {{ html()->email('email_update')->class('form-control')->id('email_update')->attribute('style','font-weight:bold;')->attribute('form','update') }}
                                </div>
                                <div class="form-group pt-1">
                                    {{ html()->label('Password:')->attribute('style','font-weight:bold;')->attribute('for','password') }}
                                    {{ html()->password('password_update')->class('form-control')->id('password_update')->attribute('style','font-weight:bold;')->attribute('form','update') }}
                                </div>
                                <div class="form-group pt-1">
                                    {{ html()->label('Address:')->attribute('style','font-weight:bold;')->attribute('for','address') }}
                                    {{ html()->text('address_update')->class('form-control')->id('address_update')->attribute('style','font-weight:bold;')->attribute('form','update') }}
                                </div>
                                <div class="form-group pt-1">
                                    {{ html()->label('Phone:')->attribute('style','font-weight:bold;')->attribute('for','phone') }}
                                    {{ html()->text('phone_update')->class('form-control')->id('phone_update')->attribute('style','font-weight:bold;')->attribute('form','update') }}
                                </div>
                                <div class="form-group pt-1 mt-3">
                                    {{ html()->label('Photo:')->attribute('style','font-weight:bold;')->attribute('for','photo') }}
                                    {{ html()->file('photo_update')->class('form-control')->id('photo_update')->attribute('style','font-weight:bold;')->attribute('form','update') }}
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
                                            <td class="text-center" style="font-weight: bold;">IS ADMIN?</td>
                                            <td class="text-center">
                                                <input type="radio" name="is_admin_update" id="is_admin_yes_update" value="1" form="update"  checked>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="is_admin_update" id="is_admin_no_update" value="0" form="update">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center" style="font-weight: bold;">IS ACTIVE?</td>
                                            <td class="text-center">
                                                <input type="radio" name="is_active_update" id="is_active_yes_update" value="1" form="update" checked>
                                            </td>
                                            <td class="text-center">
                                                <input type="radio" name="is_active_update" id="is_active_no_update" value="0" form="update">
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
{{-- MODAL EDIT USER --}}
{{-- MODAL DELETE USER --}}
<div class="modal fade" id="modalDeleteUser" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ html()->hidden('id')->id('delete_id')->attribute('form','delete') }}
                Are you sure you want to delete this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">CANCEL</button>
                {{ html()->submit('DELETE')->class('btn btn-outline-danger')->attribute('form','delete')}}
            </div>
        </div>
    </div>
</div>
{{-- MODAL DELETE USER --}}

{{-- MODAL SECURITY QUESTIONS --}}
<div class="modal fade" id="modalSecurityQuestions" tabindex="-1" role="dialog" aria-labelledby="securityQuestionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="securityQuestionsModalLabel">User Security Questions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-center">Security questions and answers will be displayed here.</p>
                {{ html()->hidden('id')->id('security_questions_user_id')->attribute('form','security_questions') }}
                <div class="form-group">
                    {{ html()->label('first_question:')->id('first_question')->attribute('style','font-weight:bold;')->attribute('for','first_question') }}
                    {{ html()->text('first_answer')->class('form-control')->id('first_answer')->attribute('style','font-weight:bold;')->attribute('form','security_questions') }}
                </div>
                <hr>
                <div class="form-group">
                    {{ html()->label('second_question:')->id('second_question')->attribute('style','font-weight:bold;')->attribute('for','second_question') }}
                    {{ html()->text('second_answer')->class('form-control')->id('second_answer')->attribute('style','font-weight:bold;')->attribute('form','security_questions') }}
                </div>
                 <hr>
                <div class="form-group">
                    {{ html()->label('third_question:')->id('third_question')->attribute('style','font-weight:bold;')->attribute('for','third_question') }}
                    {{ html()->text('third_answer')->class('form-control')->id('third_answer')->attribute('style','font-weight:bold;')->attribute('form','security_questions') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">CLOSE</button>
                {{ html()->submit('UPDATE')->class('btn btn-outline-success')->attribute('form','security_questions')}}
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function edit(id, name, email, address, phone, is_admin, is_active) {
            document.getElementById('edit_user_id').value = id;
            document.getElementById('name_update').value = name;
            document.getElementById('email_update').value = email;
            document.getElementById('address_update').value = address;
            document.getElementById('phone_update').value = phone;

            if(is_admin == 1) {
                document.getElementById('is_admin_yes_update').checked = true;
                document.getElementById('is_admin_no_update').checked = false;
            } else {
                document.getElementById('is_admin_yes_update').checked = false;
                document.getElementById('is_admin_no_update').checked = true;
            }

            if(is_active == 1) {
                document.getElementById('is_active_yes_update').checked = true;
                document.getElementById('is_active_no_update').checked = false;
            } else {
                document.getElementById('is_active_yes_update').checked = false;
                document.getElementById('is_active_no_update').checked = true;
            }

        }

        function destroy(id) {
            document.getElementById('delete_id').value = id;
        }

        function getSecurityQuestions(user_id) {
            fetch(`/users/${user_id}/security-questions`)
                .then(response => response.json())
                .then(data => {
                    // Handle the security questions data here
                    document.getElementById('security_questions_user_id').value = user_id;
                    document.getElementById('first_question').innerHTML = data.first_question;
                    document.getElementById('first_answer').value = data.first_answer;
                    document.getElementById('second_question').innerHTML = data.second_question;
                    document.getElementById('second_answer').value = data.second_answer;
                    document.getElementById('third_question').innerHTML = data.third_question;
                    document.getElementById('third_answer').value = data.third_answer;

                })
                .catch(error => {
                    console.error('Error fetching security questions:', error);
                });
        }

    </script>
@endsection