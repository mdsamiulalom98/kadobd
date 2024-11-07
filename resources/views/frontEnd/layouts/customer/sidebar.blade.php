<div class="customer-auth">
    <div class="customer-img">
        <img src="{{asset(Auth::guard('customer')->user()->image)}}" alt="">
    </div>
    <div class="customer-name">
        <p><small>Hello</small></p>
        <p>{{Auth::guard('customer')->user()->name}}</p>
        @if(Auth::guard('customer')->user()->is_affiliator == 0)
        <a href="" class="unautorize">Unauthorised</a>
        @else
        <a class="autorize">Authorised </a>
        @endif
    </div>
</div>
<div class="sidebar-menu">
    <ul>
        <li><a href="{{route('customer.account')}}" class="{{request()->is('customer/account')?'active':''}}"><i data-feather="user"></i> My Account</a></li>
        <li><a href="{{route('customer.orders')}}" class="{{request()->is('customer/orders')?'active':''}}"><i data-feather="database"></i> My Order</a></li>
        <li><a href="{{route('customer.profile_edit')}}" class="{{request()->is('customer/profile-edit')?'active':''}}"><i data-feather="edit"></i> Profile Edit</a></li>
        <li><a href="{{route('customer.change_pass')}}" class="{{request()->is('customer/change-password')?'active':''}}"><i data-feather="lock"></i> Change Password</a></li>
        <li><a href="{{route('customer.balance')}}" class="{{request()->is('customer/balance')?'active':''}}"><i data-feather="credit-card"></i> My Balance</a></li>
        <li><a href="{{route('customer.refferals',['level'=>1])}}" class="{{request()->is('customer/referrals')?'active':''}}"><i data-feather="users"></i> My Referrals</a></li>
        <li><a href="{{route('customer.withdraws')}}" class="{{request()->is('customer/withdraws')?'active':''}}"><i data-feather="hard-drive"></i> Withdraw</a></li>
        <li><a href="{{ route('customer.logout') }}"
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();"><i data-feather="log-out"></i> Logout</a></li>
        <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </ul>
</div>