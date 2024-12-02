<div class="customer-auth">
    <div class="customer-img">
        <img src="{{asset(Auth::guard('customer')->user()->image)}}" alt="">
    </div>
    <div class="customer-name">
        @php
            $customer = \App\Models\Customer::with('cust_area')->find(Auth::guard('customer')->user()->id);
        @endphp
        <h6>{{Auth::guard('customer')->user()->name}}</h6>
        <div class="sidebar-cus-type">
            <p class="mt-1">
            @if(Auth::guard('customer')->user()->is_affiliate == 1)
                Affiliate Customer
            @else
                Customer
            @endif
            </p>
            @if(Auth::guard('customer')->user()->is_affiliate == 1)
             <div class="balance-btn">
                 <a href="" class="mt-1"><i data-feather="credit-card"></i>  {{Auth::guard('customer')->user()->balance}}৳</a>
             </div>
            @endif
        </div>
    </div>
</div>
<div class="sidebar-menu">
    @if($customer->affiliate_id)
        <div class="reseller-code mt-0 btn-grad">
            <span>Affiliate ID :</span><span id="resellercode">{{$customer->affiliate_id}}</span>
            <button onclick="copyResellerCode()"> <i class="fas fa-copy"></i>
            </button></p>
        </div>
    @endif
    <ul>
        <li><a href="{{route('customer.account')}}" class="{{request()->is('customer/account')?'active':''}}"><i data-feather="user"></i> My Account</a></li>
        <li><a href="{{route('customer.orders')}}" class="{{request()->is('customer/orders')?'active':''}}"><i data-feather="database"></i> My Order</a></li>
        @if(Auth::guard('customer')->user()->is_affiliate == 1)
        <li><a href="{{route('customer.commisions')}}" class="{{request()->is('customer/commissions')?'active':''}}"><i data-feather="database"></i> Total Earning</a></li>
        @endif
        <!-- -----withdorw-strat---- -->
        @if(Auth::guard('customer')->user()->is_affiliate == 1)
        <li>
            <a href="{{route('customer.withdraw')}}" class="{{request()->is('customer/withdraw')?'active':''}}">
                <i data-feather="credit-card"></i> My Withdraw</a>
        </li>
        @endif
        <!-- -----withdorw-end---- -->
        <li><a href="{{route('customer.profile_edit')}}" class="{{request()->is('customer/profile-edit')?'active':''}}"><i data-feather="edit"></i> Profile Edit</a></li>
        <li><a href="{{route('customer.change_pass')}}" class="{{request()->is('customer/change-password')?'active':''}}"><i data-feather="lock"></i> Change Password</a></li>
        <li><a href="{{ route('customer.logout') }}"
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();"><i data-feather="log-out"></i> Logout</a></li>
        <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
       @if($customer->is_affiliate == 1)
            <a href="">
                <div class="mt-2 aff-grad">
                    <p>Affiliate Activeted</p>
                </div>
            </a>
        @elseif($customer->is_affiliate == 0)
            <a href="{{route('customer.affiliate')}}">
                <div class="mt-2 aff-grad">
                    <p>Affiliate Request</p>
                </div>
            </a>
        @else($customer->is_affiliate == 2)
            <a href="{{route('customer.affiliate')}}">
                <div class="mt-2 aff-grad">
                    <p>Pending Affiliate</p>
                </div>
            </a>
        @endif

    </ul>
</div>