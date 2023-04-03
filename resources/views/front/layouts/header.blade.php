<!-- Start Header -->
<header class="main-header header-sticky header-style-2 bg-white">
    <!--Main navigation-->
    <div class="container">
        <div class="mobile_menu d-lg-none d-block"></div>
        <div class="mobile_logo d-md-none">
            <h1 class="font-secondary"><a href="{{ config('app.url') }}">{{ config('app.name') }}</a></h1>
        </div>
        <div class="mobile-search d-md-none">
            <button class="search-icon d-inline">
                <i class="athena-search mr-5"></i>
            </button>
        </div>
        <!--Logo and tagline -->
        <div class="header-logo text-center pt-30 pb-30 d-none d-md-block">
            <h1 class="font-secondary"><a href="{{ config('app.url') }}">{{ config('app.name') }}</a></h1>
            <p class="tagline font-primary">{{ config('app.site_caption') }}</p>
        </div>
        <div class="row align-items-center d-none d-md-flex">
            <div class="col-lg-3 col-md-4">
                <div class="off-canvas-toggle-cover d-none d-lg-inline-block mr-20">
                    <div class="off-canvas-toggle hidden d-inline-block" id="off-canvas-toggle">
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-4 text-center">
                <ul class="main-menu d-none d-lg-inline font-primary">
                    @include('front.layouts.navigation')
                </ul>
                <!--Mobile menu-->
                <ul id="mobile-menu" class="d-none">
                    @include('front.layouts.navigation')
                </ul>
            </div>
            <div class="col-lg-3 col-md-4 text-right ">
                <ul class="header-social-network d-none d-md-inline-block list-inline">
                    <li class="list-inline-item"><a class="social-icon fb text-xs-center" target="_blank" href="#"><i class="athena-facebook"></i></a></li>
                    <li class="list-inline-item"><a class="social-icon tw text-xs-center" target="_blank" href="#"><i class="athena-twitter"></i></a></li>
                    <li class="list-inline-item"><a class="social-icon pt text-xs-center" target="_blank" href="#"><i class="athena-instagram"></i></a></li>
                </ul>
                <button class="search-icon d-inline">
                    <i class="athena-search mr-5"></i>
                </button>
            </div>
            <div class="col-12">
                <div class="header-divider"></div>
            </div>
        </div>
    </div>
</header>