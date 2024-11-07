<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Productprice;
use App\Models\Product;
use App\Models\CouponCode;
use App\Models\ProductVariable;
use Session;
use Toastr;
use Cart;
use DB;
class ShoppingController extends Controller
{

    public function add_to_cart(Request $request){
       $product = Product::select('id','name','slug','new_price','old_price','purchase_price','topsale','type')->where(['id' => $request->id])->first();
        $cartinfo = Cart::instance('shopping')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $request->qty,
            'price' => $product->new_price,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image,
                'old_price' => $product->old_price,
                'purchase_price' => $product->purchase_price,
                'package_pro'=>$product->topsale,
                'type'=>$product->type
            ],
        ]);

        $subtotal=Cart::instance('shopping')->subtotal();
        $subtotal=str_replace(',','',$subtotal);
        $subtotal=str_replace('.00', '',$subtotal);
        $findcoupon = CouponCode::where('coupon_code',Session::get('coupon_used'))->first();
        if($findcoupon==NULL){
            Session::forget('coupon_amount');
        }else{
            $currentdata = date('Y-m-d');
            $expiry_date=$findcoupon->expiry_date;
            if($currentdata <= $expiry_date){
                if($subtotal >= $findcoupon->buy_amount){
                        if($subtotal >= $findcoupon->buy_amount){
                            if($findcoupon->offer_type==1){
                                $discountammount =  (($subtotal*$findcoupon->amount)/100);
                                Session::forget('coupon_amount');
                                Session::put('coupon_amount',$discountammount);
                            }else{
                                Session::forget('coupon_amount');
                                Session::put('coupon_amount',$findcoupon->amount);
                            }
                        }
                }else{
                   Session::forget('coupon_amount');
                }
            }else{
                Session::forget('coupon_amount');
            }
        }
        return response()->json($cartinfo);
    } 

    public function cart_store(Request $request)
    {
        $product = Product::select('id','name','slug','new_price','old_price','purchase_price','topsale','type','quantity')->where(['id' => $request->id])->first();
        $var_product = ProductVariable::where(['product_id' => $request->id, 'color' => $request->product_color,'size' => $request->product_size])->first();
  
        if($product->type == 1){
            $stock = $var_product?$var_product->stock:0;
        }else{
            $stock = $product->quantity;
        }
        
        $cartitem = Cart::instance('shopping')->content()->where('id', $product->id)->first();
        if($cartitem){
            $cart_qty = $cartitem->qty + $request->qty;
        }else{
            $cart_qty = $request->qty;
        }
        if($stock < $cart_qty){
           Toastr::error('Product stock limit over', 'Failed!');
           return back();
        }
        
        Cart::instance('shopping')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $request->qty,
            'price' => $product->new_price,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image,
                'old_price' => $product->old_price,
                'purchase_price' => $product->purchase_price,
                'product_size'=>$request->product_size,
                'product_color'=>$request->product_color,
                'package_pro'=>$product->topsale,
                'pro_unit'=>$request->pro_unit,
                'type'=>$product->type
            ],
        ]);
        
        $subtotal=Cart::instance('shopping')->subtotal();
        $subtotal=str_replace(',','',$subtotal);
        $subtotal=str_replace('.00', '',$subtotal);
        $findcoupon = CouponCode::where('coupon_code',Session::get('coupon_used'))->first();
        if($findcoupon==NULL){
            Session::forget('coupon_amount');
        }else{
            $currentdata = date('Y-m-d');
            $expiry_date=$findcoupon->expiry_date;
            if($currentdata <= $expiry_date){
                if($subtotal >= $findcoupon->buy_amount){
                        if($subtotal >= $findcoupon->buy_amount){
                            if($findcoupon->offer_type==1){
                                $discountammount =  (($subtotal*$findcoupon->amount)/100);
                                Session::forget('coupon_amount');
                                Session::put('coupon_amount',$discountammount);
                            }else{
                                Session::forget('coupon_amount');
                                Session::put('coupon_amount',$findcoupon->amount);
                            }
                        }
                }else{
                   Session::forget('coupon_amount');
                }
            }else{
                Session::forget('coupon_amount');
            }
        }

        Toastr::success('Product successfully add to cart', 'Success!');
       
       if($request->order_now == 'Order Now'){
            return redirect()->route('customer.checkout');
        }
        return redirect()->back();
        return redirect()->route('customer.checkout');
        
    }
    public function cart_remove(Request $request)
    {
        $remove = Cart::instance('shopping')->update($request->id, 0);
        $data = Cart::instance('shopping')->content();
        
        $subtotal = Cart::instance('shopping')->subtotal();
        $subtotal=str_replace(',','',$subtotal);
        $subtotal=str_replace('.00', '',$subtotal);
        $shipping = Session::get('shipping')?Session::get('shipping'):0;
        $discount = Session::get('discount')?Session::get('discount'):0;
        $coupon = Session::get('coupon_amount') ? Session::get('coupon_amount'):0;
        $total = $subtotal + $shipping - ($discount+$coupon);
        
        $initial_cod = $total > 0? 20 :0;
        $extra_cod = $total > 1000? 10 : 0;
        $thousands = ceil($total / 1000);
        $cod_charge = $initial_cod + ($extra_cod * ($thousands - 1));
        Session::put('cod_charge',$cod_charge);
        
        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }
    public function cart_increment(Request $request)
    {
        $item = Cart::instance('shopping')->get($request->id);
        $qty = $item->qty + 1;
        $increment = Cart::instance('shopping')->update($request->id, $qty);
        $data = Cart::instance('shopping')->content();
        $subtotal=Cart::instance('shopping')->subtotal();
        $subtotal=str_replace(',','',$subtotal);
        $subtotal=str_replace('.00', '',$subtotal);
        $findcoupon = CouponCode::where('coupon_code',Session::get('coupon_used'))->first();
        if($findcoupon==NULL){
            Session::forget('coupon_amount');
        }else{
            $currentdata = date('Y-m-d');
            $expiry_date=$findcoupon->expiry_date;
            if($currentdata <= $expiry_date){
                if($subtotal >= $findcoupon->buy_amount){
                    if($findcoupon->offer_type==1){
                        $discountammount =  (($subtotal*$findcoupon->amount)/100);
                        Session::forget('coupon_amount');
                        Session::put('coupon_amount',$discountammount);
                    }else{
                        Session::forget('coupon_amount');
                        Session::put('coupon_amount',$findcoupon->amount);
                    }
                }else{
                   Session::forget('coupon_amount');
                }
            }else{
                Session::forget('coupon_amount');
            }
        }
        $subtotal = Cart::instance('shopping')->subtotal();
        $subtotal=str_replace(',','',$subtotal);
        $subtotal=str_replace('.00', '',$subtotal);
        $shipping = Session::get('shipping')?Session::get('shipping'):0;
        $discount = Session::get('discount')?Session::get('discount'):0;
        $coupon = Session::get('coupon_amount') ? Session::get('coupon_amount'):0;
        $total = $subtotal + $shipping - ($discount+$coupon);
        
        $initial_cod = $total > 0? 20 :0;
        $extra_cod = $total > 1000? 10 : 0;
        $thousands = ceil($total / 1000);
        $cod_charge = $initial_cod + ($extra_cod * ($thousands - 1));
        Session::put('cod_charge',$cod_charge);
        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }
    public function cart_decrement(Request $request)
    {
        $item = Cart::instance('shopping')->get($request->id);
        $qty = $item->qty - 1;
        $decrement = Cart::instance('shopping')->update($request->id, $qty);
        $data = Cart::instance('shopping')->content();
        $subtotal=Cart::instance('shopping')->subtotal();
        $subtotal=str_replace(',','',$subtotal);
        $subtotal=str_replace('.00', '',$subtotal);
        $findcoupon = CouponCode::where('coupon_code',Session::get('coupon_used'))->first();
        if($findcoupon==NULL){
            Session::forget('coupon_amount');
        }else{
            $currentdata = date('Y-m-d');
            $expiry_date=$findcoupon->expiry_date;
            if($currentdata <= $expiry_date){
                if($subtotal >= $findcoupon->buy_amount){
                        if($subtotal >= $findcoupon->buy_amount){
                            if($findcoupon->offer_type==1){
                                $discountammount =  (($subtotal*$findcoupon->amount)/100);
                                Session::forget('coupon_amount');
                                Session::put('coupon_amount',$discountammount);
                            }else{
                                Session::forget('coupon_amount');
                                Session::put('coupon_amount',$findcoupon->amount);
                            }
                        }
                }else{
                   Session::forget('coupon_amount');
                }
            }else{
                Session::forget('coupon_amount');
            }
        }
        $subtotal = Cart::instance('shopping')->subtotal();
        $subtotal=str_replace(',','',$subtotal);
        $subtotal=str_replace('.00', '',$subtotal);
        $shipping = Session::get('shipping')?Session::get('shipping'):0;
        $discount = Session::get('discount')?Session::get('discount'):0;
        $coupon = Session::get('coupon_amount') ? Session::get('coupon_amount'):0;
        $total = $subtotal + $shipping - ($discount+$coupon);
        
        $initial_cod = $total > 0? 20 :0;
        $extra_cod = $total > 1000? 10 : 0;
        $thousands = ceil($total / 1000);
        $cod_charge = $initial_cod + ($extra_cod * ($thousands - 1));
        Session::put('cod_charge',$cod_charge);
        return view('frontEnd.layouts.ajax.cart', compact('data'));
    }
    public function cart_count(Request $request)
    {
        $data = Cart::instance('shopping')->count();
        return view('frontEnd.layouts.ajax.cart_count', compact('data'));
    }
    public function mobilecart_qty(Request $request)
    {
        $data = Cart::instance('shopping')->count();
        return view('frontEnd.layouts.ajax.mobilecart_qty', compact('data'));
    }
     public function cart_remove_bn(Request $request)
    {
        $remove = Cart::instance('shopping')->update($request->id, 0);
        $data = Cart::instance('shopping')->content();
        
        $subtotal=Cart::instance('shopping')->subtotal();
        $subtotal=str_replace(',','',$subtotal);
        $subtotal=str_replace('.00', '',$subtotal);
        $findcoupon = CouponCode::where('coupon_code',Session::get('coupon_used'))->first();
        if($findcoupon==NULL){
            Session::forget('coupon_amount');
        }else{
            $currentdata = date('Y-m-d');
            $expiry_date=$findcoupon->expiry_date;
            if($currentdata <= $expiry_date){
                if($subtotal >= $findcoupon->buy_amount){
                        if($subtotal >= $findcoupon->buy_amount){
                            if($findcoupon->offer_type==1){
                                $discountammount =  (($subtotal*$findcoupon->amount)/100);
                                Session::forget('coupon_amount');
                                Session::put('coupon_amount',$discountammount);
                            }else{
                                Session::forget('coupon_amount');
                                Session::put('coupon_amount',$findcoupon->amount);
                            }
                        }
                }else{
                   Session::forget('coupon_amount');
                }
            }else{
                Session::forget('coupon_amount');
            }
        }
        return view('frontEnd.layouts.ajax.cart_bn', compact('data'));
    }
    public function cart_increment_bn(Request $request)
    {
        $item = Cart::instance('shopping')->get($request->id);
        $qty = $item->qty + 1;
        $increment = Cart::instance('shopping')->update($request->id, $qty);
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart_bn', compact('data'));
    }
    public function cart_decrement_bn(Request $request)
    {
        $item = Cart::instance('shopping')->get($request->id);
        $qty = $item->qty - 1;
        $decrement = Cart::instance('shopping')->update($request->id, $qty);
        $data = Cart::instance('shopping')->content();
        return view('frontEnd.layouts.ajax.cart_bn', compact('data'));
    }
    // wishlist script
    public function wishlist_store(Request $request){
        $product = Product::where(['id'=>$request->id])->select('id','name','old_price','new_price','purchase_price')->first();
        Cart::instance('wishlist')->add(['id'=>$product->id,'name'=>$product->name,'qty'=>$request->qty,'price'=>$product->new_price,'options' => ['image'=>$product->image->image,'purchase_price'=>$product->purchase_price,'old_price'=>$product->old_price]]);
        $data = Cart::instance('wishlist')->content();
        return response()->json($data);
         
    }
    public function wishlist_show() {
        $data = Cart::instance('wishlist')->content();
        return view('frontEnd.layouts.pages.wishlist',compact('data'));
    } 
    public function wishlist_remove(Request $request) {
        $remove = Cart::instance('wishlist')->update($request->id,0);
        $data   = Cart::instance('wishlist')->content();
        return view('frontEnd.layouts.ajax.wishlist',compact('data'));
    }    
    public function wishlist_count(Request $request) {
        $data   = Cart::instance('wishlist')->count();
        return view('frontEnd.layouts.ajax.wishlist_count',compact('data'));
    } 

}
