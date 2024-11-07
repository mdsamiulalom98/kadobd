@extends('frontEnd.layouts.master')
@section('title','Customer Referrals')
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
                <div class="account-tab">
                    <ul>
                        <li><a href="{{route('customer.refferals',['level'=>'1'])}}" class="{{request()->get('level')==1?'active':''}}">Level 1</a></li>
                        <li><a href="{{route('customer.refferals',['level'=>'2'])}}" class="{{request()->get('level')==2?'active':''}}">Level 2</a></li>
                        <li><a href="{{route('customer.refferals',['level'=>'3'])}}" class="{{request()->get('level')==3?'active':''}}">Level 3</a></li>
                        <li><a href="{{route('customer.refferals',['level'=>'4'])}}" class="{{request()->get('level')==4?'active':''}}">Level 4</a></li>
                    </ul>
                </div>
                <div class="customer-content">
                    <h5 class="account-title">Level {{request()->get('level')}}</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Refer</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $key=>$value)
                                @php 
                                   if(request()->get('level')==1){
                                     $refer =  App\Models\Customer::where('id',$value->refferal_1)->first();
                                   }elseif(request()->get('level')==2){
                                    $refer =  App\Models\Customer::where('id',$value->refferal_1)->first();
                                   }elseif(request()->get('level')==3){
                                    $refer =  App\Models\Customer::where('id',$value->refferal_1)->first();
                                   }else{
                                    $refer =  App\Models\Customer::where('id',$value->refferal_1)->first();
                                   }
                                @endphp
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$value->name}}</td>
                                    <td>{{$value->phone}}</td>
                                    <td>{{$refer?$refer->name:''}}</td>
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