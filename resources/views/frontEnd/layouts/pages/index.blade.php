@extends('frontEnd.layouts.master') 
@section('title','Unlock your potential, embrace the possibilities, and embark on a transformative journey of entrepreneurship with our dynamic MLM business.') 
@push('seo')
<meta name="app-url" content="" />
<meta name="robots" content="index, follow" />
<meta name="description" content="" />
<meta name="keywords" content=""  />

<!-- Open Graph data -->
<meta property="og:title" content="" />
<meta property="og:type" content="website" />
<meta property="og:url" content="" />
<meta property="og:image" content="{{asset($generalsetting->white_logo)}}" />
<meta property="og:description" content="" />
@endpush
@section('content')
<section class="slider-section">
    <div class="home-slider-container">
        <div class="main_slider owl-carousel">
            @foreach($sliders as $key=>$value)
            <div class="slider-item">
                <img src="{{asset($value->image)}}" alt="" />
            </div>
            <!-- slider item -->
            @endforeach
        </div>
    </div>
</section>
<!-- ============== -->
<div class="home-category">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="cat_title">
                    <h3>Category wise product</h3>
                </div>
                <div class="categoryitem-slider owl-carousel">
                @foreach($categories as $key=>$value)
                <div class="category-item">
                    <a href="{{route('category',$value->slug)}}">
                        <div class="category_img">
                            <img src="{{asset($value->image)}}" alt="">
                        </div>
                        <div class="category_name">
                            <p>{{$value->name}}</p>
                        </div>
                    </a>
                </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<div class="order-place">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <button class="youtube-link" youtubeid="{{$generalsetting->order_video}}"><i class="fa-solid fa-play"></i> How To Order Place From KadoBD? </button>
            </div>
        </div>
    </div>
</div>
<section class="homeproduct">
    <div class="container">
        <div class="row">
          <div class="col-sm-3">
            <div class="category-sidebar-wrapper">
             <div class="side__bar_category">
                <p><i class="fa-solid fa-list"></i>CATEGORIES</p>
             </div>
             <!-- ==== -->
             <div class="side__bar">
                <ul class="mtree transit">
                   @foreach($categories as $key=>$category)
                    <li @if($category->subcategories->count() > 0) class="hassubcategory" @endif>
                      <span @if($category->subcategories->count() > 0) @else style="display: none;" @endif><a href="javascript:void()"><i class="fa fa-plus"></i></a></span>
                      <a href="{{route('category',$category->slug)}}">{{$category->name}}</a>
                      <ul>
                        @foreach($category->subcategories as $subcategory)
                          <li ><a href="{{route('subcategory',$subcategory->slug)}}">{{ $subcategory->subcategoryName }}</a></li>
                        @endforeach
                      </ul>
                   </li>
                   @endforeach
                </ul>
             </div>
           </div>
          </div>
          <div class="col-sm-9">
            <div class="home_nav">
                 <ul>
                    <li><p>Package Product</p></li>
                     <li>
                         <a href="{{route('packageproducts')}}" class="view_all">View All <i class="fa-solid fa-angle-right"></i></a>
                     </li>
                 </ul>
              </div>
               <div class="product-inner">
                  <div class="row">
                   @foreach($packageproducts as $key=>$value)
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
              <!--package product end-->
              <div class="home_nav">
                 <ul>
                    <li><p>New Arrival</p></li>
                     <li>
                         <a href="{{route('newarrivals')}}" class="view_all">View All <i class="fa-solid fa-angle-right"></i></a>
                     </li>
                 </ul>
              </div>
               <div class="product-inner">
                  <div class="row">
                   @foreach($newarrivals as $key=>$value)
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
               <!-- new arrival end -->
              <div class="home_nav">
                 <ul>
                    <li><p>Discount Products</p></li>
                     <li>
                         <a href="{{route('offers')}}" class="view_all">View All <i class="fa-solid fa-angle-right"></i></a>
                     </li>
                 </ul>
              </div>
               <div class="product-inner">
                  <div class="row">
                   @foreach($discountproducts as $key=>$value)
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
                                       <a href="{{ route('product',$value->slug) }}">{{Str::limit($value->name,25)}}</a>
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
               <div class="home_nav">
                 <ul>
                    <li><p>Brand Wise Products</p></li>
                     <li>
                         <a href="{{route('allbrands')}}" class="view_all">View All <i class="fa-solid fa-angle-right"></i></a>
                     </li>
                 </ul>
              </div>
               <div class="brand_product">
                    <div class="branditem-slider owl-carousel">
                    @foreach($brands as $key=>$value)
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
                    @endforeach
                    </div>
               </div>   
        </div>
  </div>
    </div>
</section>
@if($popup_banner)
<!-- <section>
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
      </div>
      <div class="modal-body">
        <div class="my_modal_img">
            @foreach($popup_banner as $key=>$value)
            <img src="{{asset($value->image)}}">
            @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
</section> -->
@endif
@endsection 

@push('script')

<script src="{{asset('public/frontEnd/js/owl.carousel.min.js')}}"></script>

<script>
    $(document).ready(function () {
        $(".main_slider").owlCarousel({
            items: 1,
            loop: true,
            dots: false,
            autoplay: true,
            nav: false,
            autoplayHoverPause: false,
            margin: 0,
            mouseDrag: true,
            smartSpeed: 8000,
            autoplayTimeout: 3000,
            animateOut: "fadeOut",
            animateIn: "fadeIn",

            navText: ["<i class='fa-solid fa-angle-left'></i>", "<i class='fa-solid fa-angle-right'></i>"],
           
        });
    });
</script>

<script>
    $(document).ready(function () {
        $(".categoryitem-slider").owlCarousel({
            margin: 15,
            items:6,
            loop: true,
            dots: false,
            autoplay: true,
            nav: false,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            navText: ["<i class='fa-solid fa-angle-left'></i>", "<i class='fa-solid fa-angle-right'></i>"],

            responsive: {
                0: {
                    items: 2,
                    nav: false,
                    margin:8
                },
                600: {
                    items: 4,
                    nav: false,
                },
                1000: {
                    items: 6,
                    nav: false,
                },
            },

        });

    });
</script>
<script>
    $(document).ready(function () {
        $(".branditem-slider").owlCarousel({
            margin: 15,
            items:5,
            loop: true,
            dots: false,
            autoplay: true,
            nav: false,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 2,
                    nav: false,
                    margin:8
                },
                600: {
                    items: 4,
                    nav: false,
                },
                1000: {
                    items: 5,
                    nav: false,
                },
            },

        });

    });
</script>

<!-- sidebar -->
<script>
$(document).ready(function() {
  var mtree = $('ul.mtree');
  mtree.wrap('<div class=mtree-demo></div>');
  var skins = ['bubba','skinny','transit','jet','nix'];
  mtree.addClass(skins[0]);
  $('body').prepend('<div class="mtree-skin-selector"><ul class="button-group radius"></ul></div>');
  var s = $('.mtree-skin-selector');
  $.each(skins, function(index, val) {
    s.find('ul').append('<li><button class="small skin">' + val + '</button></li>');
  });
  s.find('ul').append('<li><button class="small csl active">Close Same Level</button></li>');
  s.find('button.skin').each(function(index){
    $(this).on('click.mtree-skin-selector', function(){
      s.find('button.skin.active').removeClass('active');
      $(this).addClass('active');
      mtree.removeClass(skins.join(' ')).addClass(skins[index]);
    });
  })
  s.find('button:first').addClass('active');
  s.find('.csl').on('click.mtree-close-same-level', function(){
    $(this).toggleClass('active'); 
  });

});
</script>
@if(request()->get('view')=='')
 <script type="text/javascript">
    $(window).on('load',function(){
        $('#myModal').modal('show');
    });
</script>
@endif
@endpush