@extends('backEnd.layouts.master')
@section('title','Withdraw Process')
@section('content')
<div class="container-fluid">
    
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Withdraw Process [Invoice : #{{$data->id}}]</h4>
            </div>
        </div>
    </div>       
    <!-- end page title --> 
   <div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        
                        <tr>
                            <td>ID</td>
                            <td>{{$data->id}}</td>
                        </tr>
                        <tr>
                            <td>Amount</td>
                            <td>{{$data->amount}}</td>
                        </tr>
                        <tr>
                            <td>Time</td>
                            <td>{{date('d-m-Y', strtotime($data->created_at))}}<br> {{date('h:i:s a', strtotime($data->created_at))}}</td>
                        </tr>
                        <tr>
                            <td>Customer Name</td>
                            <td>{{$data->customer?$data->customer->name:''}}</td>
                        </tr>
                        <tr>
                            <td>Customer Phone</td>
                            <td>{{$data->customer?$data->customer->phone:''}}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>{{$data->status}}</td>
                        </tr>
                        <tr>
                            <td>Bkash</td>
                            <td>{{$data->customer?$data->customer->bkash_no:''}}</td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
               <form action="{{route('customer.withdraw_change')}}" method="POST" class=row data-parsley-validate="" name="editForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$data->id}}">
                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                <label for="category_id" class="form-label">Order Status</label>
                                 <select class="form-control @error('status') is-invalid @enderror" value="{{ old('status') }}" name="status"  required>
                                    <option value="">Select..</option>
                                    <option value="paid">Paid</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col end -->
                         <div class="col-sm-12">
                          <div class="form-group mb-3">
                               <label for="admin_note" class="form-label"> Note </label>
                               <textarea name="admin_note" class="form-control @error('admin_note') is-invalid @enderror">{{$data->admin_note}}</textarea>
                                @error('admin_note')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                 @enderror
                          </div>
                        </div>
                  
                    <!-- col end -->
                    <div>
                        <input type="submit" class="btn btn-success" value="Submit">
                    </div>

                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
   </div>
</div>
@endsection