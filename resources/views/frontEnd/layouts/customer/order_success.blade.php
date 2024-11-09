@extends('frontEnd.layouts.master')
@section('title','Order Success')
@section('content')
<section class="customer-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-8">
                <div class="success-img">
                    <img src="{{asset('public/frontEnd/images/order-success.png')}}" alt="">
                </div>
                <div class="success-title">
                    <h2>আপনার অর্ডারটি আমাদের কাছে সফলভাবে পৌঁছেছে। আগামী ২ থেকে ৪ কর্ম দিবসের মধ্যে আপনার অর্ডারকৃত পন্যটি আপনি হাতে পেয়ে যাবেন ইনশাআল্লাহ। কাডোবিডি থেকে কেনাকাটার জন্য আপনাকে ধন্যবাদ </h2>
                </div>

                <h5 class="my-3">Your Order Details</h5>
                <div class="success-table">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>
                                    <p>Invoice ID</p>
                                    <p><strong>{{$order->invoice_id}}</strong></p>
                                </td>
                                <td>
                                    <p>Date</p>
                                    <p><strong>{{$order->created_at->format('d-m-y')}}</strong></p>
                                </td>
                                <td>
                                    <p>Phone</p>
                                    <p><strong>{{$order->shipping?$order->shipping->phone:''}}</strong></p>
                                </td>
                                <td>
                                    <p>Total</p>
                                    <p><strong>৳ {{$order->amount}}</strong></p>
                                </td>
                            </tr>
                            <tr>
                                @php
                                    $payments = App\Models\Payment::where('order_id',$order->id)->first();
                                @endphp
                                <td colspan="4">
                                    <p>Payment Method</p>
                                    <p><strong>{{$payments->payment_method}}</strong></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- success table -->
                <h5 class="my-4">Pay with cash upon delivery</h5>
                <div class="success-table">
                    <h6 class="mb-3">Order Delivery</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderdetails as $key=>$value)
                            <tr>
                                <td>
                                    <p>{{$value->product_name}} x {{$value->qty}}</p>

                                </td>
                                <td><p><strong>৳ {{$value->sale_price}}</strong></p></td>
                            </tr>
                            @endforeach
                            <tr>
                                <th  class="text-end px-4">Net Total</th>
                                <td><strong id="net_total">৳{{$order->amount + $order->discount - ($order->cod_charge)}}</strong></td>
                            </tr>
                            <tr>
                                <th  class="text-end px-4">Shipping Cost</th>
                                <td>
                                    <strong id="cart_shipping_cost">৳{{$order->shipping_charge}}</strong>
                                </td>
                            </tr>
                            @if($order->cod_charge > 0)
                             <tr>
                                <th  class="text-end px-4">Cod Charge</th>
                                <td>
                                    <strong id="cart_shipping_cost">৳{{$order->cod_charge}}</strong>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th  class="text-end px-4">Discount</th>
                                <td>
                                    <strong id="cart_shipping_cost">৳{{$order->discount}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <th  class="text-end px-4">Grand Total</th>
                                <td>
                                    <strong id="grand_total">৳{{($order->amount + $order->discount +$order->shipping_charge) - ($order->cod_charge)}}</strong>
                                </td>
                            </tr>
                            @if($order->savings_pay > 0)
                            <tr>
                                <th  class="text-end px-4">Savings Pay</th>
                                <td>
                                    <strong id="grand_total">৳{{$order->savings_pay}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <th  class="text-end px-4">Due Amount</th>
                                <td>
                                    <strong id="grand_total">৳{{($order->amount + $order->discount +$order->shipping_charge) - ($order->cod_charge+$order->savings_pay)}}</strong>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>
                                    <h5 class="my-4">Billing Address</h5>
                                    <p>Name : {{$order->shipping?$order->shipping->name:''}}</p>
                                    <p>Phone : {{$order->shipping?$order->shipping->phone:''}}</p>
                                    <p>District : {{$order->shipping?$order->shipping->district:''}}</p>
                                    <p>Upazila : {{$order->shipping?$order->shipping->upazila:''}}</p>
                                    <p>Village Name : {{$order->shipping?$order->shipping->village_name:''}}</p>
                                    <p>Word No : {{$order->shipping?$order->shipping->word_no:''}}</p>
                                    <p>Full Address : {{$order->shipping?$order->shipping->address:''}}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- success table -->
                <a href="{{route('home')}}" class=" my-5 btn btn-primary">Go To Home</a>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script src="{{asset('public/frontEnd/')}}/js/parsley.min.js"></script>
<script src="{{asset('public/frontEnd/')}}/js/form-validation.init.js"></script>
@endpush
