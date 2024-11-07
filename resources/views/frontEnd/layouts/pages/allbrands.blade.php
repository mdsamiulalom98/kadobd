@extends('frontEnd.layouts.master') 
@section('title','All Brands') 
@section('content')
<section class="product-section">
    <div class="container">
        <div class="sorting-section">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="category-breadcrumb d-flex align-items-center">
                            <a href="{{ route('home') }}">Home</a>
                            <span>/</span>
                            <strong>All Brands</strong>
                        </div>
                    </div>
                </div>
            </div>
        <div class="brand_product">
            <div class="row">
                @foreach($brands as $key=>$value)
                <div class="col-sm-2">
                    <div class="branditem-slider my-3">
                        <div class="category-item">
                            <a href="{{route('brand',$value->slug)}}">
                                <div class="category_img">
                                        <img src="{{asset($value->image)}}" alt="">
                                </div>
                                <div class="category_name">
                                    <p>{{$value->name}}</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection