@php
$allCategories = categoriesWithDescendants();
@endphp
<!-- Footer Start-->
<footer class="fixed-footer bg-dark text-white pt-100 pb-100 overflow-hidden">
    <div class="container-larger">
        <div class="row align-items-center">
            <div class="col-lg-4 col-md-12 text-center  d-none d-md-flex">
                {{-- <img class="d-inline-block float-lg-left mb-30 mr-30" src="http://via.placeholder.com/105x203" alt="ads2"> --}}
                <div class="d-inline-block">
                    <div class="header-logo mb-20">
                        <h1 class="font-secondary"><a class="text-white" href="{{ config('app.url') }}">{{ config('app.name') }}</a></h1>
                        <p class="tagline font-primary">{{ config('app.site_caption') }}</p>
                    </div>
                    <p class="site-des">123 Main Street <br>New York, NY 10001</p>
                    {{-- <div class="view-more text-center mt-30">
                        <button class="btn btn-outline-white border-radius-0 font-weight-thin">Subcrible</button>
                    </div> --}}
                </div>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    @forelse($allCategories as $mainMenu)
                        @php $descendants = $mainMenu->descendants; @endphp
                        <div class="col">
                            <h5 class="widget-title-muted mb-15"><a href="{{ route('category.posts', $mainMenu->slug) }}">{{ $mainMenu->name }}</a></h5>
                            @if( count($descendants) > 0 )
                                <ul class="mb-30 mr-30 text-white">
                                @foreach($descendants as $subMenu)
                                    <li class="cat-item cat-item-2"><a class="text-white" href="{{ route('category.posts', $subMenu->slug) }}">{{ $subMenu->name }}</a></li>
                                @endforeach
                                </ul>
                            @endif
                        </div>
                    @empty
                    @endforelse
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

<script src="{{ asset('js/sweetalert2@11.js') }}"></script>
<script src="{{ asset('admin/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script>
    $(document).ready(function(){
        $('#search-btn').on('click', function(e){
            search_keyword = $('input[name="keyword"]').val().trim();
            e.preventDefault();

            if( search_keyword == '' ){
                swal_fire_error('Please enter a keyword (post title) to search!');
                return false;
            }

            $('#search-form').submit();
        });
    });
</script>
@yield('scripts')