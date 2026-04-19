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
    {!! html()->modelForm(null, null)->class('form')->id('search')->attribute('action',route('customer.orders.index'))->attribute('method','GET')->open() !!}
    {!! html()->closeModelForm() !!}
    <div class="container mt-4" style="background-color: white; padding: 20px; border-radius: 5px;">
        <div class="row">
            <div class="col-12">
                <h1>Orders</h1>
                <hr>
                @include('includes.form_error')
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-2">
                           <select class="form-select w-100" id="month" name="month" form="search">
                                <option value="">Select Month</option>
                                @foreach(range(1, 12) as $month)
                                    @if($month == request('month'))
                                        <option value="{{ $month }}" selected>{{ date('F', mktime(0, 0, 0, $month, 10)) }}</option>
                                    @else
                                        <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 10)) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select class="form-select w-100" id="year" name="year" form="search">
                                <option value="">Select Year</option>
                                @foreach(range(date('Y'), date('Y') - 5) as $year)
                                    @if($year == request('year'))
                                        <option value="{{ $year }}" selected>{{ $year }}</option>
                                    @else
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            {{ html()->submit('SEARCH')->class('btn btn-outline-success')->attribute('style','width:100%;')->attribute('form','search')}}
                        </div>
                        <div class="col-sm-2">
                        </div>
                    </div>
                </div>
                
                <div class="container-fluid">
                    <div class="row">
                        <table class="table table-bordered mt-2 px-4" style="background-color: white;">
                            <thead>
                                <tr>
                                    <th>Reference ID</th>
                                    <th>Order Date</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>OR-{{ date('Y', strtotime($order->order_date)) }}-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ date('Y-m-d', strtotime($order->order_date)) }}</td>
                                        <td>{{ $order->total_amount }}</td>
                                        <td>
                                            @if($order->status == 'pending')
                                                <span class="badge bg-warning text-dark">PENDING</span>
                                            @elseif($order->status == 'completed')
                                                <span class="badge bg-success">COMPLETED</span>
                                            @elseif($order->status == 'cancelled')
                                                <span class="badge bg-danger">CANCELLED</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a target="_blank" href="{{ route('customer.orders.pdf', $order->id) }}" class="btn btn-sm btn-secondary">Download PDF</a>
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

@endsection

@section('js')
    
@endsection