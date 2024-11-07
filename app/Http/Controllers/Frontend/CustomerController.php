<?php

namespace App\Http\Controllers\Frontend;
use shurjopayv2\ShurjopayLaravelPackage8\Http\Controllers\ShurjopayController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Intervention\Image\Facades\Image;
use App\Models\Customer;
use App\Models\District;
use App\Models\Order;
use App\Models\ShippingCharge;
use App\Models\OrderDetails;
use App\Models\Payment;
use App\Models\Shipping;
use App\Models\Review;
use App\Models\PaymentGateway;
use App\Models\SmsGateway;
use App\Models\GeneralSetting;
use App\Models\CustomerProfit;
use App\Models\Withdraw;
use App\Models\CouponCode;
use App\Models\Expense;
use Carbon\Carbon;
use Validator;
use Session;
use Hash;
use Auth;
use Cart;
use Mail;
use Str;
use DB;

class CustomerController extends Controller
{
    function __construct()
    {
        $this->middleware('customer', ['except' => ['register','store','verify','resendotp','account_verify','login','signin','logout','forgot_password','forgot_verify','forgot_reset','forgot_store','forgot_resend','order_success','order_track','order_track_result']]);
    }

    public function review(Request $request){
        $this->validate($request,[
            'ratting'=>'required',
            'review'=>'required',
        ]);

        // data save
        $review              =   new Review();
        $review->name        =   Auth::guard('customer')->user()->name ? Auth::guard('customer')->user()->name : 'N / A';
        $review->email       =   Auth::guard('customer')->user()->email ? Auth::guard('customer')->user()->email : 'N / A';
        $review->product_id  =   $request->product_id;
        $review->review      =   $request->review;
        $review->ratting     =   $request->ratting;
        $review->customer_id =   Auth::guard('customer')->user()->id;
        $review->status      =   'pending';
        $review->save();

        Toastr::success('Thanks, Your review send successfully', 'Success!');
        return redirect()->back();
    }

    public function login(){
        return view('frontEnd.layouts.customer.login');
    }
    
    public function signin(Request $request){
        $auth_check = Customer::where('phone',$request->phone)->first();
        if($auth_check){
            if (Auth::guard('customer')->attempt(['phone' => $request->phone, 'password' => $request->password])) {
                Toastr::success('You are login successfully', 'success!');
                if(Cart::instance('shopping')->count() > 0){
                    return redirect()->route('customer.checkout');
                }
                return redirect()->intended('customer/account');
            }
            Toastr::error('message', 'Opps! your phone or password wrong');
            return redirect()->back();
        }else{
            Toastr::error('message', 'Sorry! You have no account');
            return redirect()->back();
        }
    }
    
    public function register(){
        $districts = District::distinct()->select('district')->orderBy('district','asc')->get();
        return view('frontEnd.layouts.customer.register',compact('districts'));
    }
    
    public function store(Request $request){
        $this->validate($request, [
            'name'    => 'required',
            'refferal_id' => 'required|exists:customers',
            'phone'    => 'required|unique:customers',
            'bkash_no'    => 'required',
            'password' => 'required|min:6|max:12',
            'confirm_password' => 'required|min:6|max:12|same:password',
            'district' => 'required',
            'area' => 'required',
            'village_name' => 'required',
            'gender' => 'required',
            'age' => 'required',
        ]);


        // refer 1 check
        $reffer = Customer::select('id','name','phone','refferal_id','refferal_1','refferal_2','refferal_3')->where('refferal_id',$request->refferal_id)->first();
        if(!$reffer){
            Toastr::error('Failed Your refferal number not found');
            return redirect()->back();
        }
        // $count = Customer::where('refferal_1',$reffer->id)->count();
        // if($count > 4){
        //     Toastr::error('This refferal id limit exist');
        //     return redirect()->back();
        // }
        // reffer 2
        $refferal_2 = Customer::select('id','name','refferal_1')->where('id',$reffer?$reffer->refferal_1:'')->first();
        
        // reffer 3
        $refferal_3 = Customer::select('id','name','refferal_1')->where('id',$refferal_2?$refferal_2->refferal_1:'')->first();

        // reffer 4
        $refferal_4 = Customer::select('id','name','refferal_1')->where('id',$refferal_3?$refferal_3->refferal_1:'')->first();

        $name  =  time().'-'.$request->image->getClientOriginalName();
        $name  = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp',$name);
        $name  = strtolower(Str::slug($name));
        $uploadpath = 'public/uploads/customer/';
        $imageUrl = $uploadpath.$name; 
        $img   = Image::make($request->image->getRealPath());
        $img->encode('webp', 90);
        $width = 120;
        $height = 120;
        $img->resize($width, $height);
        $img->save($imageUrl);

        $last_id = Customer::max('id');
        $referral_id = $last_id ? '1' . str_pad($last_id + 1, 5, '0', STR_PAD_LEFT) : '100001';
        $store              = new Customer();
        $store->name        = $request->name;
        $store->slug        = strtolower(Str::slug($request->name.'-'.$last_id+1));
        $store->phone       = $request->phone;
        $store->bkash_no    = $request->bkash_no;
        $store->village_name= $request->village_name;
        $store->district    = $request->district;
        $store->area        = $request->area;
        $store->house       = $request->house;
        $store->road        = $request->road;
        $store->word_no     = $request->ward_no;
        $store->gender      = $request->gender;
        $store->age         = $request->age;
        $store->nid         = $request->nid;
        $store->password    = bcrypt($request->password);
        $store->refferal_1  = $reffer->id;
        $store->refferal_2  = $refferal_2?$refferal_2->id:NULL;
        $store->refferal_3  = $refferal_3?$refferal_3->id:NULL;
        $store->refferal_4  = $refferal_4?$refferal_4->id:NULL;
        $store->password    = bcrypt($request->password);
        $store->verify      = mt_rand(1111,9999);
        $store->refferal_id = $referral_id;
        $store->image       = $imageUrl;
        $store->verify      = 1;
        $store->status      = 'active';
        $store->save();
        Session::forget('refferal_id');
        
        Toastr::success('Success','Account Create Successfully');
        return redirect()->route('customer.login');
    }
    public function verify(){
        return view('frontEnd.layouts.customer.verify');
    }
    public function resendotp(Request $request){
        $customer_info = Customer::where('phone',session::get('verify_phone'))->first();
        $customer_info->verify = rand(1111,9999);
        $customer_info->save();
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where('status', 1)->first();
        if($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "contacts" => $customer_info->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "msg" => "Dear $customer_info->name!\r\nYour account verify OTP is $customer_info->verify \r\nThank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            
        }
        Toastr::success('Success','Resend code send successfully');
        return redirect()->back();
    }
    public function account_verify(Request $request){
        $this->validate($request,[
            'otp' => 'required',
        ]);
        $customer_info = Customer::where('phone',session::get('verify_phone'))->first();
        if($customer_info->verify != $request->otp){
            Toastr::error('Success','Your OTP not match');
            return redirect()->back();
        }

        $customer_info->verify = 1;
        $customer_info->status = 'active';
        $customer_info->save();
        Auth::guard('customer')->loginUsingId($customer_info->id);
        return redirect()->route('customer.account');
    }
    public function forgot_password(){
        return view('frontEnd.layouts.customer.forgot_password');
    }
    
    public function forgot_verify(Request $request){
        $customer_info = Customer::where('phone',$request->phone)->first();
        if(!$customer_info){
            Toastr::error('Your phone number not found');
            return back();
        }
        $customer_info->forgot = rand(1111,9999);
        $customer_info->save();
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where(['status'=> 1, 'forget_pass'=>1])->first();
        if($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "contacts" => $customer_info->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "msg" => "Dear $customer_info->name!\r\nYour forgot password verify OTP is $customer_info->forgot \r\nThank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        }
        
        session::put('verify_phone',$request->phone);
        Toastr::success('Your account register successfully');
        return redirect()->route('customer.forgot.reset');
    }
    
    public function forgot_resend(Request $request){
        $customer_info = Customer::where('phone',session::get('verify_phone'))->first();
        $customer_info->forgot = rand(1111,9999);
        $customer_info->save();
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where(['status'=> 1])->first();
        if($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "contacts" => $customer_info->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "msg" => "Dear $customer_info->name!\r\nYour forgot password verify OTP is $customer_info->forgot \r\nThank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            
        }

        Toastr::success('Success','Resend code send successfully');
        return redirect()->back();
    }
    public function forgot_reset(){
        if(!Session::get('verify_phone')){
          Toastr::error('Something wrong please try again');
          return redirect()->route('customer.forgot.password'); 
        };
        return view('frontEnd.layouts.customer.forgot_reset');
    }
    public function forgot_store(Request $request){

        $customer_info = Customer::where('phone',session::get('verify_phone'))->first();

        if($customer_info->forgot != $request->otp){
            Toastr::error('Success','Your OTP not match');
            return redirect()->back();
        }

        $customer_info->forgot = 1;
        $customer_info->password = bcrypt($request->password);
        $customer_info->save();
        if(Auth::guard('customer')->attempt(['phone' => $customer_info->phone, 'password' => $request->password])) {
            Session::forget('verify_phone');
            Toastr::success('You are login successfully', 'success!');
                return redirect()->intended('customer/account');
        }
    }
    public function account(){
        return view('frontEnd.layouts.customer.account');
    }
    public function logout(Request $request){
        Auth::guard('customer')->logout();
        Toastr::success('You are logout successfully', 'success!');
        return redirect()->route('customer.login');
    }
    public function savings_pay(Request $request){
        $subtotal = Cart::instance('shopping')->subtotal();
        $subtotal=str_replace(',','',$subtotal);
        $subtotal=str_replace('.00', '',$subtotal);
        $shipping = Session::get('shipping')?Session::get('shipping'):0;
        $discount = Session::get('discount')?Session::get('discount'):0;
        $coupon = Session::get('coupon_amount') ? Session::get('coupon_amount'):0;
        $total = $subtotal + $shipping - ($discount+$coupon);

        $savings_amount = auth()->guard('customer')->user()->savings;
        if($request->status == 'true'){
            if($total <= $savings_amount){
                Session::put('savings_pay',$total);
            }else{
                Session::put('savings_pay',0);
            }
        }else{
            Session::put('savings_pay',0);
        }
        
        return view('frontEnd.layouts.ajax.cart');
    }
    public function checkout(){
        $bkash_gateway = PaymentGateway::where(['status'=> 1, 'type'=>'bkash'])->first();
        $shurjopay_gateway = PaymentGateway::where(['status'=> 1, 'type'=>'shurjopay'])->first();
        Session::put('shipping',0);
        
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
        $districts = District::distinct()->select('district')->orderBy('district','asc')->get();
       return view('frontEnd.layouts.customer.checkout',compact('bkash_gateway', 'shurjopay_gateway','districts'));
    }
    public function order_save(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'phone'=>'required',
            'district'=>'required',
            'upazila'=>'required',
            'village_name'=>'required',
            'address'=>'required'
        ]);
        if(Cart::instance('shopping')->count() <= 0) {
            Toastr::error('Your shopping empty', 'Failed!');
            return redirect()->back();
        }

        $subtotal = Cart::instance('shopping')->subtotal();
        $subtotal = str_replace(',','',$subtotal);
        $subtotal = str_replace('.00', '',$subtotal);
        $discount = Session::get('discount')+Session::get('coupon_amount');

        $shippingfee  = Session::get('shipping');
        $cod_charge  = Session::get('cod_charge');
        $purchase_amount = 0;
        foreach(Cart::instance('shopping')->content() as $row) {
            $purchase_amount += $row->options->purchase_price*$row->qty;
        }

        $last_id = Order::max('id');
        $invoice_id = $last_id ? str_pad($last_id + 1, 6, '0', STR_PAD_LEFT) : '000001';
        
        // order data save
        $order                   = new Order();
        $order->invoice_id       = $invoice_id;
        $order->amount           = ($subtotal + $cod_charge) - $discount;
        $order->discount         = $discount ? $discount : 0;
        $order->savings_pay  = Session::get('savings_pay');
        $order->shipping_charge  = $shippingfee;
        $order->cod_charge       = $cod_charge;
        $order->purchase_amount  = $purchase_amount;
        $order->customer_id      =  Auth::guard('customer')->user()->id;
        $order->order_status     = 1;
        $order->note             = $request->note;
        $order->save();

        // shipping data save
        $shipping              =   new Shipping();
        $shipping->order_id    =   $order->id;
        $shipping->customer_id =   Auth::guard('customer')->user()->id;
        $shipping->name        =   $request->name;
        $shipping->phone       =   $request->phone;
        $shipping->district    =   $request->district;
        $shipping->upazila     =   $request->upazila;
        $shipping->village_name=   $request->village_name;
        $shipping->word_no     =   $request->word_no;
        $shipping->address     =   $request->address;
        $shipping->save();

        // payment data save
        $payment                 = new Payment();
        $payment->order_id       = $order->id;
        $payment->customer_id    = Auth::guard('customer')->user()->id;
        $payment->payment_method = $request->payment_method;
        $payment->amount         = $order->amount;
        $payment->trx_id         = $request->trx_id;
        $payment->sender_number  = $request->sender_number;
        $payment->payment_status = 'pending';
        $payment->save();

       // order details data save
        foreach(Cart::instance('shopping')->content() as $cart){
            $order_details                  =   new OrderDetails();
            $order_details->order_id        =   $order->id;
            $order_details->product_id      =   $cart->id;
            $order_details->product_name    =   $cart->name;
            $order_details->purchase_price  =   $cart->options->purchase_price;
            $order_details->product_color   =   $cart->options->product_color;
            $order_details->product_size    =   $cart->options->product_size;
            $order_details->package_pro     =   $cart->options->package_pro;
            $order_details->type            =   $cart->options->type;
            $order_details->sale_price      =   $cart->price;
            $order_details->qty             =   $cart->qty;
            $order_details->save();
        }

        $customer_info = Customer::select('id','savings')->find(Auth::guard('customer')->user()->id);
        $customer_info->savings -= Session::get('savings_pay');
        $customer_info->save();


       
        Cart::instance('shopping')->destroy();
        
        Toastr::success('Thanks, Your order place successfully', 'Success!');
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where(['status'=> 1, 'order'=>'1'])->first();
        if($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "contacts" => $request->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "msg" => "Dear $request->name!\r\nYour order has been successfully placed. check your customer panel on our website to know more about your order. Thank you for using $site_setting->name"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        }
        
        return redirect('customer/order-success/'.$order->id);
        
        // if($request->payment_method=='bkash'){
        //     return redirect('/bkash/checkout-url/create?order_id='.$order->id);
        // }elseif($request->payment_method=='shurjopay'){
        //     $info = array( 
        //         'currency' => "BDT",
        //         'amount' => $order->amount, 
        //         'order_id' => uniqid(), 
        //         'discsount_amount' =>0 , 
        //         'disc_percent' =>0 , 
        //         'client_ip' => $request->ip(), 
        //         'customer_name' =>  $request->name, 
        //         'customer_phone' => $request->phone, 
        //         'email' => "customer@gmail.com", 
        //         'customer_address' => $request->address, 
        //         'customer_city' => $request->area, 
        //         'customer_state' => $request->area, 
        //         'customer_postcode' => "1212", 
        //         'customer_country' => "BD",
        //         'value1' => $order->id
        //     );
        //     $shurjopay_service = new ShurjopayController();
        //     return $shurjopay_service->checkout($info);
        // }else{
        //     return redirect('customer/order-success/'.$order->id);
        // }
    }
    
    public function orders()
    {
        $orders = Order::where('customer_id',Auth::guard('customer')->user()->id)->with('status')->latest()->get();
        return view('frontEnd.layouts.customer.orders',compact('orders'));
    }
    public function order_success($id) {
        $order = Order::where('id',$id)->firstOrFail();
        return view('frontEnd.layouts.customer.order_success',compact('order'));
    }
    public function invoice(Request $request)
    {
        $order = Order::where(['id'=>$request->id,'customer_id'=>Auth::guard('customer')->user()->id])->with('orderdetails','payment','shipping','customer')->firstOrFail();
        return view('frontEnd.layouts.customer.invoice',compact('order'));
    } 
    public function order_note(Request $request)
    {
        $order = Order::where(['id'=>$request->id,'customer_id'=>Auth::guard('customer')->user()->id])->firstOrFail();
        return view('frontEnd.layouts.customer.order_note',compact('order'));
    }
    public function profile_edit(Request $request)
    {
        $profile_edit = Customer::where(['id'=>Auth::guard('customer')->user()->id])->firstOrFail();
        $districts = District::distinct()->select('district')->get();
        $areas = District::where(['district'=>$profile_edit->district])->select('area_name','id')->get();
        return view('frontEnd.layouts.customer.profile_edit',compact('profile_edit','districts','areas'));
    }
    public function profile_update(Request $request)
    {
        $update_data = Customer::where(['id'=>Auth::guard('customer')->user()->id])->firstOrFail();

        $image = $request->file('image');
        if($image){
            // image with intervention 
            $name =  time().'-'.$image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp',$name);
            $name = strtolower(Str::slug($name));
            $uploadpath = 'public/uploads/customer/';
            $imageUrl = $uploadpath.$name; 
            $img = Image::make($image->getRealPath());
            $img->encode('webp', 90);
            $width = 120;
            $height = 120;
            $img->resize($width, $height);
            $img->save($imageUrl);
        }else{
            $imageUrl = $update_data->image;
        }

        $update_data->name        =   $request->name;
        $update_data->phone       =   $request->phone;
        $update_data->district    =   $request->district;
        $update_data->area        =   $request->area;
        $update_data->image       =   $imageUrl;
        $update_data->save();

        Toastr::success('Your profile update successfully', 'Success!');
       return redirect()->route('customer.account');
    }

    public function refferals(Request $request){
        if($request->level == 1){
            $data = Customer::where(['refferal_1'=>Auth::guard('customer')->user()->id])->select('id','name','phone','status','refferal_1','refferal_2','refferal_3','refferal_4')->get();
        }elseif($request->level == 2){
            $data = Customer::where(['refferal_2'=>Auth::guard('customer')->user()->id])->select('id','name','phone','status','refferal_1','refferal_2','refferal_3','refferal_4')->get();
        }elseif($request->level == 3){
            $data = Customer::where(['refferal_3'=>Auth::guard('customer')->user()->id])->select('id','name','phone','status','refferal_1','refferal_2','refferal_3','refferal_4')->get();
        }elseif($request->level == 4){
            $data = Customer::where(['refferal_4'=>Auth::guard('customer')->user()->id])->select('id','name','phone','status','refferal_1','refferal_2','refferal_3','refferal_4')->get();
        }
        return view('frontEnd.layouts.customer.refferals',compact('data'));
    }
    public function balance(Request $request){
        if($request->start_date && $request->end_date){
           $data = CustomerProfit::where(['customer_id'=>Auth::guard('customer')->user()->id])->whereBetween(DB::raw('DATE(created_at)'), [$request->start_date,$request->end_date])->with('order')->get(); 
  
        }else{
            $data = CustomerProfit::where(['customer_id'=>Auth::guard('customer')->user()->id])->whereMonth('created_at', Carbon::now()->month)->with('order')->get();
        }
        
        return view('frontEnd.layouts.customer.balance',compact('data'));
    }
    public function withdraw_save(Request $request){
        $this->validate($request,[
            'type_id'=>'required',
            'amount'=>'required',
        ]);

        $pending_withdraw = Withdraw::where(['customer_id'=>Auth::guard('customer')->user()->id,'status'=>'pending'])->first();
        if($pending_withdraw){
            Toastr::error('Your withdraw pending , please try later', 'Failed!');
            return redirect()->route('customer.withdraws');
        }

        if(Auth::guard('customer')->user()->balance < $request->amount){
            Toastr::error('Balance withdraw amount low', 'Failed!');
            return redirect()->route('customer.withdraws');
        }
        $withdraw = new Withdraw();
        $withdraw->customer_id = Auth::guard('customer')->user()->id;
        $withdraw->type_id = $request->type_id;
        $withdraw->amount = $request->amount;
        $withdraw->status = 'pending';
        $withdraw->note = $request->note;
        $withdraw->save();
        Toastr::success('Withdraw request send successfully', 'Success!');
        return redirect()->route('customer.withdraws');
    }
    public function withdraws(Request $request){
        $date_withdraw = GeneralSetting::where('status', 1)->first();

        if (!$date_withdraw) {
            return 'No record found'; // Handle case where no record is found
        }
        
        $startDate = $date_withdraw->start_date; 
        $endDate = $date_withdraw->end_date;  
        
        // Get today's date
        $today = Carbon::today();
        
        // Check if today's date is between the start and end dates
        $isDateValid = $today->between(Carbon::parse($startDate), Carbon::parse($endDate));
        
        $isDateValid = $isDateValid ? 'true' : 'false';
        $data = Withdraw::where(['customer_id'=>Auth::guard('customer')->user()->id])->get();
        return view('frontEnd.layouts.customer.withdraws',compact('data', 'isDateValid'));
    }

    public function order_track(){
        return view('frontEnd.layouts.customer.order_track');
    }

     public function order_track_result(Request $request){
       
       $phone = $request->phone;
       $invoice_id = $request->invoice_id;
           
       if($phone !=null && $invoice_id==null){
        $order = DB::table('orders')
        ->join('shippings','orders.id','=','shippings.order_id')
        ->where(['shippings.phone' => $request->phone])
        ->get();
        
       }else if($invoice_id && $phone){
         $order = DB::table('orders')
        ->join('shippings','orders.id','=','shippings.order_id')
        ->where(['orders.invoice_id' => $request->invoice_id, 'shippings.phone'=>$request->phone])
        ->get();
       }
        
       if($order->count() == 0){
           
            Toastr::error('message', 'Something Went Wrong !');
            return redirect()->back();
       }
       
    //   return $order->count();
        
        
        
        return view('frontEnd.layouts.customer.tracking_result',compact('order'));
    }


    public function change_pass(){
        return view('frontEnd.layouts.customer.change_password');
    }

     public function password_update(Request $request)
    {
        $this->validate($request, [
            'old_password'=>'required',
            'new_password'=>'required',
            'confirm_password' => 'required_with:new_password|same:new_password|'
        ]);

        $customer = Customer::find(Auth::guard('customer')->user()->id);
        $hashPass = $customer->password;

        if (Hash::check($request->old_password, $hashPass)) {

            $customer->fill([
                'password' => Hash::make($request->new_password)
            ])->save();

            Toastr::success('Success', 'Password changed successfully!');
            return redirect()->route('customer.account');
        }else{
            Toastr::error('Failed', 'Old password not match!');
            return redirect()->back();
        }
    }
    public function customer_coupon(Request $request){
        $findcoupon = CouponCode::where('coupon_code',$request->coupon_code)->first();
        if($findcoupon==NULL){
            Toastr::error('Opps! your entered promo code is not valid');
            return back();
        }else{
            $currentdata = date('Y-m-d');
            $expiry_date=$findcoupon->expiry_date;
            if($currentdata <= $expiry_date){
                $totalcart = Cart::instance('shopping')->subtotal();
                $totalcart = str_replace('.00','',$totalcart);
                $totalcart = str_replace(',','',$totalcart);
                if($totalcart >= $findcoupon->buy_amount){
                        if($totalcart >= $findcoupon->buy_amount){
                            if($findcoupon->offer_type==1){
                                $discountammount =  (($totalcart*$findcoupon->amount)/100);
                                Session::forget('coupon_amount');
                                Session::put('coupon_amount',$discountammount);
                                Session::put('coupon_used',$findcoupon->coupon_code);
                            }else{
                                Session::put('coupon_amount',$findcoupon->amount);
                                Session::put('coupon_used',$findcoupon->coupon_code);
                            }
                            Toastr::success('Success! your promo code accepted');
                            return back();
                        }
                    
                }else{
                    Toastr::error('You need to buy a minimum of ' . $findcoupon->buy_amount . ' Taka to get the offer');
                    return back();
                }
            }else{
                Toastr::error('Opps! Sorry your promo code date expaire');
                return back();
            }
        }
    }
    public function coupon_remove(Request $request){
        Session::forget('coupon_amount');
        Session::forget('coupon_used');
        Session::forget('discount');
        Toastr::success('Success','Your coupon remove successfully');
        return back();
              
    }
}
