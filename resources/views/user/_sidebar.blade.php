<div class="card border-0 shadow-sm">
    <div class="card-body text-center border-bottom">
        <div class="text-primary mb-2">
            <i class="fa-solid fa-circle-user fa-3x"></i>
        </div>
        <div class="fw-bold">{{ Auth::user()->name }}</div>
        <div class="small text-muted">{{ Auth::user()->email }}</div>
    </div>
    <div class="list-group list-group-flush small">
        <a href="{{ route('user.home') }}"
           class="list-group-item list-group-item-action {{ request()->routeIs('user.home') ? 'active' : '' }}">
            <i class="fa-solid fa-house-user fa-fw me-2"></i> Tổng quan tài khoản
        </a>
        <a href="{{ route('user.donhang') }}"
           class="list-group-item list-group-item-action {{ request()->routeIs('user.donhang*') ? 'active' : '' }}">
            <i class="fa-solid fa-box-open fa-fw me-2"></i> Lịch sử đơn hàng
        </a>
        <a href="{{ route('user.hoso') }}"
           class="list-group-item list-group-item-action {{ request()->routeIs('user.hoso') ? 'active' : '' }}">
            <i class="fa-solid fa-user-gear fa-fw me-2"></i> Hồ sơ & Đổi mật khẩu
        </a>
        <a href="{{ route('frontend.giohang') }}" class="list-group-item list-group-item-action">
            <i class="fa-solid fa-cart-shopping fa-fw me-2"></i> Giỏ hàng ({{ \Gloudemans\Shoppingcart\Facades\Cart::count() }})
        </a>
        <a href="{{ route('logout') }}"
           class="list-group-item list-group-item-action text-danger"
           onclick="event.preventDefault(); document.getElementById('user-sidebar-logout').submit();">
            <i class="fa-solid fa-right-from-bracket fa-fw me-2"></i> Đăng xuất
        </a>
        <form id="user-sidebar-logout" action="{{ route('logout') }}" method="post" class="d-none">
            @csrf
        </form>
    </div>
</div>