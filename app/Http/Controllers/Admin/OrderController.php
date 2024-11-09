<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Customer;
use App\Models\OrderStatus;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Shipping;
use App\Models\ShippingCharge;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Courierapi;
use App\Models\Expense;
use App\Models\ExpenseCategories;
use App\Models\CustomerProfit;
use App\Models\AffiliateCommision;
use App\Models\ProductVariable;
use App\Models\GeneralSetting;

class OrderController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:order-list|order-create|order-edit|order-delete', ['only' => ['index', 'order_store', 'order_edit']]);
        $this->middleware('permission:order-create', ['only' => ['order_store', 'order_create']]);
        $this->middleware('permission:order-edit', ['only' => ['order_edit', 'order_update']]);
        $this->middleware('permission:order-delete', ['only' => ['destroy']]);
        $this->middleware('permission:order-invoice', ['only' => ['invoice']]);
        $this->middleware('permission:order-process', ['only' => ['process', 'order_process']]);
        $this->middleware('permission:order-process', ['only' => ['process']]);
    }
    public function search(Request $request)
    {
        $products = Product::select('id', 'name', 'slug', 'new_price', 'old_price')
            ->where('status', 1)
            ->with('image');
        if ($request->keyword) {
            $products = $products->where('name', 'LIKE', '%' . $request->keyword . "%")->orWhere('pro_barcode', 'LIKE', '%' . $request->keyword . "%");
        }
        $products = $products->get();

        if (empty($request->keyword)) {
            $products = [];
        }
        return view('backEnd.order.search', compact('products'));
    }
    public function index($slug, Request $request)
    {
        if ($slug == 'all') {
            $order_status = (object) [
                'name' => 'All',
                'orders_count' => Order::count(),
            ];
            $show_data = Order::latest()->with('shipping', 'status');
            if ($request->keyword) {
                $show_data = $show_data->where(function ($query) use ($request) {
                    $query->orWhere('invoice_id', 'LIKE', '%' . $request->keyword . '%')
                        ->orWhereHas('shipping', function ($subQuery) use ($request) {
                            $subQuery->where('phone', $request->keyword);
                        });
                });
            }
            $show_data = $show_data->paginate(50);
        } else {
            $order_status = OrderStatus::where('slug', $slug)->withCount('orders')->first();
            $show_data = Order::where(['order_status' => $order_status->id])->latest()->with('shipping', 'status')->paginate(50);
        }
        $users = User::get();
        $steadfast = Courierapi::where(['status' => 1, 'type' => 'steadfast'])->first();
        $pathao_info = Courierapi::where(['status' => 1, 'type' => 'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();
        // pathao courier
        if ($pathao_info) {
            $response = Http::get($pathao_info->url . '/api/v1/countries/1/city-list');
            $pathaocities = $response->json();
            $response2 = Http::withHeaders([
                'Authorization' => 'Bearer ' . $pathao_info->token,
                'Content-Type' => 'application/json',
            ])->get($pathao_info->url . '/api/v1/stores');
            $pathaostore = $response2->json();
        } else {
            $pathaocities = [];
            $pathaostore = [];
        }
        return view('backEnd.order.index', compact('show_data', 'order_status', 'users', 'steadfast', 'pathaostore', 'pathaocities'));
    }

    public function pathaocity(Request $request)
    {
        $pathao_info = Courierapi::where(['status' => 1, 'type' => 'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();
        if ($pathao_info) {
            $response = Http::get($pathao_info->url . '/api/v1/cities/' . $request->city_id . '/zone-list');
            $pathaozones = $response->json();
            return response()->json($pathaozones);
        } else {
            return response()->json([]);
        }
    }
    public function pathaozone(Request $request)
    {
        $pathao_info = Courierapi::where(['status' => 1, 'type' => 'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();
        if ($pathao_info) {
            $response = Http::get($pathao_info->url . '/api/v1/zones/' . $request->zone_id . '/area-list');
            $pathaoareas = $response->json();
            return response()->json($pathaoareas);
        } else {
            return response()->json([]);
        }
    }

    public function order_pathao(Request $request)
    {
        $orders_id = $request->order_ids;

        foreach ($orders_id as $order_id) {
            $order = Order::with('shipping')->find($order_id);
            $order_count = OrderDetails::select('order_id')->where('order_id', $order->id)->count();
            // return $request->all();
            // pathao
            $pathao_info = Courierapi::where(['status' => 1, 'type' => 'pathao'])->select('id', 'type', 'url', 'token', 'status')->first();
            if ($pathao_info) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $pathao_info->token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])->post($pathao_info->url . '/api/v1/orders', [
                    'store_id' => $request->pathaostore,
                    'merchant_order_id' => $order->invoice_id,
                    'sender_name' => 'Test',
                    'sender_phone' => $order->shipping ? $order->shipping->phone : '',
                    'recipient_name' => $order->shipping ? $order->shipping->name : '',
                    'recipient_phone' => $order->shipping ? $order->shipping->phone : '',
                    'recipient_address' => $order->shipping ? $order->shipping->address : '',
                    'recipient_city' => $request->pathaocity,
                    'recipient_zone' => $request->pathaozone,
                    'recipient_area' => $request->pathaoarea,
                    'delivery_type' => 48,
                    'item_type' => 2,
                    'special_instruction' => 'Special note- product must be check after delivery',
                    'item_quantity' => 1,
                    'item_weight' => 0.5,
                    'amount_to_collect' => round($order->amount),
                    'item_description' => 'Special note- product must be check after delivery',
                ]);
            }
            if ($response->status() == '200') {
                Toastr::success($response['data']['consignment_id'], 'Courier Tracking ID');
                return response()->json(['status' => 'success', 'message' => $response['data']['consignment_id'], 'Courier Tracking ID']);
            }
            Toastr::error($response['message'], 'Courier Order Faild');
            return response()->json(['status' => 'failed', 'message' => $response['message'], 'Courier Order Faild']);
        }
    }

    public function invoice($invoice_id)
    {
        $order = Order::where(['invoice_id' => $invoice_id])->with('orderdetails', 'payment', 'shipping', 'customer')->firstOrFail();
        return view('backEnd.order.invoice', compact('order'));
    }

    public function ticket($invoice_id)
    {
        $order = Order::where(['invoice_id' => $invoice_id])->with('orderdetails', 'payment', 'shipping', 'customer')->firstOrFail();
        return view('backEnd.order.slip', compact('order'));
    }

    public function process($invoice_id)
    {
        $data = Order::where(['invoice_id' => $invoice_id])->select('id', 'invoice_id', 'order_status')->with('orderdetails')->first();
        $shippingcharge = ShippingCharge::where('status', 1)->get();
        return view('backEnd.order.process', compact('data', 'shippingcharge'));
    }

    public function order_process(Request $request)
    {
        $link = OrderStatus::find($request->status)->slug;
        $order = Order::find($request->id);
        $courier = $order->order_status;
        $order->order_status = $request->status;
        $order->admin_note = $request->admin_note;
        $order->save();

        if ($request->status == 7) {
            $customer = Customer::select('id', 'savings')->find($order->customer_id);
            $customer->savings += $order->savings_pay;
            $customer->save();
        }


        $shipping_update = Shipping::where('order_id', $order->id)->first();
        $shippingfee = ShippingCharge::find($request->area);
        if ($shippingfee->name != $request->area) {
            if ($order->shipping_charge > $shippingfee->amount) {
                $total = $order->amount + ($shippingfee->amount - $order->shipping_charge);
                $order->shipping_charge = $shippingfee->amount;
                $order->amount = $total;
                $order->save();
            } else {
                $total = $order->amount + ($shippingfee->amount - $order->shipping_charge);
                $order->shipping_charge = $shippingfee->amount;
                $order->amount = $total;
                $order->save();
            }
        }

        $shipping_update->name = $request->name;
        $shipping_update->phone = $request->phone;
        $shipping_update->address = $request->address;
        $shipping_update->save();
        if ($request->status == 5 && $courier != 5) {
            $courier_info = Courierapi::where(['status' => 1, 'type' => 'steadfast'])->first();
            if ($courier_info) {
                $consignmentData = [
                    'invoice' => $order->invoice_id,
                    'recipient_name' => $order->shipping ? $order->shipping->name : 'InboxHat',
                    'recipient_phone' => $order->shipping ? $order->shipping->phone : '01750578495',
                    'recipient_address' => $order->shipping ? $order->shipping->address : '01750578495',
                    'cod_amount' => $order->amount
                ];
                $client = new Client();
                $response = $client->post('$courier_info->url', [
                    'json' => $consignmentData,
                    'headers' => [
                        'Api-Key' => '$courier_info->api_key',
                        'Secret-Key' => '$courier_info->secret_key',
                        'Accept' => 'application/json',
                    ],
                ]);

                $responseData = json_decode($response->getBody(), true);
            }
        }

        if ($request->status == 6) {
            $orders_details = OrderDetails::where('order_id', $order->id)->get();
            foreach ($orders_details as $order_detail) {

                if ($order_detail->type == 0) {
                    $product = Product::select('id', 'quantity')->find($order_detail->product_id);
                    $product->quantity -= $order_detail->qty;
                    $product->save();
                } else {
                    $product = ProductVariable::where(['product_id' => $order_detail->product_id, 'color' => $order_detail->product_color, 'size' => $order_detail->product_size])->first();
                    $product->stock -= $order_detail->qty;
                    $product->save();
                }
            }

            // affilate active
            $orders_amount = Order::where(['customer_id' => $order->customer_id, 'order_status' => 6])->sum('amount');
            if ($orders_amount > 1500) {
                $affialiate = Customer::select('id', 'name', 'is_affiliator')->find($order->customer_id);
                $affialiate->is_affiliator = 1;
                $affialiate->save();
            }
            // profit share system

            $netprofit = $order->amount - ($order->cod_charge + $order->purchase_amount);
            $customer = Customer::find($order->customer_id);
            $customer->profit += $order->amount;
            $customer->save();

            // level1 profit share
            $setting = GeneralSetting::select('company_profit', 'withdraw_ratio')->first();
            $level1_customer = Customer::where(['id' => $customer->refferal_1, 'is_affiliator' => 1, 'status' => 'active'])->first();
            if ($level1_customer && $setting->company_profit <= $level1_customer->profit) {

                $affiliate = AffiliateCommision::find(1);
                $amount = round(($netprofit * $affiliate->commision) / 100, 2) / 2;
                $level1_customer->balance += $amount;
                $level1_customer->savings += $amount;
                $level1_customer->save();

                $profit_save              = new CustomerProfit();
                $profit_save->customer_id = $level1_customer->id;
                $profit_save->order_id    = $order->id;
                $profit_save->profit      = round(($netprofit * $affiliate->commision) / 100, 2);
                $profit_save->level       = 1;
                $profit_save->status      = 1;
                $profit_save->save();
            }
            // level2 profit share
            $level2_customer = Customer::where(['id' => $level1_customer ? $level1_customer->refferal_1 : '', 'is_affiliator' => 1, 'status' => 'active'])->first();
            if ($level2_customer && $setting->company_profit <= $level2_customer->profit) {

                $affiliate = AffiliateCommision::find(2);
                $amount = round(($netprofit * $affiliate->commision) / 100, 2) / 2;
                $level2_customer->balance += $amount;
                $level2_customer->savings += $amount;
                $level2_customer->save();

                $profit_save              = new CustomerProfit();
                $profit_save->customer_id = $level2_customer->id;
                $profit_save->order_id    = $order->id;
                $profit_save->profit      = round(($netprofit * $affiliate->commision) / 100, 2);
                $profit_save->level       = 2;
                $profit_save->status      = 1;
                $profit_save->save();
            }

            // level3 profit share
            $level3_customer = Customer::where(['id' => $level2_customer ? $level2_customer->refferal_1 : '', 'is_affiliator' => 1, 'status' => 'active'])->first();
            if ($level3_customer && $setting->company_profit <= $level3_customer->profit) {

                $affiliate = AffiliateCommision::find(3);
                $amount = round(($netprofit * $affiliate->commision) / 100, 2) / 2;
                $level3_customer->balance += $amount;
                $level3_customer->savings += $amount;
                $level3_customer->save();

                $profit_save              = new CustomerProfit();
                $profit_save->customer_id = $level3_customer->id;
                $profit_save->order_id    = $order->id;
                $profit_save->profit      = round(($netprofit * $affiliate->commision) / 100, 2);
                $profit_save->level       = 3;
                $profit_save->status      = 1;
                $profit_save->save();
            }

            // level4 profit share
            $level4_customer = Customer::where(['id' => $level3_customer ? $level3_customer->refferal_1 : '', 'is_affiliator' => 1, 'status' => 'active'])->first();
            if ($level4_customer && $setting->company_profit <= $level4_customer->profit) {

                $affiliate = AffiliateCommision::find(4);
                $amount = round(($netprofit * $affiliate->commision) / 100, 2) / 2;
                $level4_customer->balance += $amount;
                $level4_customer->savings += $amount;
                $level4_customer->save();

                $profit_save              = new CustomerProfit();
                $profit_save->customer_id = $level4_customer->id;
                $profit_save->order_id    = $order->id;
                $profit_save->profit      = round(($netprofit * $affiliate->commision) / 100, 2);
                $profit_save->level       = 4;
                $profit_save->status      = 1;
                $profit_save->save();
            }
        }

        Toastr::success('Success', 'Order status change successfully');
        return redirect('admin/order/' . $link);
    }

    public function destroy(Request $request)
    {
        $order = Order::where('id', $request->id)->delete();
        $order_details = OrderDetails::where('order_id', $request->id)->delete();
        $shipping = Shipping::where('order_id', $request->id)->delete();
        $payment = Payment::where('order_id', $request->id)->delete();
        Toastr::success('Success', 'Order delete success successfully');
        return redirect()->back();
    }

    public function order_assign(Request $request)
    {
        $products = Order::whereIn('id', $request->input('order_ids'))->update(['user_id' => $request->user_id]);
        return response()->json(['status' => 'success', 'message' => 'Order user id assign']);
    }

    public function order_status(Request $request)
    {
        $orders = Order::whereIn('id', $request->input('order_ids'))->update(['order_status' => $request->order_status]);

        if ($request->order_status == 7) {
            $orders = Order::whereIn('id', $request->input('order_ids'))->get();
            foreach ($orders as $order) {
                $customer = Customer::select('id', 'savings')->find($order->customer_id);
                $customer->savings += $order->savings_pay;
                $customer->save();
            }
        }

        if ($request->order_status == 6) {
            $orders = Order::whereIn('id', $request->input('order_ids'))->get();

            foreach ($orders as $order) {

                $orders_details = OrderDetails::where('order_id', $order->id)->get();
                foreach ($orders_details as $order_detail) {

                    if ($order_detail->type == 0) {
                        $product = Product::select('id', 'quantity')->find($order_detail->product_id);
                        $product->quantity -= $order_detail->qty;
                        $product->save();
                    } else {
                        $product = ProductVariable::where(['product_id' => $order_detail->product_id, 'color' => $order_detail->product_color, 'size' => $order_detail->product_size])->first();
                        $product->stock -= $order_detail->qty;
                        $product->save();
                    }


                    // affilate active
                    if ($order_detail->package_pro == 1) {
                        $affialiate = Customer::select('id', 'name', 'is_affiliator')->find($order->customer_id);
                        $affialiate->is_affiliator = 1;
                        $affialiate->save();
                    }
                }

                // profit share system

                $netprofit = $order->amount - ($order->cod_charge + $order->purchase_amount);
                $customer = Customer::find($order->customer_id);
                $customer->profit += $order->amount;
                $customer->save();

                // level1 profit share
                $setting = GeneralSetting::select('company_profit', 'withdraw_ratio')->first();
                $level1_customer = Customer::where(['id' => $customer->refferal_1, 'is_affiliator' => 1, 'status' => 'active'])->first();
                if ($level1_customer && $setting->company_profit <= $level1_customer->profit) {

                    $affiliate = AffiliateCommision::find(1);
                    $amount = round(($netprofit * $affiliate->commision) / 100, 2);

                    $sav_amount = round(($amount * $setting->withdraw_ratio) / 100, 2);
                    $level1_customer->balance += $amount - $sav_amount;
                    $level1_customer->savings += $sav_amount;
                    $level1_customer->save();


                    $profit_save              = new CustomerProfit();
                    $profit_save->customer_id = $level1_customer->id;
                    $profit_save->order_id    = $order->id;
                    $profit_save->profit      = round(($netprofit * $affiliate->commision) / 100, 2);
                    $profit_save->level       = 1;
                    $profit_save->status      = 1;
                    $profit_save->save();
                }
                // level2 profit share
                $level2_customer = Customer::where(['id' => $level1_customer ? $level1_customer->refferal_1 : '', 'is_affiliator' => 1, 'status' => 'active'])->first();
                if ($level2_customer && $setting->company_profit <= $level2_customer->profit) {

                    $affiliate = AffiliateCommision::find(2);
                    $amount = round(($netprofit * $affiliate->commision) / 100, 2);

                    $sav_amount = round(($amount * $setting->withdraw_ratio) / 100, 2);
                    $level2_customer->balance += $amount - $sav_amount;
                    $level2_customer->savings += $sav_amount;

                    $level2_customer->save();

                    $profit_save              = new CustomerProfit();
                    $profit_save->customer_id = $level2_customer->id;
                    $profit_save->order_id    = $order->id;
                    $profit_save->profit      = round(($netprofit * $affiliate->commision) / 100, 2);
                    $profit_save->level       = 2;
                    $profit_save->status      = 1;
                    $profit_save->save();
                }

                // level3 profit share
                $level3_customer = Customer::where(['id' => $level2_customer ? $level2_customer->refferal_1 : '', 'is_affiliator' => 1, 'status' => 'active'])->first();
                if ($level3_customer && $setting->company_profit <= $level3_customer->profit) {

                    $affiliate = AffiliateCommision::find(3);
                    $amount = round(($netprofit * $affiliate->commision) / 100, 2);
                    $sav_amount = round(($amount * $setting->withdraw_ratio) / 100, 2);
                    $level3_customer->balance += $amount - $sav_amount;
                    $level3_customer->savings += $sav_amount;
                    $level3_customer->save();

                    $profit_save              = new CustomerProfit();
                    $profit_save->customer_id = $level3_customer->id;
                    $profit_save->order_id    = $order->id;
                    $profit_save->profit      = round(($netprofit * $affiliate->commision) / 100, 2);
                    $profit_save->level       = 3;
                    $profit_save->status      = 1;
                    $profit_save->save();
                }

                // level4 profit share
                $level4_customer = Customer::where(['id' => $level3_customer ? $level3_customer->refferal_1 : '', 'is_affiliator' => 1, 'status' => 'active'])->first();
                if ($level4_customer && $setting->company_profit <= $level4_customer->profit) {

                    $affiliate = AffiliateCommision::find(4);
                    $amount = round(($netprofit * $affiliate->commision) / 100, 2);
                    $sav_amount = round(($amount * $setting->withdraw_ratio) / 100, 2);
                    $level4_customer->balance += $amount - $sav_amount;
                    $level4_customer->savings += $sav_amount;
                    $level4_customer->save();

                    $profit_save              = new CustomerProfit();
                    $profit_save->customer_id = $level4_customer->id;
                    $profit_save->order_id    = $order->id;
                    $profit_save->profit      = round(($netprofit * $affiliate->commision) / 100, 2);
                    $profit_save->level       = 4;
                    $profit_save->status      = 1;
                    $profit_save->save();
                }
            }
        }
        return response()->json(['status' => 'success', 'message' => 'Order status change successfully']);
    }

    public function bulk_destroy(Request $request)
    {
        $orders_id = $request->order_ids;
        foreach ($orders_id as $order_id) {
            $order = Order::where('id', $order_id)->delete();
            $order_details = OrderDetails::where('order_id', $order_id)->delete();
            $shipping = Shipping::where('order_id', $order_id)->delete();
            $payment = Payment::where('order_id', $order_id)->delete();
        }
        return response()->json(['status' => 'success', 'message' => 'Order delete successfully']);
    }
    public function order_print(Request $request)
    {
        $orders = Order::whereIn('id', $request->input('order_ids'))->with('orderdetails', 'payment', 'shipping', 'customer')->get();
        $view = view('backEnd.order.print', ['orders' => $orders])->render();
        return response()->json(['status' => 'success', 'view' => $view]);
    }
    public function bulk_courier($slug, Request $request)
    {
        $courier_info = Courierapi::where(['status' => 1, 'type' => $slug])->first();
        if ($courier_info) {
            $orders_id = $request->order_ids;
            foreach ($orders_id as $order_id) {
                $order = Order::find($order_id);
                $courier = $order->order_status;
                if ($request->status == 5 && $courier != 5) {
                    $consignmentData = [
                        'invoice' => $order->invoice_id,
                        'recipient_name' => $order->shipping ? $order->shipping->name : 'InboxHat',
                        'recipient_phone' => $order->shipping ? $order->shipping->phone : '01750578495',
                        'recipient_address' => $order->shipping ? $order->shipping->address : '01750578495',
                        'cod_amount' => $order->amount
                    ];
                    $client = new Client();
                    $response = $client->post('$courier_info->url', [
                        'json' => $consignmentData,
                        'headers' => [
                            'Api-Key' => '$courier_info->api_key',
                            'Secret-Key' => '$courier_info->secret_key',
                            'Accept' => 'application/json',
                        ],
                    ]);

                    $responseData = json_decode($response->getBody(), true);
                    if ($responseData['status'] == 200) {
                        $message = 'Your order place to courier successfully';
                        $status = 'success';
                        $order->order_status = 4;
                        $order->save();
                    } else {
                        $message = 'Your order place to courier failed';
                        $status = 'failed';
                    }
                    return response()->json(['status' => $status, 'message' => $message]);
                }
            }
        } else {
            return "stop";
        }
    }
    public function order_create()
    {
        $products = Product::select('id', 'name', 'new_price', 'product_code')->where(['status' => 1])->get();
        $cartinfo  = Cart::instance('pos_shopping')->content();
        $shippingcharge = ShippingCharge::where('status', 1)->get();
        return view('backEnd.order.create', compact('products', 'cartinfo', 'shippingcharge'));
    }

    public function order_store(Request $request)
    {
        if ($request->guest_customer) {
            $this->validate($request, [
                'guest_customer' => 'required',
            ]);
            $customer = Customer::find($request->guest_customer);

            $area = ShippingCharge::where('pos', 1)->first();
            $name = $customer->name;
            $phone = $customer->phone;
            $address = $area->name;
            $area = $area->id;
        } else {
            $this->validate($request, [
                'name' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'area' => 'required',
            ]);
            $name = $request->name;
            $phone = $request->phone;
            $address = $request->name;
            $area = $request->area;
        }

        if (Cart::instance('pos_shopping')->count() <= 0) {
            Toastr::error('Your shopping empty', 'Failed!');
            return redirect()->back();
        }

        $subtotal = Cart::instance('pos_shopping')->subtotal();
        $subtotal = str_replace(',', '', $subtotal);
        $subtotal = str_replace('.00', '', $subtotal);
        $discount = Session::get('pos_discount') + Session::get('product_discount');
        $shippingfee  = ShippingCharge::find($area);

        $exits_customer = Customer::where('phone', $phone)->select('phone', 'id')->first();
        if ($exits_customer) {
            $customer_id = $exits_customer->id;
        } else {
            $password = rand(111111, 999999);
            $store              = new Customer();
            $store->name        = $name;
            $store->slug        = $name;
            $store->phone       = $phone;
            $store->password    = bcrypt($password);
            $store->verify      = 1;
            $store->status      = 'active';
            $store->save();
            $customer_id = $store->id;
        }

        // order data save
        $order                   = new Order();
        $order->invoice_id       = rand(11111, 99999);
        $order->amount           = ($subtotal + $shippingfee->amount) - $discount;
        $order->discount         = $discount ? $discount : 0;
        $order->shipping_charge  = $shippingfee->amount;
        $order->customer_id      =  $customer_id;
        $order->order_status     = 1;
        $order->note             = $request->note;
        $order->save();

        // shipping data save
        $shipping              =   new Shipping();
        $shipping->order_id    =   $order->id;
        $shipping->customer_id =   $customer_id;
        $shipping->name        =   $name;
        $shipping->phone       =   $phone;
        $shipping->address     =   $address;
        $shipping->area        =   $shippingfee->name;
        $shipping->save();

        // payment data save
        $payment                 = new Payment();
        $payment->order_id       = $order->id;
        $payment->customer_id    = $customer_id;
        $payment->payment_method = 'Cash On Delivery';
        $payment->amount         = $order->amount;
        $payment->payment_status = 'pending';
        $payment->save();

        // order details data save
        foreach (Cart::instance('pos_shopping')->content() as $cart) {
            $order_details                   =   new OrderDetails();
            $order_details->order_id         =   $order->id;
            $order_details->product_id       =   $cart->id;
            $order_details->product_name     =   $cart->name;
            $order_details->purchase_price   =   $cart->options->purchase_price;
            $order_details->product_discount =   $cart->options->product_discount;
            $order_details->sale_price       =   $cart->price;
            $order_details->qty              =   $cart->qty;
            $order_details->save();
        }
        Cart::instance('pos_shopping')->destroy();
        Session::forget('pos_shipping');
        Session::forget('pos_discount');
        Session::forget('product_discount');
        Toastr::success('Thanks, Your order place successfully', 'Success!');
        return redirect('admin/order/pending');
    }
    public function cart_add(Request $request)
    {
        $product = Product::select('id', 'name', 'stock', 'new_price', 'old_price', 'purchase_price', 'slug')->where(['id' => $request->id])->first();
        $qty = 1;
        $cartinfo = Cart::instance('pos_shopping')->add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $qty,
            'price' => $product->new_price,
            'options' => [
                'slug' => $product->slug,
                'image' => $product->image->image,
                'old_price' => $product->old_price,
                'purchase_price' => $product->purchase_price,
                'product_discount' => 0,
            ],
        ]);
        return response()->json(compact('cartinfo'));
    }
    public function cart_content()
    {
        $cartinfo = Cart::instance('pos_shopping')->content();
        return view('backEnd.order.cart_content', compact('cartinfo'));
    }
    public function cart_details()
    {
        $cartinfo = Cart::instance('pos_shopping')->content();
        $discount = 0;
        foreach ($cartinfo as $cart) {
            $discount += $cart->options->product_discount * $cart->qty;
        }
        Session::put('product_discount', $discount);
        return view('backEnd.order.cart_details', compact('cartinfo'));
    }
    public function cart_increment(Request $request)
    {
        $qty = $request->qty + 1;
        $cartinfo = Cart::instance('pos_shopping')->update($request->id, $qty);
        return response()->json($cartinfo);
    }
    public function cart_decrement(Request $request)
    {
        $qty = $request->qty - 1;
        $cartinfo = Cart::instance('pos_shopping')->update($request->id, $qty);
        return response()->json($cartinfo);
    }
    public function cart_remove(Request $request)
    {
        $remove = Cart::instance('pos_shopping')->remove($request->id);
        $cartinfo = Cart::instance('pos_shopping')->content();
        return response()->json($cartinfo);
    }
    public function product_discount(Request $request)
    {
        $discount = $request->discount;
        $cart = Cart::instance('pos_shopping')->content()->where('rowId', $request->id)->first();
        $cartinfo = Cart::instance('pos_shopping')->update($request->id, [
            'options' => [
                'slug' => $cart->options->slug,
                'image' => $cart->options->image,
                'old_price' => $cart->options->old_price,
                'purchase_price' => $cart->options->purchase_price,
                'product_discount' => $request->discount,
            ],
        ]);
        return response()->json($cartinfo);
    }
    public function cart_shipping(Request $request)
    {
        $shipping = ShippingCharge::where(['status' => 1, 'id' => $request->id])->first()->amount;
        Session::put('pos_shipping', $shipping);
        return response()->json($shipping);
    }

    public function cart_clear(Request $request)
    {
        $cartinfo = Cart::instance('pos_shopping')->destroy();
        Session::forget('pos_shipping');
        Session::forget('pos_discount');
        Session::forget('product_discount');
        return redirect()->back();
    }
    public function order_edit($invoice_id)
    {
        $products = Product::select('id', 'name', 'new_price', 'product_code')->where(['status' => 1])->get();
        $shippingcharge = ShippingCharge::where('status', 1)->get();
        $order = Order::where('invoice_id', $invoice_id)->first();
        $cartinfo  = Cart::instance('pos_shopping')->destroy();
        $shippinginfo  = Shipping::where('order_id', $order->id)->first();
        Session::put('product_discount', $order->discount);
        Session::put('pos_shipping', $order->shipping_charge);
        $orderdetails = OrderDetails::where('order_id', $order->id)->get();
        foreach ($orderdetails as $ordetails) {
            $cartinfo = Cart::instance('pos_shopping')->add([
                'id' => $ordetails->product_id,
                'name' => $ordetails->product_name,
                'qty' => $ordetails->qty,
                'price' => $ordetails->sale_price,
                'options' => [
                    'image' => $ordetails->image->image,
                    'purchase_price' => $ordetails->purchase_price,
                    'product_discount' => $ordetails->product_discount,
                    'details_id' => $ordetails->id,
                ],
            ]);
        }
        $cartinfo  = Cart::instance('pos_shopping')->content();
        return view('backEnd.order.edit', compact('products', 'cartinfo', 'shippingcharge', 'shippinginfo', 'order'));
    }

    public function order_update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'area' => 'required',
        ]);

        if (Cart::instance('pos_shopping')->count() <= 0) {
            Toastr::error('Your shopping empty', 'Failed!');
            return redirect()->back();
        }

        $subtotal = Cart::instance('pos_shopping')->subtotal();
        $subtotal = str_replace(',', '', $subtotal);
        $subtotal = str_replace('.00', '', $subtotal);
        $discount = Session::get('pos_discount') + Session::get('product_discount');
        $shippingfee  = ShippingCharge::find($request->area);

        $exits_customer = Customer::where('phone', $request->phone)->select('phone', 'id')->first();
        if ($exits_customer) {
            $customer_id = $exits_customer->id;
        } else {
            $password = rand(111111, 999999);
            $store              = new Customer();
            $store->name        = $request->name;
            $store->slug        = $request->name;
            $store->phone       = $request->phone;
            $store->password    = bcrypt($password);
            $store->verify      = 1;
            $store->status      = 'active';
            $store->save();
            $customer_id = $store->id;
        }

        // order data save
        $order                   =  Order::where('id', $request->order_id)->first();
        $order->invoice_id       = rand(11111, 99999);
        $order->amount           = ($subtotal + $shippingfee->amount) - $discount;
        $order->discount         = $discount ? $discount : 0;
        $order->shipping_charge  = $shippingfee->amount;
        $order->customer_id      =  $customer_id;
        $order->order_status     = 1;
        $order->note             = $request->note;
        $order->save();


        // shipping data save
        $shipping              =   Shipping::where('order_id', $request->order_id)->first();
        $shipping->order_id    =   $order->id;
        $shipping->customer_id =   $customer_id;
        $shipping->name        =   $request->name;
        $shipping->phone       =   $request->phone;
        $shipping->address     =   $request->address;
        $shipping->area        =   $shippingfee->name;
        $shipping->save();

        // payment data save
        $payment                 =  Payment::where('order_id', $request->order_id)->first();
        $payment->order_id       = $order->id;
        $payment->customer_id    = $customer_id;
        $payment->payment_method = 'Cash On Delivery';
        $payment->amount         = $order->amount;
        $payment->payment_status = 'pending';
        $payment->save();

        // order details data save
        foreach (Cart::instance('pos_shopping')->content() as $cart) {
            $exits = OrderDetails::where('id', $cart->options->details_id)->first();
            if ($exits) {
                $order_details                   =   OrderDetails::find($exits->id);
                $order_details->product_discount =   $cart->options->product_discount;
                $order_details->sale_price       =   $cart->price;
                $order_details->qty              =   $cart->qty;
                $order_details->save();
            } else {
                $order_details                   =   new OrderDetails();
                $order_details->order_id         =   $order->id;
                $order_details->product_id       =   $cart->id;
                $order_details->product_name     =   $cart->name;
                $order_details->purchase_price   =   $cart->options->purchase_price;
                $order_details->product_discount =   $cart->options->product_discount;
                $order_details->sale_price       =   $cart->price;
                $order_details->qty              =   $cart->qty;
                $order_details->save();
            }
        }
        Cart::instance('pos_shopping')->destroy();
        Session::forget('pos_shipping');
        Session::forget('pos_discount');
        Session::forget('product_discount');
        Toastr::success('Thanks, Your order place successfully', 'Success!');
        return redirect('admin/order/pending');
    }


    public function order_report(Request $request)
    {
        $users = User::where('status', 1)->get();
        $orders = OrderDetails::with('shipping', 'order')->whereHas('order', function ($query) {
            $query->where('order_status', 6);
        });
        if ($request->keyword) {
            $orders = $orders->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        if ($request->user_id) {
            $orders = $orders->whereHas('order', function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            });
        }
        if ($request->start_date && $request->end_date) {
            $orders = $orders->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
        $total_purchases = $orders->sum(DB::raw('purchase_price * qty'));
        $total_item = $orders->sum('qty');
        $total_sales = $orders->sum(DB::raw('sale_price * qty'));
        $orders = $orders->paginate(50);
        return view('backEnd.reports.order', compact('orders', 'users', 'total_purchases', 'total_item', 'total_sales'));
    }
    public function stock_report(Request $request)
    {
        $products = Product::select('id', 'name', 'new_price', 'quantity', 'type')
            ->where('status', 1);
        if ($request->keyword) {
            $products = $products->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        if ($request->category_id) {
            $products = $products->where('category_id', $request->category_id);
        }
        if ($request->start_date && $request->end_date) {
            $products = $products->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
        $total_purchase = $products->sum(DB::raw('purchase_price * quantity'));
        $total_stock = $products->sum('quantity');
        $total_price = $products->sum(DB::raw('new_price * quantity'));
        $products = $products->with('variables')->paginate(50);
        $categories = Category::where('status', 1)->get();
        return view('backEnd.reports.stock', compact('products', 'categories', 'total_purchase', 'total_stock', 'total_price'));
    }
    public function expense_report(Request $request)
    {
        $data = Expense::where('status', 1);
        if ($request->keyword) {
            $data = $data->where('name', 'LIKE', '%' . $request->keyword . "%");
        }
        if ($request->category_id) {
            $data = $data->where('expense_cat_id', $request->category_id);
        }
        if ($request->start_date && $request->end_date) {
            $data = $data->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
        $data = $data->paginate(50);
        $categories = ExpenseCategories::where('status', 1)->get();
        return view('backEnd.reports.expense', compact('data', 'categories'));
    }
    public function commision_report(Request $request)
    {
        $data = Customer::with([
            'orders' => function ($query) use ($request) {
                if ($request->start_date && $request->end_date) {
                    $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
                }
            },
            'profits' => function ($query) use ($request) {
                if ($request->start_date && $request->end_date) {
                    $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
                }
            }
        ])
        ->select('id', 'name', 'gender', 'age', 'profit', 'status', 'created_at', 'phone', 'bkash_no')
        ->where('status', 'active');

        if ($request->keyword) {
            $data = $data->where('phone', $request->keyword);
        }

        // Paginate the results
        $data = $data->paginate(50);

        return view('backEnd.reports.commision', compact('data'));
    }
    public function loss_profit(Request $request)
    {
        if ($request->start_date && $request->end_date) {
            $total_expense = Expense::where('status', 1)->whereBetween('created_at', [$request->start_date, $request->end_date])->sum('amount');
            $total_purchase = OrderDetails::whereHas('order', function ($query) use ($request) {
                $query->where('order_status', 6)
                    ->whereBetween('created_at', [$request->start_date, $request->end_date]);
            })->sum(DB::raw('purchase_price * qty'));

            $total_sales = OrderDetails::whereHas('order', function ($query) use ($request) {
                $query->where('order_status', 6)
                    ->whereBetween('created_at', [$request->start_date, $request->end_date]);
            })->sum(DB::raw('sale_price * qty'));
        } else {
            $total_expense = Expense::where('status', 1)->sum('amount');
            $total_purchase = OrderDetails::whereHas('order', function ($query) {
                $query->where('order_status', 6);
            })->sum(DB::raw('purchase_price * qty'));

            $total_sales = OrderDetails::whereHas('order', function ($query) {
                $query->where('order_status', 6);
            })->sum(DB::raw('sale_price * qty'));
        }


        return view('backEnd.reports.loss_profit', compact('total_expense', 'total_purchase', 'total_sales'));
    }
}
