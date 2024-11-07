@extends('frontEnd.layouts.master')
@section('title','Package Products')
@push('css')
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/jquery-ui.css') }}" />
@endpush
@push('seo')
    <meta name="app-url" content="route('packageproducts')" />
    <meta name="robots" content="index, follow" />
    <meta name="description" content="Unlock your potential, embrace the possibilities, and embark on a transformative journey of entrepreneurship with our dynamic MLM business." />
    <meta name="keywords" content="Package Products" />
    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product" />
    <meta name="twitter:site" content="Package Products" />
    <meta name="twitter:title" content="Package Products" />
    <meta name="twitter:description" content="Unlock your potential, embrace the possibilities, and embark on a transformative journey of entrepreneurship with our dynamic MLM business." />
    <meta name="twitter:creator" content="kadobd.com" />
    <meta property="og:url" content="route('packageproducts')" />
    <meta name="twitter:image" content="{{asset($generalsetting->white_logo)}}" />

    <!-- Open Graph data -->
    <meta property="og:title" content="Package Products" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="route('packageproducts')" />
    <meta property="og:image" content="{{asset($generalsetting->white_logo)}}" />
    <meta property="og:description" content="Unlock your potential, embrace the possibilities, and embark on a transformative journey of entrepreneurship with our dynamic MLM business." />
    <meta property="og:site_name" content="Package Products" />
@endpush
@section('content')
    <section class="product-section">
        <div class="container">
            <div class="sorting-section">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="category-breadcrumb d-flex align-items-center">
                            <a href="{{ route('home') }}">Home</a>
                            <span>/</span>
                            <strong>Package Products</strong>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="showing-data">
                                    <span>Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of
                                        {{ $products->total() }} Results</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="filter_sort">
                                    <div class="page-sort">
                                        <form action="" class="sort-form">
                                            <select name="sort" class="form-control form-select sort">
                                                <option value="1" @if(request()->get('sort')==1)selected @endif>Product: Latest</option>
                                                <option value="2" @if(request()->get('sort')==2)selected @endif>Product: Oldest</option>
                                                <option value="3" @if(request()->get('sort')==3)selected @endif>Price: High To Low</option>
                                                <option value="4" @if(request()->get('sort')==4)selected @endif>Price: Low To High</option>
                                                <option value="5" @if(request()->get('sort')==5)selected @endif>Name: A-Z</option>
                                                <option value="6" @if(request()->get('sort')==6)selected @endif>Name: Z-A</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-sm-3 filter_sidebar">
                    <div class="filter_close"><i class="fa fa-long-arrow-left"></i> Filter</div>
                    <form action="" class="attribute-submit">
                        <div class="sidebar_item wraper__item">
                            <div class="accordion" id="category_sidebar">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseCat" aria-expanded="true" aria-controls="collapseOne">
                                            Package Products
                                        </button>
                                    </h2>
                                    <div id="collapseCat" class="accordion-collapse collapse show"
                                        data-bs-parent="#category_sidebar">
                                        <div class="accordion-body cust_according_body">
                                            <ul>
                                                @foreach ($categories as $key => $category)
                                                    <li>
                                                        <a
                                                            href="{{ route('category',$category->slug) }}">{{ $category->name }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--sidebar item end-->
                        <div class="sidebar_item wraper__item">
                            <div class="accordion" id="price_sidebar">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapsePrice" aria-expanded="true" aria-controls="collapseOne">
                                            Price
                                        </button>
                                    </h2>
                                    <div id="collapsePrice" class="accordion-collapse collapse show"
                                        data-bs-parent="#price_sidebar">
                                        <div class="accordion-body cust_according_body">
                                            <div class="category-filter-box category__wraper" id="categoryFilterBox">
                                                <div class="category-filter-item">
                                                    <div class="filter-body">
                                                        <div class="slider-box">
                                                            <div class="filter-price-inputs">
                                                                <p class="min-price">৳<input type="text"
                                                                        name="min_price" id="min_price" readonly="" />
                                                                </p>
                                                                <p class="max-price">৳<input type="text"
                                                                        name="max_price" id="max_price" readonly="" />
                                                                </p>
                                                            </div>
                                                            <div id="price-range" class="slider form-attribute"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--sidebar item end-->
                    </form>
                </div>
                <div class="col-sm-9">
                    <div class="row">
                    @foreach ($products as $key => $value)
                    <div class="col-sm-3 col-6">
                         <div class="hotdeals-slider">            
                           <div class="product_item main_item wist_item wow zoomIn" data-wow-duration="1.5s" data-wow-delay="0.{{$key}}s">
                              <div class="product_item_inner">
                                  @if($value->old_price)

                                        @if($value->old_price)
                                       <div class="discount">
                                          <p> @php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp {{ number_format($discount, 0) }}% OFF</p>
                                                    
                                       </div>
                                       @endif
                                   @endif
                                 <div class="pro_img">
                                    <a href="{{route('product',$value->slug)}}">
                                        <img src="{{ asset($value->image ? $value->image->image : '') }}"
                                                alt="{{ $value->name }}" />
                                    </a>
                                    <div class="quick_wishlist">
                                       <button data-id="{{$value->id}}" class="hover-zoom wishlist_store" title="Wishlist"><i class="fa-regular fa-heart"></i></button>
                                    </div>
                                    <div class="product-size-wrapper pro_size{{$value->id}}"></div>
                                 </div>
                                 <div class="pro_des">
                                    <div class="pro_name">
                                       <a href="{{ route('product',$value->slug) }}">{{Str::limit($value->name,40)}}</a>
                                    </div>
                                     <div class="product_unit">
                                        <p>{{$value->pro_unit}}</p>
                                    </div>
                                    <div class="pro_price">
                                       <p>
                                          ৳ {{number_format($value->new_price,0)}}
                                       </p>
                                      @if($value->old_price)
                                       <p>
                                          <del>৳ {{number_format($value->old_price,0)}}</del>
                                       </p>
                                       @endif
                                    </div>
                                     @if($value->variables_count < 1 && $value->type == 1 || $value->quantity < 1 && $value->type == 0)
                                      <div class="Out_stock_btn">
                                         <li><a>Sold Out</a></li>
                                      </div>
                                      @else
                                      <div class="pro__btn">
                                        @if($value->variables_count < 1)
                                         <button  data-id="{{$value->id}}" class="add_to_cart">Add to cart</button>
                                         @else
                                         <a  href="{{ route('product',$value->slug)}}" class="add_to_cart">Add to cart</a>
                                         @endif
                                         <a class="view_deatils_btn" href="{{ route('product',$value->slug)}}">View details</a>
                                      </div>
                                     @endif
                                 </div>
                              </div>
                           </div>
                         </div>
                      </div>
                    @endforeach
                    </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="custom_paginate">
                        {{ $products->links('pagination::bootstrap-4') }}

                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="homeproduct">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="meta_des">
                        {!! $category->meta_description !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <script>
        $("#price-range").click(function() {
            $(".price-submit").submit();
        })
        $(".form-attribute").on('change click',function(){
            $(".attribute-submit").submit();
        })
        $(".sort").change(function() {
            $(".sort-form").submit();
        })
        $(".form-checkbox").change(function() {
            $(".subcategory-submit").submit();
        })
    </script>
    <script>
        $(function() {
            $("#price-range").slider({
                step: 5,
                range: true,
                min: {{ $min_price }},
                max: {{ $max_price }},
                values: [
                    {{ request()->get('min_price') ? request()->get('min_price') : $min_price }},
                    {{ request()->get('max_price') ? request()->get('max_price') : $max_price }}
                ],
                slide: function(event, ui) {
                    $("#min_price").val(ui.values[0]);
                    $("#max_price").val(ui.values[1]);
                }
            });
            $("#min_price").val({{ request()->get('min_price') ? request()->get('min_price') : $min_price }});
            $("#max_price").val({{ request()->get('max_price') ? request()->get('max_price') : $max_price }});
            $("#priceRange").val($("#price-range").slider("values", 0) + " - " + $("#price-range").slider("values",
                1));

            $("#mobile-price-range").slider({
                step: 5,
                range: true,
                min: {{ $min_price }},
                max: {{ $max_price }},
                values: [
                    {{ request()->get('min_price') ? request()->get('min_price') : $min_price }},
                    {{ request()->get('max_price') ? request()->get('max_price') : $max_price }}
                ],
                slide: function(event, ui) {
                    $("#min_price").val(ui.values[0]);
                    $("#max_price").val(ui.values[1]);
                }
            });
            $("#min_price").val({{ request()->get('min_price') ? request()->get('min_price') : $min_price }});
            $("#max_price").val({{ request()->get('max_price') ? request()->get('max_price') : $max_price }});
            $("#priceRange").val($("#price-range").slider("values", 0) + " - " + $("#price-range").slider("values",
                1));

        });
    </script>
@endpush
