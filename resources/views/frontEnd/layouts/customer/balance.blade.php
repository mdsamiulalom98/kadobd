@extends('frontEnd.layouts.master')
@section('title','Customer Account')
@section('content')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .customer_form {
    margin-bottom: 15px;
}

.customer_form button,.customer_form button:focus {
    background: #4C32B7;
    color: #fff;
}
.customer_form input,.customer_form input:focus {
    background: #F5F7F9;
}
</style>
@endpush
<section class="customer-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div class="customer-sidebar">
                    @include('frontEnd.layouts.customer.sidebar');
                </div>
            </div>
            <div class="col-sm-9">
                <form class="customer_form">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <input type="text" id="start_date" name="start_date" class="form-control myDate" required placeholder="Start Date" />
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <input type="text" id="end_date" name="end_date" class="form-control myDate" required  placeholder="End Date"  />
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <button type="submit" class="form-control">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="customer-content">
                    <div class="row">
                        <div class="col-12"><h5 class="account-title">My Balance = <strong> Current: ৳{{auth()->guard('customer')->user()->balance}}</strong> | <strong> Savings: ৳{{auth()->guard('customer')->user()->savings}}</strong></h5></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Invoice</th>
                                    <th>Name</th>
                                    <th>Date & Time</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $key=>$value)
                                <tr>
                                    <td> {{$loop->iteration}}</td>
                                    <td>{{$value->order->invoice_id}} </td>
                                    <td>{{$value->order?$value->order->shipping->name:''}}</td>
                                    <td>{{date('d-m-Y h:i a', strtotime($value->created_at))}}</td>
                                    <td>৳{{$value->profit}} </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr(".myDate", {
         dateFormat: "Y-m-d",
        
    });
</script>
@endpush