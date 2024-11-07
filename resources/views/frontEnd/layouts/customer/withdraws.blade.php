@extends('frontEnd.layouts.master')
@section('title','My Withdraw')
@section('content')
<section class="customer-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div class="customer-sidebar">
                    @include('frontEnd.layouts.customer.sidebar');
                </div>
            </div>
            <div class="col-sm-9">
                <div class="customer-content">
                    <div class="row">
                        <div class="col-12"><h5 class="account-title">My Withdraw = <strong> Current: ৳{{auth()->guard('customer')->user()->balance}}</strong> | <strong> Savings: ৳{{auth()->guard('customer')->user()->savings}}</strong></h5></div>
                    </div>
                    <div class="new_withdraw">
                        <form action="{{route('customer.withdraw_save')}}" method="POST" class=row data-parsley-validate="" >
                        @csrf
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="type_id" class="form-label">Type</label>
                                 <select class="form-control select2 @error('type_id') is-invalid @enderror" name="type_id" data-toggle="select2"  data-placeholder="Choose ..." style="width:100%;" required>
                                    <optgroup >
                                        <option value="">Select..</option>
                                        <option value="1">Main Balance  ({{auth()->guard('customer')->user()->balance}})</option>
                                    </optgroup>
                                </select>
                                @error('type_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col end -->
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="amount" class="form-label">Amount *</label>
                                <input type="text" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount') }}" id="amount" required="">
                                @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="amount" class="form-label">Note</label>
                                <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror"></textarea>

                                @error('note')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        
                        <div>
                            @if($isDateValid == 'true')
                            <input type="submit" class="btn btn-success" value="Submit">
                            @else
                            <input type="submit" disabled class="btn btn-secondary" value="Withdraw Date Expired 😔" style="curser:no-drop">
                            @endif
                        </div>

                    </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Date & Time</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $key=>$value)
                                <tr>
                                    <td> {{$loop->iteration}}</td>
                                    <td>{{date('d-m-Y', strtotime($value->created_at))}} ,{{date('h:i a', strtotime($value->created_at))}}</td>
                                    <td>৳{{$value->amount}}</td>
                                    <td>@if($value->type_id==1) Balance @else Savings @endif</td>
                                    <td>{{$value->status}}</td>
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