<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AffiliateCommision;
use Toastr;
use Image;
use File;
use Str;
class AffiliateCommisionController extends Controller
{

    public function index(Request $request)
    {
        $data = AffiliateCommision::orderBy('id','DESC')->get();
        return view('backEnd.commision.index',compact('data'));
    }
    public function create()
    {
        $categories = AffiliateCommision::orderBy('id','DESC')->select('id','name')->get();
        return view('backEnd.commision.create',compact('categories'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);

        $input = $request->all();
        AffiliateCommision::create($input);
        Toastr::success('Success','Data insert successfully');
        return redirect()->route('commisions.index');
    }
    
    public function edit($id)
    {
        $edit_data = AffiliateCommision::find($id);
        return view('backEnd.commision.edit',compact('edit_data'));
    }
    
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $update_data = AffiliateCommision::find($request->id);
        $input = $request->all();
        $input['status'] = $request->status?1:0;
        $update_data->update($input);
        Toastr::success('Success','Data update successfully');
        return redirect()->route('commisions.index');
    }
 
    public function inactive(Request $request)
    {
        $inactive = AffiliateCommision::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success','Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = AffiliateCommision::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success','Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $delete_data = AffiliateCommision::find($request->hidden_id);
        $delete_data->delete();
        Toastr::success('Success','Data delete successfully');
        return redirect()->back();
    }
}
