@extends('frontEnd.layouts.master') @section('title', 'Customer Checkout') @push('css')
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/select2.min.css') }}" />
@endpush @section('content')
<section class="chheckout-section">
    @php
        $subtotal = Cart::instance('shopping')->subtotal();
        $subtotal = str_replace(',', '', $subtotal);
        $subtotal = str_replace('.00', '', $subtotal);
        $shipping = Session::get('shipping') ? Session::get('shipping') : 0;
        $coupon = Session::get('coupon_amount') ? Session::get('coupon_amount') : 0;
        $discount = Session::get('discount')?Session::get('discount'):0;
        $cod_charge = Session::get('cod_charge')?Session::get('cod_charge'):0;
    @endphp
    <div class="container">
        <div class="row">
            <div class="col-sm-5 cust-order-1">
                <div class="checkout-shipping">
                    <form action="{{ route('customer.ordersave') }}" method="POST" data-parsley-validate="">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h6>Fill in the information to confirm your bellow form and click on the "Order Now" button</h6>
                                
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group mb-3">
                                            <label for="name">Full Name *</label>
                                            <input type="text" id="name"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                value="@if(Auth::guard('customer')->user()){{ Auth::guard('customer')->user()->name }}@else{{ old('name') }} @endif"
                                                required />
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- col-end -->
                                    <div class="col-sm-6">
                                        <div class="form-group mb-3">
                                            <label for="phone">Mobile Number *</label>
                                            <input type="text" minlength="11" id="number" maxlength="11"
                                                pattern="0[0-9]+"
                                                title="please enter number only and 0 must first character"
                                                title="Please enter an 11-digit number." id="phone"
                                                class="form-control @error('phone') is-invalid @enderror" name="phone"
                                                value="@if(Auth::guard('customer')->user()){{ Auth::guard('customer')->user()->phone }}@else{{old('phone') }}@endif"
                                                required />
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- col-end -->
                                    <div class="col-sm-6">
                                        <div class="form-group mb-3">
                                            <label for="district">District *</label>
                                            <select  id="district" class="form-control select2 district @error('district') is-invalid @enderror" name="district" value="{{ old('district') }}"  required>
                                                <option value="">Select...</option>
                                                @foreach($districts as $key=>$district)
                                                <option value="{{$district->district}}">{{$district->district}}</option>
                                                @endforeach
                                            </select>
                                            @error('district')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- col-end -->
                                    <div class="col-sm-6">
                                        <div class="form-group mb-3">
                                            <label for="upazila">Upazila *</label>
                                            <select id="area" class="form-control  area select2 @error('upazila') is-invalid @enderror" name="upazila" value="{{ old('upazila') }}"  required>
                                                <option value="">Select...</option>
                                                
                                            </select>
                                            @error('upazila')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- col-end -->
                                    <div class="col-sm-6">
                                        <div class="form-group mb-3">
                                            <label for="village_name">Village Name *</label>
                                            <input type="text" id="village_name" class="form-control @error('name') is-invalid @enderror" name="village_name" value="{{ old('village_name') }}" placeholder="Enter villege name" required>
                                            @error('village_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- col-end -->
                                    <div class="col-sm-6">
                                        <div class="form-group mb-3">
                                            <label for="word_no">Word No (Optional)</label>
                                            <input type="text" id="word_no" class="form-control @error('name') is-invalid @enderror" name="word_no" value="{{ old('word_no') }}" placeholder="Enter word_no name">
                                            @error('word_no')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- col-end -->
                                    <div class="col-sm-12">
                                        <div class="form-group mb-3">
                                            <label for="address">Full Address *</label>
                                            <input type="address" id="address"
                                                class="form-control @error('address') is-invalid @enderror"
                                                name="address"
                                                value="@if(Auth::guard('customer')->user()){{ Auth::guard('customer')->user()->address }}@else{{ old('address') }} @endif"
                                                required />
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="card checkout-shipping payment-form mt-3">
                                           <div class="card-header">
                                            <h5>Payment Method</h5>
                                           </div>
                                           <div class="card-body pt-2">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                
                                            <div class="card form-check payment_method mt-3" data-id="cod">
                                              <input type="radio" class="form-check-input" name="payment_method" id="cod" value="cod" required="" checked />
                                              <label class="form-check-label" for="cod">
                                               <img src="{{asset('public/frontEnd/images/cod.png')}}" width="60px" alt="cod" />
                                              </label>
                                             </div>
                                             <div class="card form-check payment_method mt-3" data-id="bkash">
                                              <input type="radio" class="form-check-input" name="payment_method" id="bkash" value="bkash" required="" />
                                              <label class="form-check-label" for="bkash">
                                               <img src="{{asset('public/frontEnd/images/bkash.png')}}" width="60px" alt="bkash" />
                                              </label>
                                             </div>
                                             <div class="card form-check payment_method mt-3" data-id="rocket">
                                              <input type="radio" class="form-check-input" name="payment_method" id="rocket" value="rocket" required="" />
                                              <label class="form-check-label" for="rocket">
                                               <img src="{{asset('public/frontEnd/images/rocket.png')}}" width="60px" alt="rocket" />
                                              </label>
                                             </div>
                                             <div class="card form-check payment_method mt-3" data-id="dbbl">
                                              <input type="radio" class="form-check-input" name="payment_method" id="dbbl" value="dbbl" required="" />
                                              <label class="form-check-label" for="dbbl">
                                               <img src="{{asset('public/frontEnd/images/dbbl.png')}}" width="60px" alt="dbbl" />
                                              </label>
                                             </div>
                                             <div class="card form-check payment_method mt-1" data-id="brac">
                                              <input type="radio" class="form-check-input" name="payment_method" id="brac" value="brac" required="" />
                                              <label class="form-check-label" for="brac">
                                               <img src="{{asset('public/frontEnd/images/brac-bank.png')}}" width="60px" alt="brac" />
                                              </label>
                                             </div>
                                            </div>
                                            <!-- payment option end -->
                                            <div class="payment-instruction">
                                            <div class="codform"></div>
                                            <div class="bkashform">
                                                <p>বিকাশে টাকা পাঠাতে নিচের ধাপগুলি অনুসরণ করুন:</p>
                                                <p> 1. ডায়াল মেনু থেকে *247# ডায়াল করুন, অথবা বিকাশ অ্যাপে যান।</p>
                                                <p> 2. "টাকা পাঠান" এ ক্লিক করুন।</p>
                                                <p> 3. প্রাপকের নম্বর হিসাবে এই নম্বরটি লিখুন: 01718505404</p>
                                                <p> 4. টাকার পরিমাণ লিখুন ( ক্যাশ আউট চার্জ সহ)।</p>
                                                <p> 5. রেফারেন্স হিসাবে 1234 দিন।</p>
                                                <p> 6. নিশ্চিত করতে আপনার বিকাশ পিন লিখুন।</p>
                                                <p> 7. নিচের বক্সে আপনার লেনদেন আইডি এবং যে নম্বর থেকে আপনি টাকা পাঠিয়েছেন সেটি লিখুন।</p> 
                                                <p> 8. "ORDER PLACE" বোতামে ক্লিক করুন৷</p>
                                              
                                             </div>
                                             <div class="rocketform">
                                                <p>রকেটে টাকা পাঠাতে নিচের ধাপগুলো অনুসরণ করুন:  </p>
                                                <p>1. ডায়াল মেনু থেকে *322# ডায়াল করুন বা রকেট অ্যাপে যান।  </p>
                                                <p>2. "টাকা পাঠান" এ ক্লিক করুন।  </p>
                                                <p>3. প্রাপক নম্বর হিসাবে এই নম্বরটি লিখুন: 017185054047৷  </p>
                                                <p>4. টাকার পরিমাণ লিখুন (ক্যাশ আউট চার্জ সহ)।  </p>
                                                <p>5. রেফারেন্স হিসাবে 1234 দিন।  </p>
                                                <p>6. নিশ্চিত করতে আপনার রকেট পিন লিখুন।  </p>
                                                <p>7. নিচের বক্সে আপনার লেনদেন আইডি এবং যে নম্বর থে কে আপনি টাকা পাঠিয়েছেন সেটি লিখুন।  </p>
                                                <p>8. "ORDER PLACE" বোতামে ক্লিক করুন </p>
                                             </div>
                                             <div class="dbblform">
                                                <p> DBBL এ টাকা পাঠাতে নিচের ধাপগুলো অনুসরণ করুন: </p>
                                                <p> 1.  একাউন্ট নম্বর: 2451030377245 </p>
                                                <p> 2. একাউন্টের নাম: Mohammad Mafizul Islam Khan </p>
                                                <p> 3. ব্যাংকের নাম: Dutch Bangla Bank PLC </p>
                                                <p> 4. শাখার নাম: Zirabo</p>
                                                <p> 5. রাউটিং নম্বর: 020331638 </p>
                                                <p> 6. নিচের বক্সে আপনার লেনদেন আইডি এবং যে নম্বর থেকে আপনি টাকা পাঠিয়েছেন সেটি লিখুন।</p>
                                                <p> 7. "ORDER PLACE" বোতামে ক্লিক করুন৷</p>
                                             </div>
                                             <div class="bracform">
                                                <p> Brac Bank এ টাকা পাঠাতে নিচের ধাপগুলো অনুসরণ করুন: </p>
                                                <p> 1. একাউন্ট নম্বর: 1515203566783001 </p>
                                                <p> 2. একাউন্টের নাম: Mohammad Mafizul Islam Khan </p>
                                                <p> 3. ব্যাংকের নাম: ব্র্যাক ব্যাংক </p>
                                                <p> 4. শাখার নাম: Tongi </p>
                                                <p> 5. রাউটিং নম্বর: 020331638 </p>
                                                <p> 6. নিচের বক্সে আপনার লেনদেন আইডি এবং যে নম্বর থেকে আপনি টাকা পাঠিয়েছেন সেটি লিখুন। </p>
                                                <p> 7. "ORDER PLACE" বোতামে ক্লিক করুন৷</p>
                                             </div>
                                             
                                             <div class="trxform row">
                                              <div class="col-sm-6">
                                               <div class="form-group mb-3">
                                                <label for="sender_number">Sender Number</label>
                                                <span data-feather="link"></span>
                                                <input type="text" id="sender_number" class="form-control @error('sender_number') is-invalid @enderror" name="sender_number" value="{{ old('sender_number') }}" />
                                                @error('sender_number')
                                                <span class="invalid-feedback" role="alert">
                                                 <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                               </div>
                                              </div>
                                              <!-- col-end -->
                                              <div class="col-sm-6">
                                               <div class="form-group mb-3">
                                                <label for="trx_id">Transaction ID</label>
                                                <span data-feather="key"></span>
                                                <input type="text" id="trx_id" class="form-control @error('trx_id') is-invalid @enderror" name="trx_id" value="{{ old('trx_id') }}" />
                                                @error('trx_id')
                                                <span class="invalid-feedback" role="alert">
                                                 <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                               </div>
                                              </div>
                                              <!-- col-end -->
                                             </div>
                                            
                                           </div>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-sm-12 custom_check">
                                        <div class="from-group">
                                            <input type="checkbox" value="1" id="savings_pay"> <label for="savings_pay">Pay with savings balance ({{auth()->guard('customer')->user()->savings}} Tk)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <button class="order_place" type="submit">Confirm Order</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- card end -->
                    </form>
                </div>
            </div>
            <!-- col end -->
            <div class="col-sm-7 cust-order-2">
                <div class="cart_details table-responsive-sm">
                    <div class="card">
                        <div class="card-header">
                            <h5>Cart Information</h5>
                        </div>
                        <div class="card-body cartlist">
                            <table class="cart_table table table-bordered table-striped text-center mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 20%;">Remove</th>
                                        <th style="width: 40%;">Product</th>
                                        <th style="width: 20%;">Qty</th>
                                        <th style="width: 20%;">Price</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach (Cart::instance('shopping')->content() as $value)
                                        <tr>
                                            <td>
                                                <a class="cart_remove" data-id="{{ $value->rowId }}"><i
                                                        class="fas fa-trash text-danger"></i></a>
                                            </td>
                                            <td class="text-left">
                                                <a href="{{ route('product', $value->options->slug) }}"> <img
                                                        src="{{ asset($value->options->image) }}" style="height: 30px;width: 30px;" />
                                                    {{ Str::limit($value->name, 20) }}</a>
                                                @if ($value->options->product_size)
                                                    <p>Size: {{ $value->options->product_size }}</p>
                                                @endif
                                                @if ($value->options->product_color)
                                                    <p>Color: {{ $value->options->product_color }}</p>
                                                @endif
                                            </td>
                                            <td class="cart_qty">
                                                <div class="qty-cart vcart-qty">
                                                    <div class="quantity">
                                                        <button class="minus cart_decrement"
                                                            data-id="{{ $value->rowId }}">-</button>
                                                        <input type="text" value="{{ $value->qty }}" readonly />
                                                        <button class="plus cart_increment"
                                                            data-id="{{ $value->rowId }}">+</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="alinur">৳ </span><strong>{{ $value->price }}</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end px-4">SubTotal</th>
                                        <td class="px-4">
                                            <span id="net_total"><span class="alinur">৳
                                                </span><strong>{{ $subtotal }}</strong></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end px-4">Delivery Charge</th>
                                        <td class="px-4">
                                            <span id="cart_shipping_cost"><span class="alinur">৳
                                                </span><strong>{{ $shipping }}</strong></span>
                                        </td>
                                    </tr>
                                    @if($cod_charge)
                                    <tr>
                                        <th colspan="3" class="text-end px-4">COD Charge</th>
                                        <td class="px-4">
                                            <span id="cart_shipping_cost"><span class="alinur">৳
                                                </span><strong>{{ $cod_charge }}</strong></span>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th colspan="3" class="text-end px-4">Discount</th>
                                        <td class="px-4">
                                            <span id="cart_shipping_cost"><span class="alinur">৳
                                                </span><strong>{{ $discount + $coupon }}</strong></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end px-4">Total</th>
                                        <td class="px-4">
                                            <span id="grand_total"><span class="alinur">৳
                                                </span><strong>{{ $subtotal + $shipping+$cod_charge - ($discount+$coupon) }}</strong></span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                             <form action="@if(Session::get('coupon_used')) {{ route('customer.coupon_remove') }} @else {{ route('customer.coupon') }} @endif" class="checkout-coupon-form"  method="POST">
                                @csrf
                                <div class="coupon">
                                    <input  type="text" name="coupon_code" placeholder=" @if(Session::get('coupon_used')) {{Session::get('coupon_used')}} @else Apply Coupon @endif" class="border-0 shadow-none form-control"  />
                                    <input type="submit" value="@if(Session::get('coupon_used')) remove @else apply  @endif "   class="border-0 shadow-none btn btn-theme" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- col end -->
        </div>
    </div>
</section>
 
@push('script')

<script src="{{ asset('public/frontEnd/') }}/js/parsley.min.js"></script>
<script src="{{ asset('public/frontEnd/') }}/js/form-validation.init.js"></script>
<script src="{{ asset('public/frontEnd/') }}/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $(".select2").select2();
    });
</script>

<script>
 $(document).ready(function () {
  $('.codform').hide();
  $(".bkashform").hide();
  $(".rocketform").hide();
  $(".dbblform").hide();
  $(".bracform").hide();
  $(".trxform").hide();

  $(".payment_method").on("click", function () {
   var id = $(this).data("id");
   if(id=="cod"){
      $('.codform').show();
      $('.bkashform').hide();
      $('.roketform').hide();
      $('.dbblform').hide();
      $('.bracform').hide();
      $('.trxform').hide();
    }
   else if (id == "bkash") {
    $(".codform").hide();
    $(".bkashform").show();
    $(".rocketform").hide();
    $(".dbblform").hide();
    $(".bracform").hide();
    $(".trxform").show();
   }
   else if (id == "rocket") {
    $(".codform").hide();
    $(".bkashform").hide();
     $(".rocketform").show();
    $(".dbblform").hide();
    $(".bracform").hide();
    $(".trxform").show();
   } 
   else if (id == "dbbl") {
    $(".codform").hide();
    $(".bkashform").hide();
     $(".rocketform").hide();
    $(".dbblform").show();
    $(".bracform").hide();
    $(".trxform").show();
   }  
   else if (id == "brac") {
    $(".codform").hide();
    $(".bkashform").hide();
     $(".rocketform").hide();
    $(".dbblform").hide();
    $(".bracform").show();
    $(".trxform").show();
   } 
   else if (id == "") {
    $(".codform").hide();
    $(".bkashform").hide();
    $(".rocketform").hide();
    $(".dbblform").hide();
    $(".bracform").hide();
    $(".trxform").hide();
   }
    //   cod charge manage
    $.ajax({
        type: "GET",
        data: { id: id},
        url: "{{route('cashondelivery.charge')}}",
        dataType: "html",
        success: function(response){
           $(".cartlist").html(response);
            
        }
    });
  });
 });
</script>
<script type = "text/javascript">
    dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
    dataLayer.push({
        event    : "view_cart",
        ecommerce: {
            items: [@foreach (Cart::instance('shopping')->content() as $cartInfo){
                item_name     : "{{$cartInfo->name}}",
                item_id       : "{{$cartInfo->id}}",
                price         : "{{$cartInfo->price}}",
                item_brand    : "{{$cartInfo->options->brand}}",
                item_category : "{{$cartInfo->options->category}}",
                item_size     : "{{$cartInfo->options->size}}",
                item_color     : "{{$cartInfo->options->color}}",
                currency      : "BDT",
                quantity      : {{$cartInfo->qty ?? 0}}
            },@endforeach]
        }
    });
</script>
<script type="text/javascript">
    // Clear the previous ecommerce object.
    dataLayer.push({ ecommerce: null });

    // Push the begin_checkout event to dataLayer.
    dataLayer.push({
        event: "begin_checkout",
        ecommerce: {
            items: [@foreach (Cart::instance('shopping')->content() as $cartInfo)
                {
                    item_name: "{{$cartInfo->name}}",
                    item_id: "{{$cartInfo->id}}",
                    price: "{{$cartInfo->price}}",
                    item_brand: "{{$cartInfo->options->brands}}",
                    item_category: "{{$cartInfo->options->category}}",
                    item_size: "{{$cartInfo->options->size}}",
                    item_color: "{{$cartInfo->options->color}}",
                    currency: "BDT",
                    quantity: {{$cartInfo->qty ?? 0}}
                },
            @endforeach]
        }
    });
</script>
<script>
    $('.district').on('change',function(){
    var id = $(this).val();
        $.ajax({
           type:"GET",
           data:{'id':id},
           url:"{{route('districts')}}",
           success:function(res){               
            if(res){
                $(".area").empty();
                $(".area").append('<option value="">Select..</option>');
                $.each(res,function(key,value){
                    $(".area").append('<option value="'+value+'" >'+value+'</option>');
                });
           
            }else{
               $(".area").empty();
            }
           }
        });  
   });
   $("#area").on("change", function () {
        var id = $(this).val();
        var status = $('#savings_pay').is(':checked');
        $.ajax({
            type: "GET",
            data: { id: id,status:status },
            url: "{{route('shipping.charge')}}",
            dataType: "html",
            success: function(response){
               $(".cartlist").html(response);
                
            }
        });
    });
    $("#savings_pay").on("change", function () {
            var status = $(this).is(':checked');
            $.ajax({
                type: "GET",
                data: { status: status },
                url: "{{route('savings.pay')}}",
                dataType: "html",
                success: function(response){
                   $(".cartlist").html(response);
                //   console.log({{Session::get('savings_pay')}});
                //   if({{Session::get('savings_pay')}} == 0){
                //     toastr.error('failed', 'Savings amount is low');
                //   }
                }
            });
        });
</script>

<!--<script>-->
<!--      $(".district").on("change", function () {-->
<!--        var id = $(this).val();-->
<!--        console.log('data',id);-->
<!--        $.ajax({-->
<!--          type: "GET",-->
<!--          data: { id: id },-->
<!--          url: "{{route('districts')}}",-->
<!--          success: function (res) {-->
<!--            if (res) {-->
<!--              $(".area").empty();-->
<!--              $(".area").append('<option value="">Select..</option>');-->
<!--              $.each(res, function (key, value) {-->
<!--                $(".area").append('<option value="' + value + '" >' + value + "</option>");-->
<!--              });-->
<!--            } else {-->
<!--              $(".area").empty();-->
<!--            }-->
<!--          },-->
<!--        });-->
<!--      });-->
<!--      $("#area").on("change", function () {-->
<!--            var id = $(this).val();-->
<!--            var status = $('#savings_pay').is(':checked');-->
<!--            $.ajax({-->
<!--                type: "GET",-->
<!--                data: { id: id,status:status },-->
<!--                url: "{{route('shipping.charge')}}",-->
<!--                dataType: "html",-->
<!--                success: function(response){-->
<!--                   $(".cartlist").html(response);-->
                    
<!--                }-->
<!--            });-->
<!--        });-->
<!--      $("#savings_pay").on("change", function () {-->
<!--            var status = $(this).is(':checked');-->
<!--            $.ajax({-->
<!--                type: "GET",-->
<!--                data: { status: status },-->
<!--                url: "{{route('savings.pay')}}",-->
<!--                dataType: "html",-->
<!--                success: function(response){-->
<!--                   $(".cartlist").html(response);-->
<!--                   console.log({{Session::get('savings_pay')}});-->
<!--                   if({{Session::get('savings_pay')}} == 0){-->
<!--                    toastr.error('failed', 'Savings amount is low');-->
<!--                   }-->
<!--                }-->
<!--            });-->
<!--        });-->
<!--    </script>-->
@endpush
@endsection
