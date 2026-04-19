<?php 
$user_auth = Auth::user();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Dashboard</title>

    <!-- Fontfaces CSS-->
    <link href="{{asset('assets/css/font-face.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('assets/vendor/fontawesome-7.1.0/css/all.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('assets/vendor/mdi-font/css/material-design-iconic-font.min.css')}}" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="{{asset('assets/vendor/bootstrap-5.3.8.min.css')}}" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="{{asset('assets/css/aos.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('assets/vendor/css-hamburgers/hamburgers.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('assets/css/swiper-bundle-12.0.3.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('assets/vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.css')}}" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="{{asset('assets/css/theme.css')}}" rel="stylesheet" media="all">
    @yield('css')
</head>

<body>
    <div class="page-wrapper">
        <!-- HEADER MOBILE-->
        <header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="index.html">
                            <img src="{{asset('assets/images/icon/logo.png')}}" alt="CoolAdmin" />
                        </a>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="index.html">DASHBOARD</a>
                                </li>
            
                            </ul>
                        </li>
                        
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                 <i class="fas fa-users"></i>USERS</a>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="#">
                    <img src="{{asset('assets/images/icon/logo.png')}}" style="width: 130px; margin-left: 30px;" alt="Cool Admin" />
                </a>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li>
                            <a href="{{ route('dashboard.index') }}">
                                <i class="fas fa-tachometer-alt"></i>Dashboard
                            </a>
                        </li>
                        @if($user_auth->is_admin==1)
                            <li>
                                <a href="{{ route('users.index') }}">
                                    <i class="fas fa-users"></i>Users</a>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('categories.index') }}">
                                    <i class="fas fa-list"></i>Categories</a>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('products.index') }}">
                                    <i class="fas fa-box"></i>Products Admin</a>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.orders.index') }}">
                                    <i class="fas fa-shopping-cart"></i>Orders Admin</a>
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ route('customer.products.index') }}">
                                <i class="fas fa-box"></i>Products</a>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('customer.orders.index') }}">
                                <i class="fas fa-shopping-cart"></i>Orders</a>
                            </a>
                        </li>
                        <li>
                            <a class="btn btn-danger" href="{{ route('logout') }}" style="text-align: left; color:white; padding:10px; border-radius:10px;">
                                 <i class="fas fa-sign-out-alt"></i>Logout</a>
                            </a>
                        </li>


                    </ul>
                </nav>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            <form class="form-header" action="" method="POST">
                              
                            </form>
                            <div class="header-button">
                                
                                <div class="noti-wrap">
                                    <div class="noti__item js-item-menu">
                                        <i class="fas fa-briefcase"></i>
                                        <span class="quantity">
                                            {{ count($carts) }}
                                        </span>
                                        <div class="notifi-dropdown js-dropdown">
                                            <div class="notifi__title">
                                                <p>Item in Cart</p>
                                            </div>
                                            @if(count($carts) == 0)
                                            <div class="notifi__item">
                                                <div class="content">
                                                    <p>Your cart is empty</p>
                                                </div>
                                            </div>
                                            @endif
                                            @foreach($carts as $cart)
                                            <div class="notifi__item">
                                                <div class="bg-c1 img-cir img-40">
                                                    <i class="fas fa-briefcase"></i>
                                                </div>
                                                <div class="content">
                                                    <p>{{ $cart->product->name }} - QTY: {{ $cart->qty }}</p>
                                                    <span class="date" style="color:rgb(182, 68, 68); font-weight:bold;">P {{ $cart->qty * $cart->product->price }}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                            @if(count($carts) > 0)
                                            <div class="notifi__footer">
                                                <a href="{{ route('checkout.index') }}">Go to Checkout</a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="image">
                                            <img src="{{ asset('storage/uploads/users/' . $user_auth->photo) }}" alt="{{ $user_auth->name }}" />
                                        </div>
                                        <div class="content">
                                            <a class="js-acc-btn" href="#">{{ $user_auth->name }}</a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
                                                <div class="image">
                                                    <a href="#">
                                                        <img src="{{ asset('storage/uploads/users/' . $user_auth->photo) }}" alt="{{ $user_auth->name }}" />
                                                    </a>
                                                </div>
                                                <div class="content">
                                                    <h5 class="name">
                                                        <a href="#">{{ $user_auth->name }}</a>
                                                    </h5>
                                                    <span class="email">{{ $user_auth->email }}</span>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__body">
                                                <div class="account-dropdown__item">
                                                    <a href="#">
                                                        <i class="zmdi zmdi-account"></i>Account</a>
                                                </div>
                                                <div class="account-dropdown__item">
                                                    <a href="#">
                                                        <i class="zmdi zmdi-settings"></i>Setting</a>
                                                </div>
                                                <div class="account-dropdown__item">
                                                    <a href="#">
                                                        <i class="zmdi zmdi-money-box"></i>Billing</a>
                                                </div>
                                            </div>
                                            
                                            <div class="account-dropdown__footer">
                                                <a href="#">
                                                    <i class="zmdi zmdi-power"></i>Logout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </div>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        </div>

    </div>
    @yield('modals')
    <!-- Jquery JS-->
    <script src="{{asset('assets/js/vanilla-utils.js')}}"></script>
    <!-- Bootstrap JS-->
    <script src="{{asset('assets/vendor/bootstrap-5.3.8.bundle.min.js')}}"></script>
    <!-- Vendor JS       -->
    <script src="{{asset('assets/vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.min.js')}}"></script>
    <script src="{{asset('assets/vendor/chartjs/chart.umd.js-4.5.1.min.js')}}"></script>

    <!-- Main JS-->
    <script src="{{asset('assets/js/bootstrap5-init.js')}}"></script>
    <script src="{{asset('assets/js/main-vanilla.js')}}"></script>
    <script src="{{asset('assets/js/swiper-bundle-12.0.3.min.js')}}"></script>
    <script src="{{asset('assets/js/aos.js')}}"></script>
    <script src="{{asset('assets/js/modern-plugins.js')}}"></script>
    @yield('js')

</body>

</html>
<!-- end document-->
