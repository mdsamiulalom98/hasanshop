<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Affiliate;
use App\Models\Customer;
use Toastr;
use Image;
use File;
use DB;

class AffiliateController extends Controller
{

    public function index(Request $request){
        $show_data = Affiliate::latest()->where('status',$request->status)->paginate(50);
        return view('backEnd.affiliate.index',compact('show_data'));
    }
    public function details(Request $request){
        $show_data = Affiliate::find($request->id);
        //return $show_data;
        return view('backEnd.affiliate.details',compact('show_data'));
    }
    public function request_status(Request $request){
        $affiliate = Affiliate::find($request->id);
        $affiliate->status = $request->status;
        $affiliate->save();

        if($request->status = 'active'){
            $affiliate_id = $this->resellerGenerate();
            $customer = Customer::find($affiliate->customer_id);
            $customer->is_affiliate =  1;
            $customer->affiliate_id = $affiliate_id;
            $customer->save();
        }

        Toastr::success('Success','Data active successfully');
        return back();
    }
    public function resellerGenerate() {
        $max_affiliate = DB::table('customers')->max(DB::raw('CAST(affiliate_id AS UNSIGNED)'));
        return $max_affiliate ? $max_affiliate+'1':'100001';
    }
}
