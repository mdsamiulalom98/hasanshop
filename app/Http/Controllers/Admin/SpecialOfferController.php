<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SpecialOffer;
use Image;
use Toastr;
use Str;
use File;

class SpecialOfferController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:slider-list|slider-create|slider-edit|slider-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:slider-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:slider-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:slider-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $show_data = SpecialOffer::orderBy('id', 'DESC')->get();
        return view('backEnd.specialoffer.index', compact('show_data'));
    }
    public function create()
    {
        $products = Product::where(['status'=>1])->select('id','name','status')->get();
        return view('backEnd.specialoffer.create',compact('products'));
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'status' => 'required',
        ]);
        // return $request->all();
        // dd($input);
        $input = $request->all();
        SpecialOffer::create($input);
        Toastr::success('Success', 'Data insert successfully');
        return redirect()->route('specialoffer.index');
    }

    public function edit($id)
    {
        $edit_data = SpecialOffer::find($id);
        $select_products = Product::where('campaign_id',$id)->get();
        $products = Product::where(['status'=>1])->select('id','name','status')->get();
        return view('backEnd.specialoffer.edit', compact('edit_data', 'products','select_products'));
    }

    public function update(Request $request)
    { 
        $this->validate($request, [
            'status' => 'required',
        ]);
        // image one
        $update_data = SpecialOffer::find($request->hidden_id);
        $input = $request->except('hidden_id');
        // dd($input);
        $update_data->update($input);

        Toastr::success('Success', 'Data update successfully');
        return redirect()->route('specialoffer.index');
    }


    public function inactive(Request $request)
    {
        $inactive = SpecialOffer::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success', 'Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = SpecialOffer::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success', 'Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        $delete_data = SpecialOffer::find($request->hidden_id);
        $delete_data->delete();
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->back();
    }
}
