 <!-- ========== Left Sidebar Start ========== -->
 <div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!-- User details -->
        <div class="user-profile text-center mt-3">
            <div class="">
                @if( !is_null(Auth::user()->image) )
                    <img src="{{asset(Auth::user()->image)}}" alt="" class="avatar-md rounded-circle">
                @else
                    <img src="{{asset('backend/images/user.jpg')}}" alt="" class="avatar-md rounded-circle">
                @endif
            </div>
            <div class="mt-3">
                <h4 class="font-size-16 mb-1">
                    {{Auth::user()->name}}
                </h4>
                <span class="text-muted"><i class="ri-record-circle-line align-middle font-size-14 text-success"></i> Online</span>
            </div>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li>
                    <a href="{{url('/admin/dashboard')}}" class="waves-effect">
                        <i class="ri-home-4-line"></i><span class="badge rounded-pill bg-success float-end">3</span>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="menu-title">Product Management</li>
                <li>
                    <a href="{{route('add.product')}}" class="waves-effect">
                        <i class="ri-heart-add-fill"></i>
                        <span>Add Product</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('manage.product')}}" class="waves-effect">
                        <i class="ri-shirt-fill"></i>
                        <span>Manage Product</span>
                    </a>
                </li>

                <li class="menu-title">Order Management</li>
                <li>
                    <a href="{{route('manage.order')}}" class="waves-effect">
                        <i class="ri-gift-line"></i>
                        <span>All Order List</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('pending')}}" class="waves-effect">
                        <i class="ri-history-line"></i>
                        <span>All Pending Orders</span>
                    </a>
                </li>

                <li class="menu-title">Settings</li>
                <li>
                    <a href="{{route('manage.shipping')}}" class="waves-effect">
                        <i class=" ri-truck-line"></i>
                        <span>Shipping Method</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('manage.settings')}}" class="waves-effect">
                        <i class="ri-settings-2-line"></i>
                        <span>Genarel Settings</span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->