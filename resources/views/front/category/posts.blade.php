@php
    $page_title = !is_null($category->page_title) ? $category->page_title : $category->name;
    $meta_description = !is_null($category->metadata) ? $category->metadata : $category->name;
@endphp
@extends('front.layouts.app')

@section('metaTitle', $page_title)
@section('metaDescription', $meta_description)

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
                @forelse($topPosts as $topKey => $topPost)
                    @php
                        $blockSize = ($topKey < 2) ? 'large' : 'medium';
                        $imageUrl = isset($topPost->banner->name) ? 'images/blogs/1000x600/' . $topPost->banner->name : 'images/no-image.png';
                    @endphp
                    <article class="@if($blockSize == 'large') col-lg-6 @else col-lg-3 @endif col-md-6 mb-30 wow animated fadeIn">
                        <figure class="mb-20">
                            <a href="single.html"><img src="{{ asset($imageUrl) }}" alt="{{ $topPost->banner->alt_tag ?? config('app.name') }}"></a>
                            @if($blockSize == 'large')
                            <div class="post-meta font-primary text-uppercase rotate-90 top-left">
                                <span>{{ $topPost->created_at }}</span>
                            </div>
                            @endif
                        </figure>
                        <h4 class="post-title">
                            <a href="single.html">{{ $topPost->title }}</a>
                        </h4>
                    </article>
                @empty
                @endforelse
                <div class="col-lg-12 mt-50 mb-80 text-center wow animated fadeIn">
                    <img class="d-inline" src="http://via.placeholder.com/914x245" alt="">
                </div>
                @forelse($mainPosts as $mainKey => $mainPost)
                    @php
                        $imageUrl = isset($mainPost->banner->name) ? 'images/blogs/1000x600/' . $mainPost->banner->name : 'images/no-image.png';
                    @endphp
                    <article class="col-lg-4 col-md-6 mb-30 wow animated fadeIn">
                        <figure class="mb-20">
                            <a href="single.html"><img src="{{ asset($imageUrl) }}" alt="{{ $mainPost->banner->alt_tag ?? config('app.name') }}"></a>
                            <div class="post-meta font-primary text-uppercase rotate-90 top-left">
                                <span>{{ $mainPost->created_at }}</span>
                            </div>
                        </figure>
                        <h4 class="post-title">
                            <a href="single.html">{{ $mainPost->title }}</a>
                        </h4>
                    </article>
                @empty
                @endforelse
            </div>
            <!--Pagination-->
            <div class="view-more text-right wow animated fadeIn">
                {{ $mainPosts->withQueryString()->links() }}
            </div>
        </div>
    </div>
    @include('front.includes.instagram-block')
</main>
@endsection