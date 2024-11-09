@extends('frontEnd.layouts.master')
@section('title','Customer Register')
@section('content')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <div class="form-content">
                    <p class="auth-title">Customer Register</p>
                    <form action="{{route('customer.store')}}" method="POST"   class="row"  enctype="multipart/form-data"  data-parsley-validate="">
                        @csrf
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="refferal_id">Referral ID *</label>
                                <input type="text" id="refferal_id" class="form-control @error('refferal_id') is-invalid @enderror" @if(Session::get('refferal_id')) readonly @endif name="refferal_id" value="{{Session::get('refferal_id')?Session::get('refferal_id'):old('refferal_id')}}" placeholder="Refer ID" required>
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
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name')}}" placeholder="Enter full name" required>
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
                                <label for="phone"> Telegram / WhatsApp Number *</label>
                                 <input type="text" minlength="11" id="number" maxlength="11"
                                    pattern="0[0-9]+"
                                    title="please enter number only and 0 must first character"
                                    title="Please enter  11-digit number" id="phone"
                                    class="form-control @error('phone') is-invalid @enderror" name="phone"
                                    value="{{old('phone')}}"
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
                                <label for="bkash_no"> Bkash Number *</label>
                                 <input type="text" minlength="11" id="bkash_no" maxlength="11"
                                    pattern="0[0-9]+"
                                    title="please enter number only and 0 must first character"
                                    title="Please enter 11-digit bkash number" id="bkash_no"
                                    class="form-control @error('phone') is-invalid @enderror" name="bkash_no"
                                    value="{{old('bkash_no')}}"
                                    required />
                                @error('bkash_no')
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
                                <select  id="district" class="form-control select2 district @error('district') is-invalid @enderror" name="district"  required>
                                    <option value="">Select...</option>
                                    @foreach($districts as $key=>$district)
                                    <option value="{{$district->district}}" {{ old('district') == $district->district ? 'selected' : '' }} >{{$district->district}}</option>
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
                                <label for="area">Thana *</label>
                                <select  id="area" class="form-control area select2 @error('area') is-invalid @enderror" name="area" value="{{ old('area') }}"  required>
                                    <option value="">Select...</option>

                                </select>
                                @error('area')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->

                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="village_name">Village Name </label>
                                <input type="text" id="village_name" class="form-control @error('name') is-invalid @enderror" name="village_name" value="{{ old('village_name') }}" placeholder="Enter villege name" >
                                @error('village_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->

                        <!--<div class="col-sm-6">-->
                        <!--    <div class="form-group mb-3">-->
                        <!--        <label for="house">House/Flat *</label>-->
                        <!--        <input type="text" id="house" class="form-control @error('name') is-invalid @enderror" name="house" value="{{ old('house') }}" placeholder="Enter house/flat name" required>-->
                        <!--        @error('house')-->
                        <!--            <span class="invalid-feedback" role="alert">-->
                        <!--                <strong>{{ $message }}</strong>-->
                        <!--            </span>-->
                        <!--        @enderror-->
                        <!--    </div>-->
                        <!--</div>-->
                        <!-- col-end -->

                        <!--<div class="col-sm-6">-->
                        <!--    <div class="form-group mb-3">-->
                        <!--        <label for="road">Road *</label>-->
                        <!--        <input type="text" id="road" class="form-control @error('name') is-invalid @enderror" name="road" value="{{ old('road') }}" placeholder="Enter road name" required>-->
                        <!--        @error('road')-->
                        <!--            <span class="invalid-feedback" role="alert">-->
                        <!--                <strong>{{ $message }}</strong>-->
                        <!--            </span>-->
                        <!--        @enderror-->
                        <!--    </div>-->
                        <!--</div>-->
                        <!-- col-end -->

                        <!--<div class="col-sm-6">-->
                        <!--    <div class="form-group mb-3">-->
                        <!--        <label for="ward_no">Ward No (Optional)</label>-->
                        <!--        <input type="text" id="ward_no" class="form-control @error('name') is-invalid @enderror" name="ward_no" value="{{ old('ward_no') }}" placeholder="Enter ward_no name">-->
                        <!--        @error('ward_no')-->
                        <!--            <span class="invalid-feedback" role="alert">-->
                        <!--                <strong>{{ $message }}</strong>-->
                        <!--            </span>-->
                        <!--        @enderror-->
                        <!--    </div>-->
                        <!--</div>-->
                        <!-- col-end -->

                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="gender">Gender *</label>
                                <select  id="gender" class="form-control gender select2 @error('gender') is-invalid @enderror" name="gender" value="{{ old('gender') }}"  required>
                                   <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->

                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="age">Age *</label>
                                <input type="number" id="age" class="form-control @error('name') is-invalid @enderror" name="age" value="{{ old('age') }}" placeholder="Enter your age" required>
                                @error('age')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <!--<div class="col-sm-6">-->
                        <!--    <div class="form-group mb-3">-->
                        <!--        <label for="nid">NID Number *</label>-->
                        <!--        <input type="text" id="nid" class="form-control @error('name') is-invalid @enderror" name="nid" value="{{ old('nid') }}" placeholder="Enter your nid" required>-->
                        <!--        @error('nid')-->
                        <!--            <span class="invalid-feedback" role="alert">-->
                        <!--                <strong>{{ $message }}</strong>-->
                        <!--            </span>-->
                        <!--        @enderror-->
                        <!--    </div>-->
                        <!--</div>-->
                        <!-- col-end -->
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="password"> Password *</label>
                                <div class="password-input">
                                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror password" placeholder="Enter your password " name="password" value="{{ old('password')}}" minlength="6" maxlength="12" required>
                                    <span class="show-password-input"></span>
                                </div>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="confirm_password">Confirm Password *</label>
                                <input type="password" id="confirm_password" class="form-control @error('confirm_password') is-invalid @enderror password" placeholder="Enter  confirm password " name="confirm_password" value="{{ old('confirm_password') }}" minlength="6" maxlength="12" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->

                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="image">Image * (maxmimum 2MB photo upload)</label>
                                <input type="file" id="image" class="form-control @error('image') is-invalid @enderror" name="image" required>
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <!-- col-end -->
                        <div class="col-sm-12">
                            <div class="form-group mb-2" >
                              <div class="form-check">
                                <input class="form-check-input" name="agree" required type="checkbox" value="1" id="agree">
                                <label class="form-check-label" for="agree">
                                  I am agree with <a href="https://kadobd.com/page/terms-&-conditions" target="_blank">terms & conditions</a>
                                </label>
                              </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <button class="submit-btn" id="submit" disabled>Register</button>
                             <div class="register-now no-account">
                            <p><i class="fa-solid fa-user"></i> Aready have an account?</p>
                            <a href="{{route('customer.login')}}"><i data-feather="edit-3"></i> Login Here </a>
                        </div>
                        </div>
                        </div>
                     <!-- col-end -->


                     </form>
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
    $(document).ready(function(){
        $(".show-password-input").click(function(){
            $(this).toggleClass("active");
            var input = $('.password');
            console.log('input',input);
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
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
                    $(".area").append('<option value="'+key+'" >'+value+'</option>');
                });

            }else{
               $(".area").empty();
            }
           }
        });
   });
</script>
 <script>
    $('#agree').on('click', function() {
      $('#submit').prop('disabled', !this.checked);
    });
  </script>
@endpush
