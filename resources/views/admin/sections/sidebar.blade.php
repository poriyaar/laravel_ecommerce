<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion pr-0" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">WebProg.ir</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span> داشبورد </span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        فروشگاه
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    {{-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span> کامپونت ها </span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header"> کامپونت سفارشی : </h6>
                <a class="collapse-item" href="#">Buttons</a>
                <a class="collapse-item" href="#">Cards</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span> ابزار ها </span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header"> لورم ایپسوم </h6>
                <a class="collapse-item" href="#">Colors</a>
                <a class="collapse-item" href="#">Borders</a>
                <a class="collapse-item" href="#">Animations</a>
                <a class="collapse-item" href="#">Other</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        لورم
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span> صفحات </span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header"> صفحات ورود : </h6>
                <a class="collapse-item" href="login.html"> ورود </a>
                <a class="collapse-item" href="register.html"> عضویت </a>
                <a class="collapse-item" href="forgot-password.html"> فراموشی رمز عبور </a>
                <div class="collapse-divider"></div>
                <h6 class="collapse-header"> صفحات دیگر : </h6>
                <a class="collapse-item" href="404.html">404 Page</a>
                <a class="collapse-item" href="#">Blank Page</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Charts -->
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-chart-area"></i>
            <span> نمودار ها </span></a>
    </li> --}}

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUser"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-users"></i>
            <span> کاربران </span>
        </a>
        <div id="collapseUser"
            class="collapse
        {{ request()->is('admin-panel/management/users*') ? 'show' : '' }}
        {{ request()->is('admin-panel/management/permissions*') ? 'show' : '' }}
        {{ request()->is('admin-panel/management/roles*') ? 'show' : '' }}
         "
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header"> کاربران : </h6>
                <a class="collapse-item {{ request()->is('admin-panel/management/users*') ? 'active' : '' }}"
                    href="{{ route('admin.users.index') }}">لیست کاربران</a>
                <a class="collapse-item {{ request()->is('admin-panel/management/permissions**') ? 'active' : '' }}"
                    href="{{ route('admin.permissions.index') }}">مجوز ها</a>
                <a class="collapse-item {{ request()->is('admin-panel/management/roles*') ? 'active' : '' }}"
                    href="{{ route('admin.roles.index') }}">نقش ها</a>
            </div>
        </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.brands.index') }}">
            <i class="fas fa-store"></i>
            <span> برند ها </span>
        </a>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    {{-- @can('create-product')
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProducts"
                aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-fw fa-cart-plus"></i>
                <span> محصولات </span>
            </a>
            <div id="collapseProducts" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('admin.products.index') }}">محصولات</a>
                    <a class="collapse-item" href="{{ route('admin.categories.index') }}">دسته بندی ها</a>
                    <a class="collapse-item" href="{{ route('admin.tags.index') }}">تگ ها</a>
                    <a class="collapse-item" href="{{ route('admin.attributes.index') }}">ویژگی ها</a>
                    <a class="collapse-item" href="{{ route('admin.comments.index') }}">نظرات</a>
                </div>
            </div>
        </li>
    @endcan --}}
    @role('admin')
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProducts"
                aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-fw fa-cart-plus"></i>
                <span> محصولات </span>
            </a>
            <div id="collapseProducts" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('admin.products.index') }}">محصولات</a>
                    <a class="collapse-item" href="{{ route('admin.categories.index') }}">دسته بندی ها</a>
                    <a class="collapse-item" href="{{ route('admin.tags.index') }}">تگ ها</a>
                    <a class="collapse-item" href="{{ route('admin.attributes.index') }}">ویژگی ها</a>
                    <a class="collapse-item" href="{{ route('admin.comments.index') }}">نظرات</a>
                </div>
            </div>
        </li>
    @endrole


    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        سفارشات
    </div>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw  fa-shopping-cart"></i>
            <span> سفارشات </span>
        </a>
        <div id="collapseUtilities" class="collapse
        {{ request()->is('admin-panel/management/orders*') ? 'show' : '' }}
        {{ request()->is('admin-panel/management/transactions*') ? 'show' : '' }}
        {{ request()->is('admin-panel/management/coupons*') ? 'show' : '' }}

        " aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header"> سفارشات </h6>
                <a class="collapse-item {{ request()->is('admin-panel/management/orders*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">سفارشات</a>
                <a class="collapse-item {{ request()->is('admin-panel/management/transactions*') ? 'active' : '' }}" href="{{ route('admin.transactions.index') }}">تراکنش ها</a>
                <a class="collapse-item {{ request()->is('admin-panel/management/coupons*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}">کوپن ها</a>

            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        تنظیمات
    </div>

    <!-- Nav Item - Banners -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.banners.index') }}">
            <i class="fas fa-fw fa-image"></i>
            <span> بنر ها </span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
