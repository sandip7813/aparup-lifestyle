<!-- Footer Start-->
<footer class="fixed-footer bg-dark text-white pt-100 pb-100 overflow-hidden">
    <div class="container-larger">
        <div class="row align-items-center">
            <div class="col-lg-4 col-md-12 text-center  d-none d-md-flex">
                <img class="d-inline-block float-lg-left mb-30 mr-30" src="http://via.placeholder.com/105x203" alt="ads2">
                <div class="d-inline-block">
                    <div class="header-logo mb-20">
                        <h1 class="font-secondary"><a class="text-white" href="{{ config('app.url') }}">{{ config('app.name') }}</a></h1>
                        <p class="tagline font-primary">{{ config('app.site_caption') }}</p>
                    </div>
                    <p class="site-des">123 Main Street <br>New York, NY 10001</p>
                    <div class="view-more text-center mt-30">
                        <button class="btn btn-outline-white border-radius-0 font-weight-thin">Subcrible</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <div class="col">
                        <h5 class="widget-title-muted mb-15">About</h5>
                        <ul class="mb-30 mr-30 text-white">
                            <li class="cat-item cat-item-2"><a class="text-white" href="category.html">Terms & Conditions</a></li>
                            <li class="cat-item cat-item-3"><a class="text-white" href="category.html">Help & Support Policy</a></li>
                            <li class="cat-item cat-item-4"><a class="text-white" href="category.html">Licensing Policy</a></li>
                            <li class="cat-item cat-item-5"><a class="text-white" href="category.html">Refund Policy</a></li>
                        </ul>
                    </div>
                    <div class="col">
                        <h5 class="widget-title-muted mb-15">News</h5>
                        <ul class="mb-30 mr-30 text-white">
                            <li class="cat-item cat-item-2"><a class="text-white" href="category.html">Mindfulness</a></li>
                            <li class="cat-item cat-item-3"><a class="text-white" href="category.html">Environment</a></li>
                            <li class="cat-item cat-item-4"><a class="text-white" href="category.html">Religion</a></li>
                            <li class="cat-item cat-item-5"><a class="text-white" href="category.html">Recipes</a></li>
                        </ul>
                    </div>
                    <div class="col">
                        <h5 class="widget-title-muted mb-15">Travel</h5>
                        <ul class="mb-30 mr-30 text-white">
                            <li class="cat-item cat-item-2"><a class="text-white" href="category.html">Destinations</a></li>
                            <li class="cat-item cat-item-3"><a class="text-white" href="category.html">Travel Tips</a></li>
                            <li class="cat-item cat-item-4"><a class="text-white" href="category.html">Hotels review</a></li>
                            <li class="cat-item cat-item-5"><a class="text-white" href="category.html">Air ticket</a></li>
                        </ul>
                    </div>
                    <div class="col">
                        <h5 class="widget-title-muted mb-15">Healthy</h5>
                        <ul class="mb-30 mr-30 text-white">
                            <li class="cat-item cat-item-2"><a class="text-white" href="category.html">Integrative Health</a></li>
                            <li class="cat-item cat-item-3"><a class="text-white" href="category.html">Mental Health</a></li>
                            <li class="cat-item cat-item-4"><a class="text-white" href="category.html">Health Food</a></li>
                            <li class="cat-item cat-item-5"><a class="text-white" href="category.html">Sleep Disorders</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-copy-right text-center mt-30">
            <p class="font-medium mb-0">
                <span class="text-muted">&copy; {{ date('Y') }},</span> 
                <a href="{{ config('app.url') }}" class="text-primary">{{ config('app.name') }}</a>
                <span class="text-muted"> | All rights reserved</span> 
            </p>
        </div>
    </div>
</footer>
<!-- End Footer -->
<div class="dark-mark"></div>
<!-- Vendor JS-->
<script src="{{ asset('front/assets/js/vendor/modernizr-3.5.0.min.js') }}"></script>
<script src="{{ asset('front/assets/js/vendor/jquery-1.12.4.min.js') }}"></script>
<script src="{{ asset('front/assets/js/vendor/popper.min.js') }}"></script>
<script src="{{ asset('front/assets/js/vendor/jquery.slicknav.js') }}"></script>
<script src="{{ asset('front/assets/js/vendor/slick.min.js') }}"></script>
<script src="{{ asset('front/assets/js/vendor/wow.min.js') }}"></script>
<script src="{{ asset('front/assets/js/vendor/jquery.scrollUp.min.js') }}"></script>
<script src="{{ asset('front/assets/js/vendor/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('front/assets/js/vendor/jquery.magnific-popup.js') }}"></script>
<script src="{{ asset('front/assets/js/vendor/jquery.sticky.js') }}"></script>
<script src="{{ asset('front/assets/js/vendor/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('front/assets/js/vendor/waypoints.min.js') }}"></script>
<script src="{{ asset('front/assets/js/vendor/jquery.theia.sticky.js') }}"></script>
<script src="{{ asset('front/assets/js/vendor/masonry.pkgd.min.js') }}"></script>
<script src="{{ asset('front/assets/js/vendor/imagesloaded.pkgd.min.js') }}"></script>
<!-- Theme JS -->
<script src="{{ asset('front/assets/js/main.js') }}"></script>

@yield('scripts')