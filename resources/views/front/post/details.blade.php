@php
    $page_title = !is_null($post->page_title) ? $post->page_title : $post->name;
    $meta_description = !is_null($post->metadata) ? $post->metadata : $post->name;
    $bannerUrl = isset($post->banner->name) ? 'images/blogs/1000x600/' . $post->banner->name : 'images/no-image.png';
@endphp
@extends('front.layouts.app')

@section('metaTitle', $page_title)
@section('metaDescription', $meta_description)

@section('content')
<main class="main-content bg-white">
    <div class="container single-content">
        <div class="entry-header entry-header-style-1 mb-50 pt-50">
            <h1 class="entry-title mb-50 font-weight-900">{{ $post->title }}</h1>
            <div class="row">
                <div class="col-md-6">
                    <div class="entry-meta align-items-center meta-2">
                        @if($post->categories->count() > 0)
                        <p class="mb-5 post-meta">
                            <span class="category font-weight-bold text-uppercase">
                                @foreach($post->categories as $category)
                                <a class="font-weight-bold" href="{{ route('category.posts', $category->slug) }}">{{ $category->name }}</a>
                                @endforeach
                            </span>
                        </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 text-right d-none d-md-inline">
                    <ul class="header-social-network d-inline-block list-inline mr-15">
                        <li class="list-inline-item text-muted"><span>Share this: </span></li>
                        <li class="list-inline-item"><a class="social-icon fb text-xs-center" target="_blank" href="#"><i class="athena-facebook"></i></a></li>
                        <li class="list-inline-item"><a class="social-icon tw text-xs-center" target="_blank" href="#"><i class="athena-twitter"></i></a></li>
                        <li class="list-inline-item"><a class="social-icon pt text-xs-center" target="_blank" href="#"><i class="athena-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!--end single header-->
        <figure class="image mb-30 m-auto text-center">
            <img src="{{ asset($bannerUrl) }}" alt="{{ $post->banner->alt_tag ?? config('app.name') }}" />
            <div class="post-meta font-primary text-uppercase rotate-90 top-left">
                <span>Updated {{ \Carbon\Carbon::parse($post->updated_at)->format('d F Y') }}</span>
            </div>
        </figure>
        <!--figure-->
        <article class="entry-wraper mb-50">
            <div class="entry-main-content dropcap wow fadeIn animated">
                {!! $post->content !!}

                @forelse($post->contents as $subContent)
                    <hr class="section-divider">

                    <h2>{{ $subContent->content_title }}</h2>

                    {!! $subContent->content !!}

                    @if( count($subContent->images) > 0 )
                    <figure class="wp-block-gallery columns-3 wp-block-image">
                        <ul class="blocks-gallery-grid">
                            @foreach($subContent->images as $subImage)
                            <li class="blocks-gallery-item"><a href="#"><img src="{{ asset('images/blogs/main/' . $subImage['name']) }}" alt=""></a></li>
                            @endforeach
                        </ul>
                        <figcaption> <i class="ti-credit-card mr-5"></i>Image credit: Pexel.com </figcaption>
                    </figure>
                    @endif
                @empty
                @endforelse
                <hr class="section-divider">
            </div>
            <div class="single-social-share text-center border bg-lighter p-50 mt-50 mb-50 clearfix wow fadeIn animated">
                <p>If you enjoyed reading this story, then we'd love it if you would <strong>share it!</strong></p>
                <ul class="header-social-network d-inline-block list-inline mr-15">
                    <li class="list-inline-item"><a class="social-icon fb text-xs-center" target="_blank" href="#"><i class="athena-facebook"></i></a></li>
                    <li class="list-inline-item"><a class="social-icon tw text-xs-center" target="_blank" href="#"><i class="athena-twitter"></i></a></li>
                    <li class="list-inline-item"><a class="social-icon pt text-xs-center" target="_blank" href="#"><i class="athena-instagram"></i></a></li>
                </ul>
            </div>
            <!--More posts-->
            <div class="single-more-articles border">
                <div class="widget-header-4 position-relative mb-30">
                    <h6 class="mt-5 mb-30 text-uppercase">Related posts</h6>
                    <button class="single-more-articles-close"><i class="athena-cancel"></i></button>
                </div>
                <article class="row align-items-center mb-30">
                    <figure class="col-sm-4 mb-0">
                        <a href="single.html"><img src="http://via.placeholder.com/500x500" alt=""></a>
                    </figure>
                    <div class="col-sm-8 pl-0">
                        <h6 class="post-title">
                            <a href="single.html">The World Caters to Average</a>
                        </h6>
                        <div class="post-meta mb-10 font-primary text-uppercase">
                            <span class="date">By <a class="text-primary" href="author.html">Elizabeth</a></span>
                        </div>
                    </div>
                </article>
                <article class="row align-items-center mb-10">
                    <figure class="col-sm-4 mb-0">
                        <a href="single.html"><img src="http://via.placeholder.com/500x500" alt=""></a>
                    </figure>
                    <div class="col-sm-8 pl-0">
                        <h6 class="post-title">
                            <a href="single.html">The most haunted spot in every state</a>
                        </h6>
                        <div class="post-meta mb-10 font-primary text-uppercase">
                            <span class="date">By <a class="text-primary" href="author.html">Brona</a></span>
                        </div>
                    </div>
                </article>
            </div>
            <!--Comments-->
            <hr class="section-divider">
            <div class="comments-area">
                <div class="widget-header-2 position-relative mb-30">
                    <h2 class="mt-5 mb-30">Comments</h2>
                </div>
                <div class="comment-list wow fadeIn animated">
                    <div class="single-comment justify-content-between d-flex">
                        <div class="user justify-content-between d-flex">
                            <div class="thumb">
                                <img src="http://via.placeholder.com/300x300" alt="">
                            </div>
                            <div class="desc">
                                <p class="comment">
                                    Vestibulum euismod, leo eget varius gravida, eros enim interdum urna, non rutrum enim ante quis metus. Duis porta ornare nulla ut bibendum
                                </p>
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <h5>
                                            <a href="#">Rosie</a>
                                        </h5>
                                        <p class="date">6 minutes ago </p>
                                    </div>
                                    <div class="reply-btn">
                                        <a href="#" class="btn-reply">Reply</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="comment-list wow fadeIn animated">
                    <div class="single-comment justify-content-between d-flex">
                        <div class="user justify-content-between d-flex">
                            <div class="thumb">
                                <img src="http://via.placeholder.com/300x300" alt="">
                            </div>
                            <div class="desc">
                                <p class="comment">
                                    Sed ac lorem felis. Ut in odio lorem. Quisque magna dui, maximus ut commodo sed, vestibulum ac nibh. Aenean a tortor in sem tempus auctor
                                </p>
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <h5>
                                            <a href="#">Agatha Christie</a>
                                        </h5>
                                        <p class="date">December 4, 2020 at 3:12 pm </p>
                                    </div>
                                    <div class="reply-btn">
                                        <a href="#" class="btn-reply">Reply</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="single-comment depth-2 justify-content-between d-flex mt-50">
                        <div class="user justify-content-between d-flex">
                            <div class="thumb">
                                <img src="http://via.placeholder.com/300x300" alt="">
                            </div>
                            <div class="desc">
                                <p class="comment">
                                    Sed ac lorem felis. Ut in odio lorem. Quisque magna dui, maximus ut commodo sed, vestibulum ac nibh. Aenean a tortor in sem tempus auctor
                                </p>
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <h5>
                                            <a href="#">Steven</a>
                                        </h5>
                                        <p class="date">December 4, 2020 at 3:12 pm </p>
                                    </div>
                                    <div class="reply-btn">
                                        <a href="#" class="btn-reply">Reply</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="comment-list wow fadeIn animated">
                    <div class="single-comment justify-content-between d-flex">
                        <div class="user justify-content-between d-flex">
                            <div class="thumb">
                                <img src="http://via.placeholder.com/300x300" alt="">
                            </div>
                            <div class="desc">
                                <p class="comment">
                                    Donec in ullamcorper quam. Aenean vel nibh eu magna gravida fermentum. Praesent eget nisi pulvinar, sollicitudin eros vitae, tristique odio.
                                </p>
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <h5>
                                            <a href="#">Danielle Steel</a>
                                        </h5>
                                        <p class="date">December 4, 2020 at 3:12 pm </p>
                                    </div>
                                    <div class="reply-btn">
                                        <a href="#" class="btn-reply">Reply</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--comment form-->
            <div class="comment-form wow fadeIn animated">
                <div class="widget-header-2 position-relative mb-30">
                    <h5 class="mt-5 mb-30">Leave a Reply</h5>
                </div>
                <form class="form-contact comment_form" action="#" id="commentForm">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <textarea class="form-control w-100" name="comment" id="comment" cols="30" rows="9" placeholder="Write Comment"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input class="form-control" name="name" id="name" type="text" placeholder="Name">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <input class="form-control" name="email" id="email" type="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <input class="form-control" name="website" id="website" type="text" placeholder="Website">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn button button-contactForm">Post Comment</button>
                    </div>
                </form>
            </div>
        </article>
    </div>
    <!--container-->
    <!--Instagram-->
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