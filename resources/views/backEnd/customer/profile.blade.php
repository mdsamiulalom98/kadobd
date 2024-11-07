@extends('backEnd.layouts.master')
@section('title','Customer Profile')
@section('css')
<link href="{{asset('public/backEnd')}}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('customers.index')}}" class="btn btn-primary rounded-pill">Customer List</a>
                    <form method="post" action="{{route('customers.adminlog')}}" class="d-inline" target="_blank">
                        @csrf
                    <input type="hidden" value="{{$profile->id}}" name="hidden_id">        
                    <button type="button" class="btn btn-info rounded-pill change-confirm" title="Login as customer"><i class="fe-log-in"></i> Login</button></form>
                </div>
                <h4 class="page-title">Customer Profile</h4>
            </div>
        </div>
    </div>  
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-4 col-xl-4">
            <div class="card text-center">
                <div class="card-body">
                    <img src="{{asset($profile->image)}}" class="rounded-circle avatar-lg img-thumbnail"
                    alt="profile-image">

                    <h4 class="mb-0">{{$profile->name}}</h4>
                    <p class="text-muted">Sponsor - {{$profile->refferal_id}}</p>

                    <a href="tel:{{$profile->phone}}" class="btn btn-success btn-xs waves-effect mb-2 waves-light">Call</a>
                    <a href="mailto:{{$profile->email}}" class="btn btn-danger btn-xs waves-effect mb-2 waves-light">Email</a>

                    <div class="text-start mt-3">
                        <h4 class="font-13 text-uppercase">About Me :</h4>
                        <table class="table">
                            <tbody>
                            <tr class="text-muted mb-2 font-13">
                                <td>Full Name </td>
                                <td class="ms-2">{{$profile->name}}</td>
                            </tr>
                            <tr class="text-muted mb-2 font-13">
                                <td>Telegram </td>
                                <td class="ms-2">{{$profile->phone}}</td>
                            </tr>
                            <tr class="text-muted mb-2 font-13">
                                <td>Bkash </td>
                                <td class="ms-2">{{$profile->bkash_no}}</td>
                            </tr>
                            <tr class="text-muted mb-2 font-13">
                                <td>Village</td>
                                <td class="ms-2">{{$profile->village_name}}</td>
                            </tr>
                            <tr class="text-muted mb-2 font-13">
                                <td>House </td>
                                <td class="ms-2">{{$profile->house}}</td>
                            </tr>
                            <tr class="text-muted mb-2 font-13">
                                <td>Road </td>
                                <td class="ms-2">{{$profile->road}}</td>
                            </tr>
                            <tr class="text-muted mb-2 font-13">
                                <td>Word</td>
                                <td class="ms-2">{{$profile->word_no}}</td>
                            </tr>
                            <tr class="text-muted mb-2 font-13">
                                <td>Gender</td>
                                <td class="ms-2">{{$profile->gender}}</td>
                            </tr>
                            <tr class="text-muted mb-2 font-13">
                                <td>NID</td>
                                <td class="ms-2">{{$profile->nid}}</td>
                            </tr>
                            <tr class="text-muted mb-1 font-13">
                                <td>District </td> 
                                <td class="ms-2">{{$profile->district}}</td>
                            </tr>
                            <tr class="text-muted mb-1 font-13">
                                <td>Upzlila </td> 
                                <td class="ms-2">{{$profile->area}}</td>
                            </tr>
                            
                            <tr class="text-muted mb-1 font-13">
                                <td>Balance :</td> 
                                <td class="ms-2">{{$profile->balance}} Tk</td>
                            </tr>
                            <tr class="text-muted mb-1 font-13">
                                <td>Savings :</td> 
                                <td class="ms-2">{{$profile->savings}} Tk</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end card -->

        </div> <!-- end col-->

        <div class="col-lg-8 col-xl-8">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills nav-fill navtab-bg">

                        <li class="nav-item">
                            <a href="#step1" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                               Step-1
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#step2" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                               Step-2
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#step3" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                               Step-3
                            </a>
                        </li> 
                        <li class="nav-item">
                            <a href="#step4" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                               Step-4
                            </a>
                        </li> 
                        <li class="nav-item">
                            <a href="#profits" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                               Profits
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#withdraw" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                               Withdraw
                            </a>
                        </li>

                        <li class="nav-item mt-2">
                            <a href="#order" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                               Order
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="step1">
                            <h4>Step-1</h4>
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($step1 as $key=>$value)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$value->name}}</td>
                                        <td>{{$value->phone}}</td>
                                        <td>@if($value->status=='pending')<span class="badge bg-soft-danger text-danger">{{$value->status}}</span> @else <span class="badge bg-soft-success text-success">{{$value->status}}</span> @endif</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- end step1 -->
                        <div class="tab-pane" id="step2">
                            <h4>Step-2</h4>
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($step2 as $key=>$value)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$value->name}}</td>
                                        <td>{{$value->phone}}</td>
                                        <td>@if($value->status=='pending')<span class="badge bg-soft-danger text-danger">{{$value->status}}</span> @else <span class="badge bg-soft-success text-success">{{$value->status}}</span> @endif</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- end step2 -->
                        <div class="tab-pane" id="step3">
                            <h4>Step-3</h4>
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($step3 as $key=>$value)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$value->name}}</td>
                                        <td>{{$value->phone}}</td>
                                        <td>@if($value->status=='pending')<span class="badge bg-soft-danger text-danger">{{$value->status}}</span> @else <span class="badge bg-soft-success text-success">{{$value->status}}</span> @endif</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- end step3 -->
                        <div class="tab-pane" id="step4">
                            <h4>Step-4</h4>
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($step4 as $key=>$value)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$value->name}}</td>
                                        <td>{{$value->phone}}</td>
                                        <td>@if($value->status=='pending')<span class="badge bg-soft-danger text-danger">{{$value->status}}</span> @else <span class="badge bg-soft-success text-success">{{$value->status}}</span> @endif</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- end step3 -->
                        <div class="tab-pane" id="profits">
                            <h4>Profits</h4>
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Order</th>
                                        <th>Date</th>
                                        <th>ID Name</th>
                                        <th>Profit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($profits as $key=>$value)
                                    <tr>
                                        <td> {{$loop->iteration}}</td>
                                        <td>{{$value->order?$value->order->invoice_id:''}}</td>
                                        <td>{{$value->created_at->format('d-m-y h:m a')}}</td>
                                        <td>{{$value->order?$value->order->customer->name:''}}</td>
                                        <td>৳{{$value->profit}} </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- end step5 -->
                        <div class="tab-pane" id="order">
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Invoice</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($profile->orders as $key=>$value)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$value->invoice_id}}</td>
                                        <td>{{$value->shipping?$value->shipping->name:''}}</td>
                                        <td>{{date('d-m-Y', strtotime($value->created_at))}} ,{{date('h:i a', strtotime($value->created_at))}}</td>
                                        <td>৳{{$value->amount}}</td>
                                        <td>@if($value->order_status=='pending')<span class="badge bg-soft-danger text-danger">{{$value->order_status}}</span> @else <span class="badge bg-soft-success text-success">{{$value->order_status}}</span> @endif</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- end  item-->
                        <div class="tab-pane" id="withdraw">
                            <h4>Withdraw</h4>
                            <div class="withdraw_form">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="{{route('withdraw')}}" method="POST" class=row data-parsley-validate="" >
                                                @csrf
                                            <div class="col-sm-6">
                                                <input type="hidden" name="customer_id" value="{{$profile->id}}">
                                                <div class="form-group mb-3">
                                                    <label for="type_id" class="form-label">Type</label>
                                                     <select class="form-control select2 @error('type_id') is-invalid @enderror" name="type_id" data-toggle="select2"  data-placeholder="Choose ..." style="width:100%;" required>
                                                        <optgroup >
                                                            <option value="">Select..</option>
                                                            <option value="1">Main Balance  ({{$profile->balance}})</option>
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
                                                    <input type="submit" class="btn btn-success" value="Submit">
                                                </div>

                                            </form>

                                        </div> <!-- end card-body-->
                                    </div> <!-- end card-->
                                </div>
                                </div>
                            <h4>Withdraw List</h4>
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Type</th>
                                        <th>Invoice</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($withdraws as $key=>$value)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{date('d-m-Y', strtotime($value->created_at))}} ,{{date('h:i a', strtotime($value->created_at))}}</td>
                                        <td>৳{{$value->amount}}</td>
                                        <td>@if($value->type_id=='1')<span class="badge bg-soft-info text-info">Main Balance</span> @else <span class="badge bg-soft-success text-success"> Savings</span> @endif</td>
                                        <td><a href="{{route('customer.withdraw.invoice',['id'=>$value->id])}}" class="btn btn-xs  btn-success waves-effect waves-light"><i class="fe-eye"></i></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- end  item-->
                    </div> <!-- end tab-content -->
                </div>
            </div> <!-- end card-->

        </div> <!-- end col -->
    </div>
    <!-- end row-->

</div> <!-- container -->

</div> <!-- content -->
@endsection


@section('script')
<script src="{{asset('public/backEnd/')}}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-validation.init.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{asset('public/backEnd/')}}/assets/js/pages/form-advanced.init.js"></script>
@endsection