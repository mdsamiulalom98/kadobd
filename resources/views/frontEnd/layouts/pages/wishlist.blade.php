@extends('frontEnd.layouts.master')
@section('title','Wishlist')
@section('content')
<section class="vcart-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="vcart-inner">
                    <div class="cart-title">
                        <h4>Wishlist</h4>
                    </div>
                    <div class="vcart-content" id="wishlist">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Cart</th>
                                        <th>Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $value)
                                    <tr>
                                        <td><img src="{{asset($value->options->image)}}" alt=""></td>
                                        <td>{{$value->name}}</td>
                                        <td>{{$value->qty}}</td>
                                        <td>{{ $value->price}}৳</td>
                                        <td><button  data-id="{{$value->id}}"  class="cart_store wcart-btn" ><i data-feather="shopping-cart"></i></button></td>
                                        <td><button class="remove-cart wishlist_remove" data-id="{{$value->rowId}}"><i data-feather="x"></i></button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
