@extends('layouts.app')

@section('content')
{{ html()->form('POST', route('register'))->id('store')->acceptsFiles()->open() }}
{{ html()->form()->close() }}
@include('includes.form_error')
<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
         <label style="font-weight: bold;">Email Address</label>

        <div class="col-md-12">
            <input id="email" type="email"  class="au-input au-input--full" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="form-group mt-4">
        <label style="font-weight: bold;">Password</label>

        <div class="col-md-12">
            <input id="password" type="password" class="au-input au-input--full @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row mb-0">
        <div class="col-md-12 mt-4">
            <button type="submit" class="au-btn au-btn--block au-btn--blue m-b-20" type="submit">
                {{ __('Login') }}
            </button>
             <button type="button" class="au-btn au-btn--block au-btn--blue2 m-b-20" data-bs-toggle="modal" data-bs-target="#modalCreate">
                REGISTER
            </button> 
        </div>
    </div>
</form>

{{-- MODAL REGISTER --}}
<div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-lg modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Register</h1>
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
                            <p class="text-center" style="font-weight: bold;">SECURITY QUESTIONS</p>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        {{ html()->label('What is your favorite color?')->attribute('style','font-weight:bold;')->attribute('for','security_question_1') }}
                                        {{ html()->text('first_answer')->class('form-control')->id('first_answer')->attribute('style','font-weight:bold;')->attribute('form','store') }}
                                    </div>
                                    <div class="form-group pt-1">
                                        {{ html()->label('What is your pet\'s name?')->attribute('style','font-weight:bold;')->attribute('for','security_question_2') }}
                                        {{ html()->text('second_answer')->class('form-control')->id('second_answer')->attribute('style','font-weight:bold;')->attribute('form','store') }}
                                    </div>
                                    <div class="form-group pt-1">
                                        {{ html()->label('Who is your favorite actor?')->attribute('style','font-weight:bold;')->attribute('for','security_question_3') }}
                                        {{ html()->text('third_answer')->class('form-control')->id('third_answer')->attribute('style','font-weight:bold;')->attribute('form','store') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                {{ html()->submit('REGISTER')->class('btn btn-outline-success')->attribute('form','store')}}
            </div>
        </div>
    </div>
</div>
@endsection
