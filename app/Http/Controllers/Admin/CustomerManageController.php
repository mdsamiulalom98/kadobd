<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\CustomerProfit;
use App\Models\Customer;
use App\Models\Withdraw;
use App\Models\Expense;
use App\Models\IpBlock;
use Toastr;
use Image;
use File;
use Auth;
use Hash;
class CustomerManageController extends Controller
{
    public function index(Request $request){
        if($request->keyword){
            $show_data = Customer::orWhere('phone',$request->keyword)->orWhere('name',$request->keyword)->paginate(50);
        }elseif ($request->start_date && $request->end_date) {
            $show_data = Customer::whereBetween('created_at', [$request->start_date,$request->end_date])->paginate(50);
        }else{
             $show_data = Customer::paginate(50);
        }
       
        return view('backEnd.customer.index',compact('show_data'));
    }

    public function edit($id){
        $edit_data = Customer::find($id);
        return view('backEnd.customer.edit',compact('edit_data'));
    }
    
    public function update(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);

        $input = $request->except('hidden_id');
        $update_data = Customer::find($request->hidden_id);
        // new password
        
        
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }

        // new image
        $image = $request->file('image');
        if($image){
            // image with intervention 
            $name =  time().'-'.$image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp',$name);
            $name = strtolower(preg_replace('/\s+/', '-', $name));
            $uploadpath = 'public/uploads/customer/';
            $imageUrl = $uploadpath.$name; 
            $img=Image::make($image->getRealPath());
            $img->encode('webp', 90);
            $width = 100;
            $height = 100;
            $img->height() > $img->width() ? $width=null : $height=null;
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($imageUrl);
            $input['image'] = $imageUrl;
            File::delete($update_data->image);
        }else{
            $input['image'] = $update_data->image;
        }
        $input['status'] = $request->status?1:0;
        $update_data->update($input);

        Toastr::success('Success','Data update successfully');
        return redirect()->route('customers.index');
    }
 
    public function inactive(Request $request){
        $inactive = Customer::find($request->hidden_id);
        $inactive->status = 'inactive';
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request){
        $active = Customer::find($request->hidden_id);
        $active->status = 'active';
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
     public function profile(Request $request){
        $profile = Customer::with('orders')->find($request->id);
        $step1 = Customer::where(['refferal_1'=>$profile->id])->select('id','name','phone','status','refferal_1')->get();
        $step2 = Customer::where(['refferal_2'=>$profile->id])->select('id','name','phone','status','refferal_2')->get();
        $step3 = Customer::where(['refferal_3'=>$profile->id])->select('id','name','phone','status','refferal_3')->get();
        $step4 = Customer::where(['refferal_4'=>$profile->id])->select('id','name','phone','status','refferal_4')->get();
        $profits = CustomerProfit::where(['customer_id'=>$profile->id])->get();
        $withdraws = Withdraw::where(['customer_id'=>$profile->id])->get();
        return view('backEnd.customer.profile',compact('profile','step1','step2','step3','step4','profits','withdraws'));
    }
     public function withdraw(Request $request){
        $this->validate($request, [
            'type_id' => 'required',
            'amount' => 'required',
        ]);


        $customer = Customer::find($request->customer_id);
        if($customer->balance < $request->amount){
            Toastr::error('Failed','Request amount not sufficient');
            return redirect()->back();
        }
        $customer->balance -= $request->amount;
        
        $input = $request->all();
        $input['status'] = 'paid';
        Withdraw::create($input);
        $customer->save();
        Toastr::success('Success','Withdraw paid successfully');
        return redirect()->back();

    }
    public function withdraw_manage($slug)
    {
        $data = Withdraw::where(['status'=>$slug])->with('customer')->paginate(20);
        return view('backEnd.customer.withdraw',compact('data'));
    }
    public function withdraw_process(Request $request)
    {
        $data = Withdraw::where(['id'=>$request->id])->with('customer')->first();
        return view('backEnd.customer.withdraw_process',compact('data'));
    }
    public function invoice(Request $request)
    {
        $data = Withdraw::where(['id'=>$request->id])->with('customer')->first();
        return view('backEnd.customer.invoice',compact('data'));
    }

    public function withdraw_change(Request $request)
    {
        $withdraw = Withdraw::where(['id'=>$request->id])->first();
        $withdraw->status = $request->status;
        $withdraw->admin_note = $request->admin_note??'';
        $withdraw->save();
        if($request->status == 'paid'){
            $customer = Customer::select('id','balance','name')->find($withdraw->customer_id);
            $customer->balance -= $withdraw->amount;
            $customer->save();

            $expense = new Expense();
            $expense->expense_cat_id = 4;
            $expense->amount = $withdraw->amount;
            $expense->name   = 'commision pay of '.$customer->name;
            $expense->date   = date('Y-m-d');
            $expense->note   = 'commision pay of '.$customer->name;
            $expense->status   = 'paid';
            $expense->save();
        }
        
        
        Toastr::success('Success','Withdraw status change successfully');
        return redirect()->back();
    }
    public function multiple_destroy(Request $request)
    {
        $orders_id = $request->order_ids;
        foreach ($orders_id as $order_id) {
            $order = Withdraw::where('id', $order_id)->delete();
        }
        return response()->json(['status' => 'success', 'message' => 'Order delete successfully']);
    }
     public function withdraw_change_multiple(Request $request)
    {
        $withdraws = Withdraw::whereIn('id', $request->input('order_ids'))->get();
        
        foreach ($withdraws as $withdraw) {
            $withdrawitem = Withdraw::find($withdraw->id);
            $withdrawitem->status = $request->status;
            $withdrawitem->admin_note = $request->admin_note??'';
            $withdrawitem->save();

            $customer = Customer::select('id','balance','name')->find($withdraw->customer_id);
            $customer->balance -= $withdraw->amount;
            $customer->save();

            $expense = new Expense();
            $expense->expense_cat_id = 4;
            $expense->amount = $withdraw->amount;
            $expense->name   = 'commision pay of '.$customer->name;
            $expense->date   = date('Y-m-d');
            $expense->note   = 'commision pay of '.$customer->name;
            $expense->status   = 'paid';
            $expense->save();

            }
        return response()->json(['status' => 'success', 'message' => 'Withdraw status changed successfully']);
    }
    
    public function adminlog(Request $request){
        $customer = Customer::find($request->hidden_id);
        Auth::guard('customer')->loginUsingId($customer->id);
        return redirect()->route('customer.account');
    }

    public function delete(Request $request){
       $customer = Customer::select('id','name')->find($request->hidden_id);
        foreach($customer->orders as $order){
            $order->orderdetails->each->delete();
            $order->payment->delete();
            $order->shipping->delete();
            $order->delete();
        }
        
        $customer->reviews->each->delete();
        $customer->profits->each->delete();
        $customer->withdraws->each->delete();
        
        $customer->delete();
        Toastr::success('Success','Customer all data delete successfully');
        return redirect()->back();
    }
    public function ip_block(Request $request){
        $data = IpBlock::get();
        return view('backEnd.reports.ipblock',compact('data'));
    }
    public function ipblock_store(Request $request){

        $store_data = new IpBlock();
        $store_data->ip_no = $request->ip_no;
        $store_data->reason = $request->reason;
        $store_data->save();
        Toastr::success('Success','IP address add successfully');
        return redirect()->back();
    }
    public function ipblock_update(Request $request){
        $update_data = IpBlock::find($request->id);
        $update_data->ip_no = $request->ip_no;
        $update_data->reason = $request->reason;
        $update_data->save();
        Toastr::success('Success','IP address update successfully');
        return redirect()->back();
    }
    public function ipblock_destroy(Request $request){
        $delete_data = IpBlock::find($request->id)->delete();
        Toastr::success('Success','IP address delete successfully');
        return redirect()->back();
    }
}
