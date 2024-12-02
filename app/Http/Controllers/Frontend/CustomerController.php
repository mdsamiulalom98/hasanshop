<?php

namespace App\Http\Controllers\Frontend;

use shurjopayv2\ShurjopayLaravelPackage8\Http\Controllers\ShurjopayController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Gloudemans\Shoppingcart\Facades\Cart;
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
use App\Models\CouponCode;
use App\Models\Affiliate;
use App\Models\SellerWithdraw;
use App\Models\Thana;
use Carbon\Carbon;
use Mail;

class CustomerController extends Controller
{

    function __construct()
    {
        $this->middleware('customer', ['except' => ['register', 'customer_coupon', 'coupon_remove', 'store', 'verify', 'resendotp', 'account_verify', 'login', 'signin', 'logout', 'checkout', 'forgot_password', 'forgot_verify', 'forgot_reset', 'forgot_store', 'forgot_resend', 'order_save', 'order_success', 'order_track', 'order_track_result']]);
    }
    public function customer_coupon(Request $request)
    {
        $findcoupon = CouponCode::where('coupon_code', $request->coupon_code)->first();
        if ($findcoupon == NULL) {
            Toastr::error('Opps! your entered promo code is not valid');
            return back();
        } else {
            $currentdata = date('Y-m-d');
            $expiry_date = $findcoupon->expiry_date;
            if ($currentdata <= $expiry_date) {
                $totalcart = Cart::instance('shopping')->subtotal();
                $totalcart = str_replace('.00', '', $totalcart);
                $totalcart = str_replace(',', '', $totalcart);
                if ($totalcart >= $findcoupon->buy_amount) {
                    if ($totalcart >= $findcoupon->buy_amount) {
                        if ($findcoupon->offer_type == 1) {
                            $discountammount =  (($totalcart * $findcoupon->amount) / 100);
                            Session::forget('coupon_amount');
                            Session::put('coupon_amount', $discountammount);
                            Session::put('coupon_used', $findcoupon->coupon_code);
                        } else {
                            Session::put('coupon_amount', $findcoupon->amount);
                            Session::put('coupon_used', $findcoupon->coupon_code);
                        }
                        Toastr::success('Success! your promo code accepted');
                        return back();
                    }
                } else {
                    Toastr::error('You need to buy a minimum of ' . $findcoupon->buy_amount . ' Taka to get the offer');
                    return back();
                }
            } else {
                Toastr::error('Opps! Sorry your promo code date expaire');
                return back();
            }
        }
    }
    public function coupon_remove(Request $request)
    {
        Session::forget('coupon_amount');
        Session::forget('coupon_used');
        Session::forget('discount');
        Toastr::success('Success', 'Your coupon remove successfully');
        return back();
    }

    public function review(Request $request)
    {
        $this->validate($request, [
            'ratting' => 'required',
            'review' => 'required',
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

    public function login()
    {
        return view('frontEnd.layouts.customer.login');
    }


    public function signin(Request $request){
        $auth_check = Customer::where('phone',$request->phone)->first();
        if($auth_check){
            if ($auth_check->status == 'active') {

                if (Auth::guard('customer')->attempt(['phone' => $request->phone, 'password' => $request->password])) {
                    Toastr::success('You are login successfully', 'success!');
                    if(Cart::instance('shopping')->count() > 0){
                        return redirect()->route('customer.account');
                    }
                    return redirect()->intended('customer/account');
                }


                Toastr::error('message', 'Opps! your phone or password wrong');
                return redirect()->back();
            }

            Toastr::error('message', 'Please wait your reseller account has been pending ');
            return redirect()->back();
        }else{
            Toastr::error('message', 'Sorry! You have no account');
            return redirect()->back();
        }
    }





    public function register()
    {
        return view('frontEnd.layouts.customer.register');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'    => 'required',
            'phone'    => 'required|unique:customers',
            'password' => 'required|min:6'
        ]);


        $last_id = Customer::orderBy('id', 'desc')->first();
        $last_id = $last_id ? $last_id->id + 1 : 1;
        $store              = new Customer();
        $store->name        = $request->name;
        $store->slug        = strtolower(Str::slug($request->name . '-' . $last_id));
        $store->phone       = $request->phone;
        $store->email       = $request->email;
        $store->password    = bcrypt($request->password);
        $store->is_affiliate = 0;
        $store->verify      = 1;
        $store->status      = 'active';
        //return $store;
        $store->save();

        Toastr::success('Success', 'Account Create Successfully');
        return redirect()->route('customer.login');
    }
    public function verify()
    {
        return view('frontEnd.layouts.customer.verify');
    }
    public function resendotp(Request $request)
    {
        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();
        $customer_info->verify = rand(1111, 9999);
        $customer_info->save();
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where('status', 1)->first();
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "number" => $customer_info->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "message" => "Dear $customer_info->name!\r\nYour account verify OTP is $customer_info->verify \r\nThank you for using $site_setting->name"
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
        Toastr::success('Success', 'Resend code send successfully');
        return redirect()->back();
    }
    public function account_verify(Request $request)
    {
        $this->validate($request, [
            'otp' => 'required',
        ]);
        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();
        if ($customer_info->verify != $request->otp) {
            Toastr::error('Success', 'Your OTP not match');
            return redirect()->back();
        }

        $customer_info->verify = 1;
        $customer_info->status = 'active';
        $customer_info->save();
        Auth::guard('customer')->loginUsingId($customer_info->id);
        return redirect()->route('customer.account');
    }
    public function forgot_password()
    {
        return view('frontEnd.layouts.customer.forgot_password');
    }

    public function forgot_verify(Request $request)
    {
        $customer_info = Customer::where('phone', $request->phone)->first();
        if (!$customer_info) {
            Toastr::error('Your phone number not found');
            return back();
        }
        $customer_info->forgot = rand(1111, 9999);
        $customer_info->save();
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where(['status' => 1, 'forget_pass' => 1])->first();
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "number" => $customer_info->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "message" => "Dear $customer_info->name!\r\nYour forgot password verify OTP is $customer_info->forgot \r\nThank you for using $site_setting->name"
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

        session::put('verify_phone', $request->phone);
        Toastr::success('Your account register successfully');
        return redirect()->route('customer.forgot.reset');
    }

    public function forgot_resend(Request $request)
    {
        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();
        $customer_info->forgot = rand(1111, 9999);
        $customer_info->save();
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where(['status' => 1])->first();
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "number" => $customer_info->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "message" => "Dear $customer_info->name!\r\nYour forgot password verify OTP is $customer_info->forgot \r\nThank you for using $site_setting->name"
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

        Toastr::success('Success', 'Resend code send successfully');
        return redirect()->back();
    }
    public function forgot_reset()
    {
        if (!Session::get('verify_phone')) {
            Toastr::error('Something wrong please try again');
            return redirect()->route('customer.forgot.password');
        };
        return view('frontEnd.layouts.customer.forgot_reset');
    }
    public function forgot_store(Request $request)
    {
        $customer_info = Customer::where('phone', session::get('verify_phone'))->first();

        if ($customer_info->forgot != $request->otp) {
            Toastr::error('Success', 'Your OTP not match');
            return redirect()->back();
        }

        $customer_info->forgot = 1;
        $customer_info->password = bcrypt($request->password);
        $customer_info->save();
        if (Auth::guard('customer')->attempt(['phone' => $customer_info->phone, 'password' => $request->password])) {
            Session::forget('verify_phone');
            Toastr::success('You are login successfully', 'success!');
            return redirect()->intended('customer/account');
        }
    }
    public function account()
    {
        return view('frontEnd.layouts.customer.account');
    }
     public function commisions()
    {
        $userId = Auth::guard('customer')->user()->id;
        $commissions = Order::where(['affiliate_id'=>$userId,'order_status'=>6])->latest()->paginate(10);
        $totalCommission = Order::where(['affiliate_id'=>$userId,'order_status'=>6])->sum('commision');

        return view('frontEnd.layouts.customer.commisions', compact('commissions', 'totalCommission'));
    }
    // withdraw system start
    public function withdraw()
    {

        $withdraws = SellerWithdraw::where(['seller_id' => Auth::guard('customer')->user()->id])->latest()->paginate(20);
        return view('frontEnd.layouts.customer.withdraw', compact('withdraws'));
    }

    public function withdraw_request(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required',
            'receive' => 'required',
            'method' => 'required',
            'note' => 'required',
        ]);
        $pending_amount = SellerWithdraw::where(['status' => 'pending', 'seller_id' => Auth::guard('customer')->user()->id])->sum('amount');
        $balance_check = (Auth::guard('customer')->user()->balance - ($request->amount + $pending_amount));

        if (!Hash::check($request->password, Auth::guard('customer')->user()->password)) {
            Toastr::error('Your password is wrong', 'Failed');
            return redirect()->back();
        }
        if (Auth::guard('customer')->user()->balance < ($request->amount + $pending_amount)) {
            Toastr::error('Withdraw balance unsificient', 'Low Balance');
            return redirect()->back();
        }

        // Check if the user's balance is less than 200
        if ($balance_check < 100) {
            Toastr::error('Your balance must be at least 200 to make a withdrawal request.', 'Low Balance');
            return redirect()->back();
        }

        $withdraw = new SellerWithdraw();
        $withdraw->seller_id = Auth::guard('customer')->user()->id;
        $withdraw->amount  = $request->amount;
        $withdraw->receive = $request->receive;
        $withdraw->method  = $request->method;
        $withdraw->note    = $request->note;
        $withdraw->request_date = Carbon::now();
        $withdraw->status = 'pending';
        //return $withdraw;
        $withdraw->save();

        Toastr::success('Withdraw request send successfully', 'success');
        return redirect()->back();
    }
    // withdraw system end
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        Toastr::success('You are logout successfully', 'success!');
        return redirect()->route('customer.login');
    }
    public function checkout()
    {
        $shippingcharge = ShippingCharge::where(['status' => 1, 'pos' => 0])->get();
        $select_charge = ShippingCharge::where(['status' => 1, 'pos' => 0])->first();
        $bkash_gateway = PaymentGateway::where(['status' => 1, 'type' => 'bkash'])->first();
        $shurjopay_gateway = PaymentGateway::where(['status' => 1, 'type' => 'shurjopay'])->first();
        $customer = Auth::guard('customer')->user();
        if (Session::get('free_shipping') == 1) {
            Session::put('shipping', 0);
        } else {
            Session::put('shipping', $select_charge->amount);
        }
        $districts = District::select('id', 'district_name')->orderBy('id', 'asc')->get();
        $areas = Thana::where(['district_id' => $customer->district])->select('thana_name', 'id')->get();
        return view('frontEnd.layouts.customer.checkout', compact('shippingcharge', 'bkash_gateway','shurjopay_gateway', 'districts', 'areas'));
    }
    public function order_save(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if (Cart::instance('shopping')->count() <= 0) {
            Toastr::error('Your shopping empty', 'Failed!');
            return redirect()->back();
        }

        $subtotal = Cart::instance('shopping')->subtotal();
        $subtotal = str_replace(',', '', $subtotal);
        $subtotal = str_replace('.00', '', $subtotal);
        $discount = Session::get('discount') + Session::get('coupon_amount');
        $shippingfee  = Session::get('free_shipping') ? 0 : Session::get('shipping');
        $amount = ($subtotal + $shippingfee) - $discount;

        $shipping_area  = Thana::where('id', $request->upazila)->first();
        $shipping_district = District::find($request->district);
        if (Auth::guard('customer')->user()) {
            $customer_id = Auth::guard('customer')->user()->id;
        } else {
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
        }

        $affiliate_id = Auth::guard('customer')->user() && Auth::guard('customer')->user()->is_affiliate == 1 ? Auth::guard('customer')->user()->affiliate_id : $request->affiliate_id;
        if($affiliate_id){
            $affiliate_info = Customer::where(['affiliate_id'=>$affiliate_id, 'status'=>'active'])->first();
        }else{
          $affiliate_info = NULL;
        }
        $total_commision = 0;
        if($affiliate_info){
            foreach(Cart::instance('shopping')->content() as $cart){
                $total_commision      +=  $cart->options->commision*$cart->qty;
            }
        }

        // order data save
        $order                   = new Order();
        $order->invoice_id       = rand(11111, 99999);
        $order->amount           = $amount;
        $order->discount         = $discount ? $discount : 0;
        $order->shipping_charge  = $shippingfee;
        $order->customer_id      = $customer_id;
        $order->affiliate_id     = $affiliate_info != NULL ? $affiliate_info->id : NULL;
        $order->customer_ip      = $request->ip();
        $order->order_type       = 'website';
        $order->order_status     = 1;
        $order->commision        = $total_commision;
        $order->note             = $request->note;
        $order->save();

        // shipping data save
        $shipping              =   new Shipping();
        $shipping->order_id    =   $order->id;
        $shipping->customer_id =   $customer_id;
        $shipping->name        =   $request->name;
        $shipping->phone       =   $request->phone;
        $shipping->address     =   $request->address;
        $shipping->area        =   $shipping_area->thana_name ?? 'Shipping Area';
        $shipping->district    =   $shipping_district->district_name ?? 'Shipping District';
        $shipping->save();

        // payment data save
        $payment                 = new Payment();
        $payment->order_id       = $order->id;
        $payment->customer_id    = $customer_id;
        $payment->payment_method = $request->payment_method;
        $payment->amount         = $order->amount;
        $payment->payment_status = 'pending';
        $payment->save();

        // order details data save
        foreach (Cart::instance('shopping')->content() as $cart) {
            // return $cart;
            $order_details                  =   new OrderDetails();
            $order_details->order_id        =   $order->id;
            $order_details->product_id      =   $cart->id;
            $order_details->product_name    =   $cart->name;
            $order_details->sale_price      =   $cart->price;
            $order_details->purchase_price  =   $cart->options->purchase_price;
            $order_details->product_color   =   $cart->options->product_color;
            $order_details->product_size    =   $cart->options->product_size;
            $order_details->product_size    =   $cart->options->product_size;
            $order_details->product_type    =   $cart->options->type;
            $order_details->commision       =   $total_commision;
            $order_details->qty             =   $cart->qty;
            $order_details->save();
        }

        Cart::instance('shopping')->destroy();
        Session::forget('free_shipping');
        Session::put('purchase_event', 'true');

        Toastr::success('Thanks, Your order place successfully', 'Success!');
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where(['status' => 1, 'order' => '1'])->first();
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "number" => $request->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "message" => "Dear $request->name!\r\nYour order ($order->invoice_id) has been successfully placed. Track your order https://shoppingghor.com/customer/order-track and Total Bill $order->amount\r\nThank you for using $site_setting->name"
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

        if ($request->payment_method == 'bkash') {
            return redirect('/bkash/checkout-url/create?order_id=' . $order->id);
        } elseif ($request->payment_method == 'shurjopay') {
            $info = array(
                'currency' => "BDT",
                'amount' => $order->amount,
                'order_id' => uniqid(),
                'discsount_amount' => 0,
                'disc_percent' => 0,
                'client_ip' => $request->ip(),
                'customer_name' =>  $request->name,
                'customer_phone' => $request->phone,
                'email' => "customer@gmail.com",
                'customer_address' => $request->address,
                'customer_city' => $request->area,
                'customer_state' => $request->area,
                'customer_postcode' => "1212",
                'customer_country' => "BD",
                'value1' => $order->id
            );
            $shurjopay_service = new ShurjopayController();
            return $shurjopay_service->checkout($info);
        } else {
            return redirect('customer/order-success/' . $order->id);
        }
    }

    public function orders()
    {
        $orders = Order::where('customer_id', Auth::guard('customer')->user()->id)->with('status')->latest()->get();
        return view('frontEnd.layouts.customer.orders', compact('orders'));
    }
    public function order_success($id)
    {
        $order = Order::where('id', $id)->firstOrFail();
        return view('frontEnd.layouts.customer.order_success', compact('order'));
    }
    public function invoice(Request $request)
    {
        $order = Order::where(['id' => $request->id, 'customer_id' => Auth::guard('customer')->user()->id])->with('orderdetails', 'payment', 'shipping', 'customer')->firstOrFail();

        $orders = Order::where(['id' => $request->id, 'customer_id' => Auth::guard('customer')->user()->id])->with('orderdetails')->first();
        // return $orders;
        return view('frontEnd.layouts.customer.invoice', compact('order','orders'));
    }
    public function pdfreader(Request $request)
    {
        $order = Order::where(['id' => $request->id, 'customer_id' => Auth::guard('customer')->user()->id])->with('orderdetails', 'payment', 'shipping', 'customer')->firstOrFail();

        $orders = Order::where(['id' => $request->id, 'customer_id' => Auth::guard('customer')->user()->id])->with('orderdetails')->first();
        // return $orders;
        return view('frontEnd.layouts.customer.pdfreader', compact('order','orders'));
    }


    public function order_note(Request $request)
    {
        $order = Order::where(['id' => $request->id, 'customer_id' => Auth::guard('customer')->user()->id])->firstOrFail();
        return view('frontEnd.layouts.customer.order_note', compact('order'));
    }
    public function profile_edit(Request $request)
    {
        $profile_edit = Customer::where(['id' => Auth::guard('customer')->user()->id])->firstOrFail();
        $districts = District::select('id', 'district_name')->get();
        $areas = Thana::select('id','thana_name')->get();
        return view('frontEnd.layouts.customer.profile_edit', compact('profile_edit', 'districts', 'areas'));
    }
    public function profile_update(Request $request)
    {
        $update_data = Customer::where(['id' => Auth::guard('customer')->user()->id])->firstOrFail();

        $image = $request->file('image');
        if ($image) {
            // image with intervention
            $name =  time() . '-' . $image->getClientOriginalName();
            $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $name);
            $name = strtolower(Str::slug($name));
            $uploadpath = 'public/uploads/customer/';
            $imageUrl = $uploadpath . $name;
            $img = Image::make($image->getRealPath());
            $img->encode('webp', 90);
            $width = 120;
            $height = 120;
            $img->resize($width, $height);
            $img->save($imageUrl);
        } else {
            $imageUrl = $update_data->image;
        }

        $update_data->name        =   $request->name;
        $update_data->phone       =   $request->phone;
        $update_data->email       =   $request->email;
        $update_data->address     =   $request->address;
        $update_data->district    =   $request->district;
        $update_data->area        =   $request->area;
        $update_data->image       =   $imageUrl;
        $update_data->save();

        Toastr::success('Your profile update successfully', 'Success!');
        return redirect()->route('customer.account');
    }

    public function affiliate_store(Request $request){

        $image = $request->file('nid1');
        $name =  time().'-'.$image->getClientOriginalName();
        $name = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp',$name);
        $name = strtolower(preg_replace('/\s+/', '-', $name));
        $uploadpath = 'public/uploads/settings/';
        $imageUrl = $uploadpath.$name;
        $img=Image::make($image->getRealPath());
        $img->encode('webp', 90);
        $width = '';
        $height = '';
        $img->height() > $img->width() ? $width=null : $height=null;
        $img->resize($width, $height);
        $img->save($imageUrl);

        // dark logo
        $image2 = $request->file('nid2');
        $name2 =  time().'-'.$image2->getClientOriginalName();
        $name2 = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp',$name2);
        $name2 = strtolower(preg_replace('/\s+/', '-', $name2));
        $uploadpath2 = 'public/uploads/settings/';
        $image2Url = $uploadpath2.$name2;
        $img2=Image::make($image2->getRealPath());
        $img2->encode('webp', 90);
        $width2 = '';
        $height2 = '';
        $img2->height() > $img2->width() ? $width2=null : $height2=null;
        $img2->resize($width2, $height2);
        $img2->save($image2Url);

        $store['nid1'] = $imageUrl;
        $store['nid2'] = $image2Url;

        $customer_info = Customer::select('id')->where(['id' => Auth::guard('customer')->user()->id])->firstOrFail();

        $store = new Affiliate();
        $store->name        = $request->name;
        $store->customer_id = $customer_info->id;
        $store->phone       = $request->phone;
        $store->email       = $request->email;
        $store->address     = $request->address;
        $store->district    = $request->district;
        $store->area        = $request->area;
        $store->frontnid    = $imageUrl;
        $store->backnid     = $image2Url;
        $store->status      = 'pending';
        $store->save();
        // return $store;

        $customer_info->is_affiliate = 2;
        $customer_info->save();

        Toastr::success('Success', 'Account Request Successfully');
        return redirect()->route('customer.account');
    }

    public function affiliate(Request $request)
    {
        $profile_edit = Customer::where(['id' => Auth::guard('customer')->user()->id])->firstOrFail();
        $districts = District::distinct()->select('district')->get();
        $areas = District::where(['district' => $profile_edit->district])->select('area_name', 'id')->get();
        return view('frontEnd.layouts.customer.affiliate', compact('profile_edit', 'districts', 'areas'));
    }

    public function order_track()
    {
        return view('frontEnd.layouts.customer.order_track');
    }

    public function order_track_result(Request $request)
    {

        $phone = $request->phone;
        $invoice_id = $request->invoice_id;

        if ($phone != null && $invoice_id == null) {
            $order = DB::table('orders')
                ->join('shippings', 'orders.id', '=', 'shippings.order_id')
                ->where(['shippings.phone' => $request->phone])
                ->get();
        } else if ($invoice_id && $phone) {
            $order = DB::table('orders')
                ->join('shippings', 'orders.id', '=', 'shippings.order_id')
                ->where(['orders.invoice_id' => $request->invoice_id, 'shippings.phone' => $request->phone])
                ->get();
        }

        if ($order->count() == 0) {

            Toastr::error('message', 'Something Went Wrong !');
            return redirect()->back();
        }

        //   return $order->count();

        return view('frontEnd.layouts.customer.tracking_result', compact('order'));
    }


    public function change_pass()
    {
        return view('frontEnd.layouts.customer.change_password');
    }



    public function password_update(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required',
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
        } else {
            Toastr::error('Failed', 'Old password not match!');
            return redirect()->back();
        }
    }
}
