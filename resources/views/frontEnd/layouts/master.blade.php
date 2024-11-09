<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title') - {{ $generalsetting->name }}</title>
    <!-- App favicon -->

    <link rel="shortcut icon" href="{{ asset($generalsetting->favicon) }}" />
    <meta name="author" content="Kadobd" />
    <link rel="canonical" href="" />
    @stack('seo')
    @stack('css')
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/owl.theme.default.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/mobile-menu.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/select2.min.css') }}" />
    <!-- toastr css -->
    <link rel="stylesheet" href="{{ asset('public/backEnd/') }}/assets/css/toastr.min.css" />

    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/wsit-menu.css') }}" />

    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/grt-youtube-popup.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/mtree.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/style.css?v=1.0.6') }}" />
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/responsive.css?v=1.0.1') }}" />

    <meta name="facebook-domain-verification" content="38f1w8335btoklo88dyfl63ba3st2e" />


    @foreach ($pixels as $pixel)
        <!-- Facebook Pixel Code -->
        <script>
            !(function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = "2.0";
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s);
            })(window, document, "script", "https://connect.facebook.net/en_US/fbevents.js");
            fbq("init", "{{ $pixel->code }}");
            fbq("track", "PageView");
        </script>
        <noscript>
            <img height="1" width="1" style="display: none;"
                src="https://www.facebook.com/tr?id={{ $pixel->code }}&ev=PageView&noscript=1" />
        </noscript>
        <!-- End Facebook Pixel Code -->
    @endforeach
    <!--Google Tag-->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-5VFJZCL');
    </script>
    <!-- End Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-5VFJZCL');
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id="></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', '');
    </script>

</head>

<body class="gotop">
    <div class="content_gap"></div>
    @php $subtotal = Cart::instance('shopping')->subtotal(); @endphp
    <div class="mobile-menu no-print ">
        <div class="mobile-menu-logo">
            <div class="logo-image">
                <img src="{{ asset($generalsetting->white_logo) }}" alt="" />
            </div>
            <div class="mobile-menu-close">
                <i class="fa fa-times"></i>
            </div>
        </div>
        <ul class="first-nav">
            @foreach ($categories as $scategory)
                <li class="parent-category">
                    <a href="{{ url('category/' . $scategory->slug) }}" class="menu-category-name">
                        <img src="{{ asset($scategory->image) }}" alt="" class="side_cat_img" />
                        {{ $scategory->name }}
                    </a>
                    @if ($scategory->subcategories->count() > 0)
                        <span class="menu-category-toggle">
                            <i class="fa fa-chevron-down"></i>
                        </span>
                    @endif
                    <ul class="second-nav" style="display: none;">
                        @foreach ($scategory->subcategories as $subcategory)
                            <li class="parent-subcategory">
                                <a href="{{ url('subcategory/' . $subcategory->slug) }}"
                                    class="menu-subcategory-name">{{ $subcategory->subcategoryName }}</a>
                                @if ($subcategory->childcategories->count() > 0)
                                    <span class="menu-subcategory-toggle"><i class="fa fa-chevron-down"></i></span>
                                @endif
                                <ul class="third-nav" style="display: none;">
                                    @foreach ($subcategory->childcategories as $childcat)
                                        <li class="childcategory"><a href="{{ url('products/' . $childcat->slug) }}"
                                                class="menu-childcategory-name">{{ $childcat->childcategoryName }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>

    <header>
        <div class="mobile-header sticky">
            <div class="mobile-logo">
                <div class="menu-bar">
                    <a class="toggle">
                        <i class="fa-solid fa-bars"></i>
                    </a>
                </div>
                <div class="menu-logo">
                    <a href="{{ route('home') }}"><img src="{{ asset($generalsetting->white_logo) }}"
                            alt="" /></a>
                </div>
                <div class="menu-bag">

                    <p class="margin-shopping">
                        <a href="{{ route('wishlist.show') }}">
                            <i class="fa-solid fa-heart"></i>
                            <span class="wishlist_qty">{{ Cart::instance('wishlist')->count() }}</span>
                        </a>
                    </p>
                    <p class="margin-shopping">
                        @if (Auth::guard('customer')->user())
                            <a href="{{ route('customer.account') }}">
                                <i class="fa-solid fa-user"></i>
                            </a>
                        @else
                            <a href="{{ route('customer.login') }}">
                                <i class="fa-solid fa-user"></i>
                            </a>
                        @endif
                    </p>

                </div>
            </div>
        </div>
        <div class="mobile-search">
            <form action="{{ route('search') }}">
                <input type="text" placeholder="Search Product..." value="" class="search_keyword search_click"
                    name="keyword" />
                <button><i data-feather="search"></i></button>
            </form>
            <div class="search_result"></div>
        </div>

        <div id="navbar_top" class="main-header">
            <div class="header_top">
                <div class="container">
                    <div class="row">
                        <div class="header_top_data">
                            <div class="header_number">
                                <a href="tel:{{ $contact->hotline }}"><i class="fa-solid fa-phone"></i>
                                    {{ $contact->hotline }}</a>
                            </div>
                            <div class="header_social">
                                <ul class="social_link social_head">
                                    @foreach ($socialicons as $value)
                                        <li class="social_list">
                                            <a class="mobile-social-link" href="{{ $value->link }}"
                                                style="background:{{ $value->color }}"><i
                                                    class="{{ $value->icon }}"></i></a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- header to end -->
            <div class="logo-area">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="logo-header">
                                <div class="main-logo">
                                    <a href="{{ route('home') }}"><img
                                            src="{{ asset($generalsetting->white_logo) }}" alt="" /></a>
                                </div>
                                <div class="main-search">
                                    <form action="{{ route('search') }}">
                                        <input type="text" placeholder=" Search Product ..." value=""
                                            class="search_keyword search_click" name="keyword" />
                                        <button><i class="fa fa-search"></i></button>
                                    </form>
                                    <div class="search_result"></div>
                                </div>
                                <div class="cart_n_login">
                                    <div class="header-list-items">
                                        <ul>
                                            <li>
                                                <a href="{{ route('wishlist.show') }}">
                                                    <p class="margin-shopping">
                                                        <i class="fa-solid fa-heart"></i>
                                                        <span
                                                            class="wishlist_qty">{{ Cart::instance('wishlist')->count() }}</span>
                                                    </p>
                                                </a>
                                            </li>
                                            <li class="cart-dialog" id="cart-qty">
                                                <a href="{{ route('customer.checkout') }}">
                                                    <p class="margin-shopping">
                                                        <i class="fa-solid fa-cart-shopping"></i>
                                                        <span>{{ Cart::instance('shopping')->count() }}</span>

                                                    </p>
                                                </a>
                                                <div class="cshort-summary">
                                                    <ul>
                                                        @foreach (Cart::instance('shopping')->content() as $key => $value)
                                                            <li>
                                                                <a href=""><img
                                                                        src="{{ asset($value->options->image) }}"
                                                                        alt="" /></a>
                                                            </li>
                                                            <li><a>{{ $value->name }}</a>
                                                                @if ($value->options->product_size)
                                                                    <p class="d-block">Size:
                                                                        {{ $value->options->product_size }}</p>
                                                                @endif
                                                            </li>
                                                            <li>Qty: {{ $value->qty }}</li>
                                                            <li>
                                                                <p>৳{{ $value->price }}</p>
                                                                <button class="remove-cart cart_remove"
                                                                    data-id="{{ $value->rowId }}"><i
                                                                        data-feather="x"></i></button>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    <p><strong>Subtotal : ৳{{ $subtotal }}</strong></p>
                                                    <a href="{{ route('customer.checkout') }}" class="go_cart"> Go To
                                                        Checkout</a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="header_btn">
                                        @if (Auth::guard('customer')->user())
                                            <li class="for_order">
                                                <a href="{{ route('customer.account') }}">
                                                    <i class="fa-solid fa-user"></i>
                                                    Account
                                                </a>
                                            </li>
                                        @else
                                            <li class="for_order inactive_signin_btn">
                                                <a href="{{ route('customer.login') }}">
                                                    Sign in
                                                </a>
                                            </li>
                                            <li class="for_order">
                                                <a href="{{ route('customer.register') }}">
                                                    Register
                                                </a>
                                            </li>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- main-header end -->
        </div>


    </header>
    <div id="content">
        @yield('content')
        <!-- content end -->
        <footer>
            <div class="footer-top">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4 mb-3 mb-sm-0">
                            <div class="footer-about">
                                <a href="{{ route('home') }}">
                                    <img src="{{ asset($generalsetting->white_logo) }}" alt="" />
                                </a>
                                <p>{{ $contact->address }}</p>
                                <a href="tel:{{ $contact->hotline }}"
                                    class="footer-hotlint">{{ $contact->hotline }}</a>
                            </div>
                        </div>
                        <!-- col end -->
                        <div class="col-sm-3 mb-3 mb-sm-0 col-6">
                            <div class="footer-menu">
                                <ul>
                                    <li class="title"><a>Important Link</a></li>
                                    <li>
                                        <a href="{{ route('contact') }}"> <a href="{{ route('contact') }}">Contact
                                                Us</a></a>
                                    </li>
                                    @foreach ($pages as $page)
                                        <li><a
                                                href="{{ route('page', ['slug' => $page->slug]) }}">{{ $page->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <!-- col end -->
                        <div class="col-sm-2 mb-3 mb-sm-0 col-6">
                            <div class="footer-menu">
                                <ul>
                                    <li class="title"><a>User Guide</a></li>
                                    @foreach ($pagesright as $key => $value)
                                        <li>
                                            <a
                                                href="{{ route('page', ['slug' => $value->slug]) }}">{{ $value->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <!-- col end -->
                        <div class="col-sm-3 mb-3 mb-sm-0">
                            <div class="footer-menu">
                                <ul>
                                    <li class="title stay_conn"><a>Stay Connected</a></li>
                                </ul>
                                <ul class="social_link">
                                    @foreach ($socialicons as $value)
                                        <li class="social_list">
                                            <a class="mobile-social-link" href="{{ $value->link }}"><i
                                                    class="{{ $value->icon }}"></i></a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="d_app">
                                    <h2>Download App</h2>
                                    <a href="">
                                        <img src="{{ asset('public/frontEnd/images/app-download.png') }}"
                                            alt="" />
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- col end -->
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="copyright">
                                <p>Copyright © {{ date('Y') }} {{ $generalsetting->name }}. All rights reserved
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <div class="footer_nav">
            <ul>
                <li>
                    <a class="toggle">
                        <span>
                            <i class="fa-solid fa-bars"></i>
                        </span>
                        <span>Category</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('home') }}">
                        <span><i class="fa-solid fa-home"></i></span> <span>Home</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('customer.checkout') }}">
                        <span>
                            <i class="fa-solid fa-cart-shopping"></i>
                        </span>
                        <span id="mobilecart-qty">Cart ({{ Cart::instance('shopping')->count() }})</span>
                    </a>
                </li>
                @if (Auth::guard('customer')->user())
                    <li>
                        <a href="{{ route('customer.account') }}">
                            <span>
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <span>Account</span>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('customer.login') }}">
                            <span>
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <span>Login</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="scrolltop" style="">
        <div class="scroll">
            <i class="fa fa-angle-up"></i>
        </div>
    </div>


    <!-- /. fixed sidebar -->

    <div id="custom-modal"></div>
    <div id="page-overlay"></div>
    <div id="loading">
        <div class="custom-loader"></div>
    </div>

    <script src="{{ asset('public/frontEnd/js/jquery-3.6.3.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/mobile-menu.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/wsit-menu.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/mobile-menu-init.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/wow.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/jquery.velocity.min.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/mtree.js') }}"></script>
    <script src="{{ asset('public/frontEnd/js/grt-youtube-popup.js') }}"></script>
    <script>
        $(".youtube-link").grtyoutube();
    </script>

    <script>
        new WOW().init();
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- feather icon -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
    <script>
        feather.replace();
    </script>
    <script src="{{ asset('public/backEnd/') }}/assets/js/toastr.min.js"></script>
    {!! Toastr::message() !!}
    @stack('script')
    <script>
        $(".quick_view").on("click", function() {
            var id = $(this).data("id");
            $("#loading").show();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('quickview') }}",
                    success: function(data) {
                        if (data) {
                            $("#custom-modal").html(data);
                            $("#custom-modal").show();
                            $("#loading").hide();
                            $("#page-overlay").show();
                        }
                    },
                });
            }
        });
    </script>
    <!-- quick view end -->
    <!-- cart js start -->
    <script>
        $('.add_to_cart').on('click', function() {
            var id = $(this).data('id');
            var qty = 1;
            $("#loading").show();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        'id': id,
                        'qty': qty ? qty : 1
                    },
                    url: "{{ route('add_to_cart') }}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            $("#loading").hide();
                            return cart_count() + mobile_cart() + cart_summary() + show_cart();
                        }
                    }
                });
            }
        });
        $(".cart_remove").on("click", function() {
            var id = $(this).data("id");
            $("#loading").show();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('cart.remove') }}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            $("#loading").hide();
                            return cart_count() + mobile_cart() + cart_summary() + show_cart();
                        }
                    },
                });
            }
        });
        $(".cart_increment").on("click", function() {
            var id = $(this).data("id");
            $("#loading").show();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('cart.increment') }}",
                    success: function(res) {
                        if (res) {
                            $(".cartlist").html(res);
                            $("#loading").hide();
                            if (res.status == 'over') {
                                toastr.error('failed', 'Product limit is over');
                            }
                            return cart_count() + mobile_cart() + cart_summary() + show_cart();
                        }
                    },
                });
            }
        });

        $(".cart_decrement").on("click", function() {
            var id = $(this).data("id");
            $("#loading").show();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id
                    },
                    url: "{{ route('cart.decrement') }}",
                    success: function(data) {
                        if (data) {
                            $(".cartlist").html(data);
                            $("#loading").hide();
                            return cart_count() + mobile_cart() + cart_summary() + show_cart();
                        }
                    },
                });
            }
        });

        function cart_count() {
            $.ajax({
                type: "GET",
                url: "{{ route('cart.count') }}",
                success: function(data) {
                    if (data) {
                        $("#cart-qty").html(data);
                    } else {
                        $("#cart-qty").empty();
                    }
                },
            });
        }

        function mobile_cart() {
            $.ajax({
                type: "GET",
                url: "{{ route('mobile.cart.count') }}",
                success: function(data) {
                    if (data) {
                        $("#mobilecart-qty").html(data);
                    } else {
                        $("#mobilecart-qty").empty();
                    }
                },
            });
        }

        function cart_summary() {
            $.ajax({
                type: "GET",
                url: "{{ route('shipping.charge') }}",
                dataType: "html",
                success: function(response) {
                    $(".cart-summary").html(response);
                },
            });
        }
    </script>
    <!-- cart js end -->
    <script>
        $(".search_click").on("keyup change", function() {
            var keyword = $(".search_keyword").val();
            $.ajax({
                type: "GET",
                data: {
                    keyword: keyword
                },
                url: "{{ route('livesearch') }}",
                success: function(products) {
                    if (products) {
                        $("#loading").hide();
                        $(".search_result").html(products);
                    } else {
                        $(".search_result").empty();
                    }
                },
            });
        });
        $(".msearch_click").on("keyup change", function() {
            var keyword = $(".msearch_keyword").val();
            $.ajax({
                type: "GET",
                data: {
                    keyword: keyword
                },
                url: "{{ route('livesearch') }}",
                success: function(products) {
                    if (products) {
                        $("#loading").hide();
                        $(".search_result").html(products);
                    } else {
                        $(".search_result").empty();
                    }
                },
            });
        });
    </script>
    <!-- search js start -->
    <script>
        $(".toggle").on("click", function() {
            $("#page-overlay").show();
            $(".mobile-menu").addClass("active");
        });

        $("#page-overlay").on("click", function() {
            $("#page-overlay").hide();
            $(".mobile-menu").removeClass("active");
            $(".feature-products").removeClass("active");
        });

        $(".mobile-menu-close").on("click", function() {
            $("#page-overlay").hide();
            $(".mobile-menu").removeClass("active");
        });

        $(".mobile-filter-toggle").on("click", function() {
            $("#page-overlay").show();
            $(".feature-products").addClass("active");
        });

        $(".toggle2").on("click", function() {
            $("#page-overlay").show();
            $(".mobile_category").addClass("active");
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".parent-category").each(function() {
                const menuCatToggle = $(this).find(".menu-category-toggle");
                const secondNav = $(this).find(".second-nav");

                menuCatToggle.on("click", function() {
                    menuCatToggle.toggleClass("active");
                    secondNav.slideToggle("slow");
                    $(this).closest(".parent-category").toggleClass("active");
                });
            });
            $(".parent-subcategory").each(function() {
                const menuSubcatToggle = $(this).find(".menu-subcategory-toggle");
                const thirdNav = $(this).find(".third-nav");

                menuSubcatToggle.on("click", function() {
                    menuSubcatToggle.toggleClass("active");
                    thirdNav.slideToggle("slow");
                    $(this).closest(".parent-subcategory").toggleClass("active");
                });
            });
        });
    </script>

    <script>
        var menu = new MmenuLight(document.querySelector("#menu"), "all");

        var navigator = menu.navigation({
            selectedClass: "Selected",
            slidingSubmenus: true,
            // theme: 'dark',
            title: "Categories",
        });

        var drawer = menu.offcanvas({
            // position: 'left'
        });
        document.querySelector('a[href="#menu"]').addEventListener("click", (evnt) => {
            evnt.preventDefault();
            drawer.open();
        });
    </script>

    <script>
        $('.wishlist_store').on('click', function() {
            var id = $(this).data('id');
            var qty = 1;
            $("#loading").show();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        'id': id,
                        'qty': qty ? qty : 1
                    },
                    url: "{{ route('wishlist.store') }}",
                    success: function(data) {
                        if (data) {
                            $("#loading").hide();
                            toastr.success('success', 'Product added in wishlist');
                            return wishlist_count();
                        }
                    }
                });
            }
        });

        $('.wishlist_remove').on('click', function() {
            var id = $(this).data('id');
            $("#loading").show();
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        'id': id
                    },
                    url: "{{ route('wishlist.remove') }}",
                    success: function(data) {
                        if (data) {
                            $(".wishlist_qty").html(data);
                            $("#loading").hide();
                            return wishlist_count();
                        }
                    }
                });
            }
        });

        function wishlist_count() {
            $.ajax({
                type: "GET",
                url: "{{ route('wishlist.count') }}",
                success: function(data) {
                    if (data) {
                        $(".wishlist_qty").html(data);
                    } else {
                        $(".wishlist_qty").empty();
                    }
                }
            });
        };
    </script>

    <script>
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $('.scrolltop:hidden').stop(true, true).fadeIn();
            } else {
                $('.scrolltop').stop(true, true).fadeOut();
            }
        });
        $(function() {
            $(".scroll").click(function() {
                $("html,body").animate({
                    scrollTop: $(".gotop").offset().top
                }, "1000");
                return false
            })
        })
    </script>

    <script>
        $(document).ready(function() {
            var mtree = $('ul.mtree');
            mtree.wrap('<div class=mtree-demo></div>');
            var skins = ['bubba', 'skinny', 'transit', 'jet', 'nix'];
            mtree.addClass(skins[0]);
            $('body').prepend('<div class="mtree-skin-selector"><ul class="button-group radius"></ul></div>');
            var s = $('.mtree-skin-selector');
            $.each(skins, function(index, val) {
                s.find('ul').append('<li><button class="small skin">' + val + '</button></li>');
            });
            s.find('ul').append('<li><button class="small csl active">Close Same Level</button></li>');
            s.find('button.skin').each(function(index) {
                $(this).on('click.mtree-skin-selector', function() {
                    s.find('button.skin.active').removeClass('active');
                    $(this).addClass('active');
                    mtree.removeClass(skins.join(' ')).addClass(skins[index]);
                });
            })
            s.find('button:first').addClass('active');
            s.find('.csl').on('click.mtree-close-same-level', function() {
                $(this).toggleClass('active');
            });

        });
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5VFJZCL" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <!-- Messenger Chat Plugin Code -->
    <div id="fb-root"></div>
    <!-- Your Chat Plugin code -->
    <div id="fb-customer-chat" class="fb-customerchat">
    </div>
    <script>
        var chatbox = document.getElementById('fb-customer-chat');
        chatbox.setAttribute("page_id", "1097011787018415");
        chatbox.setAttribute("attribution", "biz_inbox");
    </script>

    <!-- Your SDK code -->
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                xfbml: true,
                version: 'v16.0'
            });
        };

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <script>
        $(".filter_btn").click(function() {
            $(".filter_sidebar").addClass('active');
            $("body").css("overflow-y", "hidden");
        })
        $(".filter_close").click(function() {
            $(".filter_sidebar").removeClass('active');
            $("body").css("overflow-y", "auto");
        })
    </script>
</body>

</html>
