@extends('frontEnd.layouts.master')
@section('title','Customer Account')
@section('content')
<section class="customer-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div class="customer-sidebar">
                    @include('frontEnd.layouts.customer.sidebar')
                </div>
            </div>
            <div class="col-sm-9">
                <div class="customer-content mb-3">
                    <h5 class="account-title">Refer Link</h5>
                    <div class="referral-program-referral">
                        <form class="reffer_form">
                            <div class="form-copy-input-cont d-flex align-items-center flex-grow-1">
                            <input type="text" id="aff-link" value="{{url('?r='.Auth::guard('customer')->user()->refferal_id)}}" readonly="" />
                            </div>             
                            <button type="button" class="btn btn-with-icon form-copy-btn copylink" value="{{url('?r='.Auth::guard('customer')->user()->refferal_id)}}" data-toggle="tooltip" data-placement="right" title="Copy to Clipboard"><i class="far fa-copy"></i></button>
                        </form>
                    </div>
                </div>
                <div class="customer-content customer_account">
                    <h5 class="account-title">My Account</h5>
                    <table class="table">
                        @php
                            $customer = \App\Models\Customer::with('cust_area')->find(Auth::guard('customer')->user()->id);
                        @endphp
                        <tbody>
                            <tr>
                                <td>Name</td>
                                <td>{{$customer->name}}</td>
                            </tr>
                            <tr>
                                <td>Refferal ID</td>
                                <td>{{$customer->refferal_id}}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>{{$customer->phone}}</td>
                            </tr>
                            <tr>
                                <td>Bkash</td>
                                <td>{{$customer->bkash_no}}</td>
                            </tr>
                            <tr>
                                <td>Disctrict</td>
                                <td>{{$customer->district}}</td>
                            </tr>
                            <tr>
                                <td>Area</td>
                                <td>{{$customer->cust_area?$customer->cust_area->area_name:''}}</td>
                            </tr>
                            <tr>
                                <td>Village</td>
                                <td>{{$customer->village_name}}</td>
                            </tr>
                            <tr>
                                <td>House</td>
                                <td>{{$customer->house}}</td>
                            </tr>
                            <tr>
                                <td>Road</td>
                                <td>{{$customer->road}}</td>
                            </tr>
                            <tr>
                                <td>Word No</td>
                                <td>{{$customer->word_no}}</td>
                            </tr>
                            <tr>
                                <td>Gender</td>
                                <td>{{$customer->gender}}</td>
                            </tr>
                            <tr>
                                <td>Nid</td>
                                <td>{{$customer->nid}}</td>
                            </tr>
                            <tr>
                                <td>Image</td>
                                <td><img src="{{asset($customer->image)}}" alt="" class="backend_img"></td>
                            </tr>
                            <tr>
                                <td>Balance</td>
                                <td>৳{{$customer->balance}}</td>
                            </tr>
                            <tr>
                                <td>Savings Balance</td>
                                <td>৳{{$customer->savings}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script src="{{asset('public/frontEnd/')}}/js/parsley.min.js"></script>
<script src="{{asset('public/frontEnd/')}}/js/form-validation.init.js"></script>
<script>
         $(".copylink").on("click", function () {
            var copyText = $(this).val();
            var el = document.createElement('textarea');
            el.value = copyText;
            el.setAttribute('readonly', '');
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
             toastr.success('Refer link copy success');
        });
    </script>
@endpush