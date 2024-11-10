<?php

namespace App\Http\Controllers\Frontend;

use shurjopayv2\ShurjopayLaravelPackage8\Http\Controllers\ShurjopayController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Childcategory;
use App\Models\Product;
use App\Models\District;
use App\Models\Thana;
use App\Models\CreatePage;
use App\Models\Campaign;
use App\Models\Banner;
use App\Models\ShippingCharge;
use App\Models\Customer;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Review;
use App\Models\ProductVariable;
use App\Models\Brand;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        $popup_banner = Banner::where(['status' => 1, 'category_id' => 2])
            ->select('id', 'image', 'link')
            ->limit(1)
            ->get();

        $sliders = Banner::where(['status' => 1, 'category_id' => 1])
            ->select('id', 'image', 'link')
            ->get();

        $packageproducts = Product::where(['status' => 1, 'topsale' => 1])
            ->orderBy('id', 'DESC')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'quantity', 'topsale', 'type', 'pro_unit')
            ->withCount('variables')
            ->limit(8)
            ->get();

        $newarrivals = Product::where(['status' => 1])
            ->orderBy('id', 'DESC')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'quantity', 'topsale', 'type', 'pro_unit')
            ->withCount('variables')
            ->limit(8)
            ->get();
        // return $newarrivals;

        $discountproducts = Product::where(['status' => 1, 'topsale' => 0])
            ->orderBy('id', 'DESC')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'quantity', 'topsale', 'type', 'pro_unit')
            ->whereNotNull('old_price')
            ->withCount('variables')
            ->limit(8)
            ->get();
        // return $discountproducts;

        if ($request->r) {
            Session::put('refferal_id', $request->r);
            return redirect()->route('customer.register');
        }
        return view('frontEnd.layouts.pages.index', compact('sliders', 'newarrivals', 'discountproducts', 'popup_banner', 'packageproducts'));
    }

    public function packageproducts(Request $request)
    {
        $products = Product::where(['status' => 1, 'topsale' => 1])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'quantity', 'topsale', 'type', 'pro_unit')
            ->withCount('variables');
        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }
        $products = $products->paginate(24);

        $categories = Category::where('status', 1)->get();
        return view('frontEnd.layouts.pages.packageproducts', compact('products', 'min_price', 'max_price', 'categories'));
    }

    public function newarrivals(Request $request)
    {
        $products = Product::where(['status' => 1])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'quantity', 'topsale', 'type', 'pro_unit')
            ->withCount('variables');
        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }
        $products = $products->paginate(24);

        $categories = Category::where('status', 1)->get();
        return view('frontEnd.layouts.pages.newarrivals', compact('products', 'min_price', 'max_price', 'categories'));
    }
    public function offers(Request $request)
    {
        $products = Product::where(['status' => 1, 'topsale' => 0])
            ->whereNotNull('old_price')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'quantity', 'topsale', 'type', 'pro_unit')
            ->withCount('variables');
        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }
        $categories = Category::where('status', 1)->get();
        $products = $products->paginate(24);
        return view('frontEnd.layouts.pages.offers', compact('products', 'min_price', 'max_price', 'categories'));
    }

    public function category($slug, Request $request)
    {
        $category = Category::where(['slug' => $slug, 'status' => 1])->first();
        $products = Product::where(['status' => 1, 'category_id' => $category->id, 'topsale' => 0])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'category_id', 'quantity', 'type', 'pro_unit')
            ->withCount('variables');
        $subcategories = Subcategory::where('category_id', $category->id)->get();

        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }
        $products = $products->paginate(24);
        return view('frontEnd.layouts.pages.category', compact('category', 'products', 'subcategories', 'min_price', 'max_price'));
    }

    public function subcategory($slug, Request $request)
    {
        $subcategory = Subcategory::where(['slug' => $slug, 'status' => 1])->first();
        $products = Product::where(['status' => 1, 'subcategory_id' => $subcategory->id, 'topsale' => 0])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'subcategory_id', 'quantity', 'type', 'pro_unit')
            ->withCount('variables');
        $childcategories = Childcategory::where('subcategory_id', $subcategory->id)->get();

        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }

        $products = $products->paginate(24);

        return view('frontEnd.layouts.pages.subcategory', compact('subcategory', 'products', 'childcategories', 'max_price', 'min_price'));
    }

    public function products($slug, Request $request)
    {
        $childcategory = Childcategory::where(['slug' => $slug, 'status' => 1])->first();
        $childcategories = Childcategory::where('subcategory_id', $childcategory->subcategory_id)->get();

        $products = Product::where(['status' => 1, 'childcategory_id' => $childcategory->id, 'topsale' => 0])->with('category')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'childcategory_id', 'quantity', 'type', 'pro_unit')
            ->withCount('variables');
        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }

        $products = $products->paginate(24);

        return view('frontEnd.layouts.pages.childcategory', compact('childcategory', 'products', 'min_price', 'max_price', 'childcategories'));
    }


    public function details($slug)
    {
        $details = Product::where(['slug' => $slug, 'status' => 1])
            ->with('image', 'images', 'category', 'subcategory', 'childcategory')
            ->withCount('reviews', 'variables')
            ->firstOrFail();

        $products = Product::where(['category_id' => $details->category_id, 'status' => 1, 'topsale' => 0])
            ->with('image')
            ->withCount('variables')
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'quantity', 'type', 'pro_unit')
            ->get();
        $shippingcharge = ShippingCharge::where(['status' => 1, 'pos' => 0])->get();
        $reviews = Review::where(['product_id' => $details->id, 'status' => 'active'])->get();
        $productcolors = ProductVariable::where('product_id', $details->id)->where('stock', '>', 0)
            ->select('color')
            ->distinct()
            ->get();
        // return $productcolors;
        $productsizes = ProductVariable::where('product_id', $details->id)->where('stock', '>', 0)
            ->select('size')
            ->distinct()
            ->get();

        $one_star = Review::where(['product_id' => $details->id, 'status' => 'active', 'ratting' => 1])->select('product_id', 'status', 'ratting', 'id', 'customer_id')->get();
        $two_star = Review::where(['product_id' => $details->id, 'status' => 'active', 'ratting' => 2])->select('product_id', 'status', 'ratting', 'id', 'customer_id')->get();
        $three_star = Review::where(['product_id' => $details->id, 'status' => 'active', 'ratting' => 3])->select('product_id', 'status', 'ratting', 'id', 'customer_id')->get();
        $four_star = Review::where(['product_id' => $details->id, 'status' => 'active', 'ratting' => 4])->select('product_id', 'status', 'ratting', 'id', 'customer_id')->get();
        $five_star = Review::where(['product_id' => $details->id, 'status' => 'active', 'ratting' => 5])->select('product_id', 'status', 'ratting', 'id', 'customer_id')->get();
        $total_review = Review::where(['product_id' => $details->id, 'status' => 'active'])->sum('ratting');
        return view('frontEnd.layouts.pages.details', compact('details', 'products', 'shippingcharge', 'productcolors', 'productsizes', 'reviews', 'one_star', 'two_star', 'three_star', 'four_star', 'five_star', 'total_review'));
    }
    public function stock_check(Request $request)
    {
        $product = ProductVariable::where(['product_id' => $request->id, 'color' => $request->color, 'size' => $request->size])->first();

        return response()->json($product ? intval($product->stock) : 0);
    }
    public function quickview(Request $request)
    {
        $data['data'] = Product::where(['id' => $request->id, 'status' => 1])->with('images')->withCount('reviews')->first();
        $data = view('frontEnd.layouts.ajax.quickview', $data)->render();
        if ($data != '') {
            echo $data;
        }
    }
    public function livesearch(Request $request)
    {
        $products = Product::select('id', 'name', 'slug', 'new_price', 'old_price')
            ->where('status', 1)
            ->with('image');
        if ($request->keyword) {
            $products = $products->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        if ($request->category) {
            $products = $products->where('category_id', $request->category);
        }
        $products = $products->get();

        if (empty($request->category) && empty($request->keyword)) {
            $products = [];
        }
        return view('frontEnd.layouts.ajax.search', compact('products'));
    }
    public function search(Request $request)
    {
        $products = Product::where(['status' => 1])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'quantity', 'topsale', 'type', 'pro_unit')
            ->withCount('variables');

        if ($request->keyword) {
            $products = $products->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }
        $products = $products->paginate(24);

        $keyword = $request->keyword;
        return view('frontEnd.layouts.pages.search', compact('products', 'keyword', 'min_price', 'max_price'));
    }
    public function allbrands()
    {
        $brands = Brand::select('id', 'name', 'slug', 'image', 'status')->orderBy('name', 'asc')->get();
        return view('frontEnd.layouts.pages.allbrands', compact('brands'));
    }
    public function brand(Request $request, $slug)
    {
        $brand = Brand::select('id', 'name', 'slug', 'status')->where(['slug' => $slug, 'status' => 1])->first();
        $products = Product::where(['status' => 1, 'brand_id' => $brand->id])
            ->select('id', 'name', 'slug', 'new_price', 'old_price', 'quantity', 'topsale', 'type', 'pro_unit')
            ->withCount('variables');
        // return $request->sort;
        if ($request->sort == 1) {
            $products = $products->orderBy('created_at', 'desc');
        } elseif ($request->sort == 2) {
            $products = $products->orderBy('created_at', 'asc');
        } elseif ($request->sort == 3) {
            $products = $products->orderBy('new_price', 'desc');
        } elseif ($request->sort == 4) {
            $products = $products->orderBy('new_price', 'asc');
        } elseif ($request->sort == 5) {
            $products = $products->orderBy('name', 'asc');
        } elseif ($request->sort == 6) {
            $products = $products->orderBy('name', 'desc');
        } else {
            $products = $products->latest();
        }

        $min_price = $products->min('new_price');
        $max_price = $products->max('new_price');
        if ($request->min_price && $request->max_price) {
            $products = $products->where('new_price', '>=', $request->min_price);
            $products = $products->where('new_price', '<=', $request->max_price);
        }
        $products = $products->paginate(24);
        $keyword = $request->keyword;
        return view('frontEnd.layouts.pages.brand', compact('products', 'keyword', 'min_price', 'max_price', 'brand'));
    }

    public function shipping_charge(Request $request)
    {
        if ($request->id == NULL) {
            Session::put('shipping', 0);
        } else {
            $shipping = District::where(['area_name' => $request->id])->first();
            Session::put('shipping', $shipping->shippingfee);
        }

        $subtotal = Cart::instance('shopping')->subtotal();
        $subtotal = str_replace(',', '', $subtotal);
        $subtotal = str_replace('.00', '', $subtotal);
        $shipping = Session::get('shipping') ? Session::get('shipping') : 0;
        $discount = Session::get('discount') ? Session::get('discount') : 0;
        $coupon = Session::get('coupon_amount') ? Session::get('coupon_amount') : 0;
        $total = $subtotal + $shipping - ($discount + $coupon);

        $savings_amount = auth()->guard('customer')->user()->savings;
        if ($request->status == 'true') {
            if ($total <=  $savings_amount) {
                Session::put('savings_pay', $total);
            } else {
                Session::put('savings_pay', 0);
            }
        } else {
            Session::put('savings_pay', 0);
        }
        return view('frontEnd.layouts.ajax.cart');
    }


    public function cod_charge(Request $request)
    {
        if ($request->id == 'cod') {
            $subtotal = Cart::instance('shopping')->subtotal();
            $subtotal = str_replace(',', '', $subtotal);
            $subtotal = str_replace('.00', '', $subtotal);
            $shipping = Session::get('shipping') ? Session::get('shipping') : 0;
            $discount = Session::get('discount') ? Session::get('discount') : 0;
            $coupon = Session::get('coupon_amount') ? Session::get('coupon_amount') : 0;
            $total = $subtotal + $shipping - ($discount + $coupon);

            $initial_cod = $total > 0 ? 20 : 0;
            $extra_cod = $total > 1000 ? 10 : 0;
            $thousands = ceil($total / 1000);
            $cod_charge = $initial_cod + ($extra_cod * ($thousands - 1));
            Session::put('cod_charge', $cod_charge);
        } else {
            Session::put('cod_charge', 0);
        }

        if($request->id == 'bkash' || $request->id == 'rocket') {
            $subtotal = Cart::instance('shopping')->subtotal();
            $subtotal = str_replace(',', '', $subtotal);
            $subtotal = str_replace('.00', '', $subtotal);
            $shipping = Session::get('shipping') ? Session::get('shipping') : 0;
            $discount = Session::get('discount') ? Session::get('discount') : 0;
            $coupon = Session::get('coupon_amount') ? Session::get('coupon_amount') : 0;
            $total = $subtotal + $shipping - ($discount + $coupon);
            $cashout_charge = ($total / 100) * 1.8;
            Session::put('cashout_charge', $cashout_charge);
        } else {
            Session::put('cashout_charge', 0);
        }


        return view('frontEnd.layouts.ajax.cart');
    }
    public function contact(Request $request)
    {
        return view('frontEnd.layouts.pages.contact');
    }

    public function page($slug)
    {
        $page = CreatePage::where('slug', $slug)->firstOrFail();
        return view('frontEnd.layouts.pages.page', compact('page'));
    }
    public function districts(Request $request)
    {
        $areas = Thana::where(['district_id' => $request->id])->orderBy('thana_name', 'asc')->pluck('thana_name', 'id');
        return response()->json($areas);
    }
    public function campaign($slug)
    {
        $campaign_data = Campaign::where('slug', $slug)->with('images')->first();
        $product = Product::where('id', $campaign_data->product_id)
            ->where('status', 1)
            ->with('image')
            ->first();
        Cart::instance('shopping')->destroy();
        $cart_count = Cart::instance('shopping')->count();
        if ($cart_count == 0) {
            Cart::instance('shopping')->add([
                'id' => $product->id,
                'name' => $product->name,
                'qty' => 1,
                'price' => $product->new_price,
                'options' => [
                    'slug' => $product->slug,
                    'image' => $product->image->image,
                    'old_price' => $product->old_price,
                    'purchase_price' => $product->purchase_price,
                ],
            ]);
        }
        $shippingcharge = ShippingCharge::where(['status' => 1, 'pos' => 0])->get();
        $select_charge = ShippingCharge::where(['status' => 1, 'pos' => 0])->first();
        Session::put('shipping', $select_charge->amount);
        return view('frontEnd.layouts.pages.campaign.campaign', compact('campaign_data', 'product', 'shippingcharge'));
    }

    public function payment_success(Request $request)
    {
        $order_id = $request->order_id;
        $shurjopay_service = new ShurjopayController();
        $json = $shurjopay_service->verify($order_id);
        $data = json_decode($json);

        if ($data[0]->sp_code != 1000) {
            Toastr::error('Your payment failed, try again', 'Oops!');
            if ($data[0]->value1 == 'customer_payment') {
                return redirect()->route('home');
            } else {
                return redirect()->route('home');
            }
        }

        if ($data[0]->value1 == 'customer_payment') {

            $customer = Customer::find(Auth::guard('customer')->user()->id);

            // order data save
            $order = new Order();
            $order->invoice_id = $data[0]->id;
            $order->amount = $data[0]->amount;
            $order->customer_id = Auth::guard('customer')->user()->id;
            $order->order_status = $data[0]->bank_status;
            $order->save();

            // payment data save
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->customer_id = Auth::guard('customer')->user()->id;
            $payment->payment_method = 'shurjopay';
            $payment->amount = $order->amount;
            $payment->trx_id = $data[0]->bank_trx_id;
            $payment->sender_number = $data[0]->phone_no;
            $payment->payment_status = 'paid';
            $payment->save();
            // order details data save
            foreach (Cart::instance('shopping')->content() as $cart) {
                $order_details = new OrderDetails();
                $order_details->order_id = $order->id;
                $order_details->product_id = $cart->id;
                $order_details->product_name = $cart->name;
                $order_details->purchase_price = $cart->options->purchase_price;
                $order_details->sale_price = $cart->price;
                $order_details->qty = $cart->qty;
                $order_details->save();
            }

            Cart::instance('shopping')->destroy();
            Toastr::error('Thanks, Your payment send successfully', 'Success!');
            return redirect()->route('home');
        }

        Toastr::error('Something wrong, please try agian', 'Error!');
        return redirect()->route('home');
    }
    public function payment_cancel(Request $request)
    {
        $order_id = $request->order_id;
        $shurjopay_service = new ShurjopayController();
        $json = $shurjopay_service->verify($order_id);
        $data = json_decode($json);

        Toastr::error('Your payment cancelled', 'Cancelled!');
        if ($data[0]->sp_code != 1000) {
            if ($data[0]->value1 == 'customer_payment') {
                return redirect()->route('home');
            } else {
                return redirect()->route('home');
            }
        }
    }
}
