@extends('frontEnd.layouts.master')
@section('title','Customer Login')
@section('content')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-5">
                <div class="form-content">
                    <p class="auth-title"> Customer Login</p>
                    <form action="{{route('customer.signin')}}" method="POST"  data-parsley-validate="">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="phone">Mobile Number</label>
                            <input type="number" id="phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}"  required>
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <!-- col-end -->
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <div class="password-input">
                                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror password" placeholder="Enter your password " name="password" value="{{ old('password') }}" required>
                                <span class="show-password-input"></span>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <!-- col-end -->
                        <a href="{{route('customer.forgot.password')}}" class="forget-link"><i class="fa-solid fa-unlock"></i> Forgot password?</a>
                        <div class="form-group mb-3">
                            <button class="submit-btn"> Login </button>
                        </div>
                     <!-- col-end -->
                     </form>
                     <div class="register-now no-account">
                        <p> Have no account? create here</p>
                        <a href="{{route('customer.register')}}"><i data-feather="edit-3"></i> Register Now</a>
                    </div>
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
@endpush