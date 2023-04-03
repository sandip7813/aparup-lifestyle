@extends('front.layouts.app')

@section('metaTitle', 'Posts')

@section('content')
<main class="main-content bg-lighter">
    <!--archive header-->
    <div class="archive-header pt-50 pb-50 bg-1 mb-50">
        <div class="container">
            <div class="widget-header-1 font-primary mb-30">
                <span class="widget-subtitle position-relative text-primary"><span class="divider-separator"></span>Category</span>
                <h2 class="widget-title mt-5 mb-30">{{ $category->name }}<sup>{{ $category->blogs_count }} posts</sup></h2>
            </div>
            <div class="breadcrumb">
                <a href="{{ route('home.index') }}" rel="nofollow">Home</a>
                <span></span> {{ $category->name }}
            </div>
        </div>
    </div>
    <div class="container mb-50">
        <div class="sidebar-widget post-module-1">
            <div class="row mb-50">
                <article class="col-lg-6 col-md-6 mb-30 wow animated fadeIn">
                    <figure class="mb-20">
                        <a href="single.html"><img src="http://via.placeholder.com/1000x753" alt=""></a>
                        <div class="post-meta font-primary text-uppercase rotate-90 top-left">
                            <span>23, October 2020</span>
                        </div>
                    </figure>
                    <div class="post-meta mb-15 font-primary text-uppercase">
                        <span class="category"><a href="category.html">Inspiration</a></span>
                    </div>
                    <h4 class="post-title">
                        <a href="single.html">This Is How Often You Should Really Clean Your Sheets, According to a Pro </a>
                    </h4>
                </article>
                <article class="col-lg-6 col-md-6 mb-30 wow animated fadeIn">
                    <figure class="mb-20">
                        <a href="single.html"><img src="http://via.placeholder.com/1000x753" alt=""></a>
                        <div class="post-meta font-primary text-uppercase rotate-90 top-left">
                            <span>23, October 2020</span>
                        </div>
                    </figure>
                    <div class="post-meta mb-15 font-primary text-uppercase">
                        <span class="category"><a href="category.html">Healthy</a></span>
                    </div>
                    <h4 class="post-title">
                        <a href="single.html">What Is Laundry Stripping? Some Swear by This Gross-Yet-Satisfying Cleaning Trend </a>
                    </h4>
                </article>
                <article class="col-lg-3 col-md-6 mb-30 wow animated fadeIn">
                    <figure class="mb-20">
                        <a href="single.html"><img src="http://via.placeholder.com/1000x753" alt=""></a>
                    </figure>
                    <div class="post-meta mb-10 font-primary text-uppercase">
                        <span><a href="category.html">Decor</a></span>
                    </div>
                    <h5 class="post-title">
                        <a href="single.html">Inside an Antiques-Filled Los Angeles Home</a>
                    </h5>
                </article>
                <article class="col-lg-3 col-md-6 mb-30 wow animated fadeIn">
                    <figure class="mb-20">
                        <a href="single.html"><img src="http://via.placeholder.com/1000x753" alt=""></a>
                    </figure>
                    <div class="post-meta mb-10 font-primary text-uppercase">
                        <span><a href="category.html">Ideas</a></span>
                    </div>
                    <h5 class="post-title">
                        <a href="single.html">A House Designed for Throwing Great Parties</a>
                    </h5>
                </article>
                <article class="col-lg-3 col-md-6 mb-30 wow animated fadeIn">
                    <figure class="mb-20">
                        <a href="single.html"><img src="http://via.placeholder.com/1000x753" alt=""></a>
                    </figure>
                    <div class="post-meta mb-10 font-primary text-uppercase">
                        <span><a href="category.html">Room</a></span>
                    </div>
                    <h5 class="post-title">
                        <a href="single.html">A Turn-of-the-Century West Village Apartment</a>
                    </h5>
                </article>
                <article class="col-lg-3 col-md-6 mb-30 wow animated fadeIn">
                    <figure class="mb-20">
                        <a href="single.html"><img src="http://via.placeholder.com/1000x753" alt=""></a>
                    </figure>
                    <div class="post-meta mb-10 font-primary text-uppercase">
                        <span><a href="category.html">Tips</a></span>
                    </div>
                    <h5 class="post-title">
                        <a href="single.html">A New Site Spotlights Black Women's Outdoor Spaces</a>
                    </h5>
                </article>
                <div class="col-lg-12 mt-50 mb-80 text-center wow animated fadeIn">
                    <img class="d-inline" src="http://via.placeholder.com/914x245" alt="">
                </div>
                <article class="col-lg-4 col-md-6 mb-30 wow animated fadeIn">
                    <figure class="mb-20">
                        <a href="single.html"><img src="http://via.placeholder.com/600x700" alt=""></a>
                        <div class="post-meta font-primary text-uppercase rotate-90 top-left">
                            <span>23, October 2020</span>
                        </div>
                    </figure>
                    <div class="post-meta mb-15 font-primary text-uppercase">
                        <span class="category"><a href="category.html">Food</a></span>
                    </div>
                    <h4 class="post-title">
                        <a href="single.html">How To Instill Healthy Habits From a Young Age</a>
                    </h4>
                </article>
                <article class="col-lg-4 col-md-6 mb-30 wow animated fadeIn">
                    <figure class="mb-20">
                        <a href="single.html"><img src="http://via.placeholder.com/600x700" alt=""></a>
                        <div class="post-meta font-primary text-uppercase rotate-90 top-left">
                            <span>25, October 2020</span>
                        </div>
                    </figure>
                    <div class="post-meta mb-15 font-primary text-uppercase">
                        <span class="category"><a href="category.html">Lifestyle</a><a href="category.html">Blog</a></span>
                    </div>
                    <h4 class="post-title">
                        <a href="single.html">First trip post lockdown © ? Narendra Bhawan</a>
                    </h4>
                </article>
                <article class="col-lg-4 col-md-6 mb-30 wow animated fadeIn">
                    <figure class="mb-20">
                        <a href="single.html"><img src="http://via.placeholder.com/600x700" alt=""></a>
                        <div class="post-meta font-primary text-uppercase rotate-90 top-left">
                            <span>22, October 2020</span>
                        </div>
                    </figure>
                    <div class="post-meta mb-15 font-primary text-uppercase">
                        <span class="category"><a href="category.html">Lifestyle</a><a href="category.html">Photo</a></span>
                    </div>
                    <h4 class="post-title">
                        <a href="single.html">Easiest Ice Cream You© ?ll Ever Make. No Ice-cream Maker</a>
                    </h4>
                </article>
                <article class="col-lg-4 col-md-6 mb-30 wow animated fadeIn">
                    <figure class="mb-20">
                        <a href="single.html"><img src="http://via.placeholder.com/600x700" alt=""></a>
                        <div class="post-meta font-primary text-uppercase rotate-90 top-left">
                            <span>23, October 2020</span>
                        </div>
                    </figure>
                    <div class="post-meta mb-15 font-primary text-uppercase">
                        <span class="category"><a href="category.html">Food</a></span>
                    </div>
                    <h4 class="post-title">
                        <a href="single.html">How Luxury Hotels Are Staying Clean, According to a Housekeeping Director</a>
                    </h4>
                </article>
                <article class="col-lg-4 col-md-6 mb-30 wow animated fadeIn">
                    <figure class="mb-20">
                        <a href="single.html"><img src="http://via.placeholder.com/600x700" alt=""></a>
                        <div class="post-meta font-primary text-uppercase rotate-90 top-left">
                            <span>25, October 2020</span>
                        </div>
                    </figure>
                    <div class="post-meta mb-15 font-primary text-uppercase">
                        <span class="category"><a href="category.html">Lifestyle</a><a href="category.html">Blog</a></span>
                    </div>
                    <h4 class="post-title">
                        <a href="single.html">This Quiet Humidifier Keeps Noise Levels Below a Whisper© ?and It© ?s on Sale</a>
                    </h4>
                </article>
                <article class="col-lg-4 col-md-6 mb-30 wow animated fadeIn">
                    <figure class="mb-20">
                        <a href="single.html"><img src="http://via.placeholder.com/600x700" alt=""></a>
                        <div class="post-meta font-primary text-uppercase rotate-90 top-left">
                            <span>22, October 2020</span>
                        </div>
                    </figure>
                    <div class="post-meta mb-15 font-primary text-uppercase">
                        <span class="category"><a href="category.html">Lifestyle</a><a href="category.html">Photo</a></span>
                    </div>
                    <h4 class="post-title">
                        <a href="single.html">This Is How Often You Should Really Clean Your Sheets, According to a Pro</a>
                    </h4>
                </article>
                <article class="col-lg-4 col-md-6 mb-30 wow animated fadeIn">
                    <figure class="mb-20">
                        <a href="single.html"><img src="http://via.placeholder.com/600x700" alt=""></a>
                        <div class="post-meta font-primary text-uppercase rotate-90 top-left">
                            <span>23, October 2020</span>
                        </div>
                    </figure>
                    <div class="post-meta mb-15 font-primary text-uppercase">
                        <span class="category"><a href="category.html">Food</a></span>
                    </div>
                    <h4 class="post-title">
                        <a href="single.html">11 Things In Your Bathroom to Throw Away Now</a>
                    </h4>
                </article>
                <article class="col-lg-4 col-md-6 mb-30 wow animated fadeIn">
                    <figure class="mb-20">
                        <a href="single.html"><img src="http://via.placeholder.com/600x700" alt=""></a>
                        <div class="post-meta font-primary text-uppercase rotate-90 top-left">
                            <span>25, October 2020</span>
                        </div>
                    </figure>
                    <div class="post-meta mb-15 font-primary text-uppercase">
                        <span class="category"><a href="category.html">Lifestyle</a><a href="category.html">Blog</a></span>
                    </div>
                    <h4 class="post-title">
                        <a href="single.html">Sanitize vs. Disinfect: What© ?s the Difference? </a>
                    </h4>
                </article>
                <article class="col-lg-4 col-md-6 mb-30 wow animated fadeIn">
                    <figure class="mb-20">
                        <a href="single.html"><img src="http://via.placeholder.com/600x700" alt=""></a>
                        <div class="post-meta font-primary text-uppercase rotate-90 top-left">
                            <span>22, October 2020</span>
                        </div>
                    </figure>
                    <div class="post-meta mb-15 font-primary text-uppercase">
                        <span class="category"><a href="category.html">Lifestyle</a><a href="category.html">Photo</a></span>
                    </div>
                    <h4 class="post-title">
                        <a href="single.html"> The 12 Best Mother© ?s Day Gifts to Send During Quarantine</a>
                    </h4>
                </article>
            </div>
            <!--Pagination-->
            <div class="view-more text-center wow animated fadeIn">
                <button class="btn btn-outline-primary border-radius-0 font-weight-thin">Load more posts</button>
            </div>
        </div>
    </div>
    <div class="sidebar-widget widget-instagram bg-white pt-50 wow animated fadeIn">
        <div class="widget-header-2 font-primary mb-50 text-center">
            <i class="athena-instagram mr-5"></i><span class="widget-subtitle position-relative text-primary">athena</span>
            <h3 class="widget-title mt-5 mb-0">Follow Me On Instagram </h3>
            <span class="font-small text-muted">Followed by: 256,215</span>
        </div>
        <ul class="alith-row alith-gap-0 overflow-hidden">
            <li class="alith-col alith-col-6">
                <a target="_blank" href="#">
                    <img title="" alt="instar" src="http://via.placeholder.com/500x500">
                </a>
            </li>
            <li class="alith-col alith-col-6">
                <a target="_blank" href="#">
                    <img title="" alt="instar" src="http://via.placeholder.com/500x500">
                </a>
            </li>
            <li class="alith-col alith-col-6">
                <a target="_blank" href="#">
                    <img title="" alt="instar" src="http://via.placeholder.com/500x500">
                </a>
            </li>
            <li class="alith-col alith-col-6">
                <a target="_blank" href="#">
                    <img title="" alt="instar" src="http://via.placeholder.com/500x500">
                </a>
            </li>
            <li class="alith-col alith-col-6">
                <a target="_blank" href="#">
                    <img title="" alt="instar" src="http://via.placeholder.com/500x500">
                </a>
            </li>
            <li class="alith-col alith-col-6">
                <a target="_blank" href="#">
                    <img title="" alt="instar" src="http://via.placeholder.com/500x500">
                </a>
            </li>
        </ul>
    </div>
</main>
@endsection