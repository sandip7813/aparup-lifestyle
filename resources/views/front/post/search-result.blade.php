@extends('front.layouts.app')

@section('metaTitle', $keyword)

@section('content')
<main class="main-content bg-lighter">
    <!--archive header-->
    <div class="archive-header pt-50 pb-50 bg-1 mb-50">
        <div class="container">
            <div class="widget-header-1 font-primary mb-30">
                <span class="widget-subtitle position-relative text-primary"><span class="divider-separator"></span>Search Result</span>
                <h2 class="widget-title mt-5 mb-30">{{ $keyword }}</h2>
            </div>
        </div>
    </div>
    <div class="container mb-50">
        <div class="sidebar-widget post-module-1">
            <div class="row mb-50">
                @forelse($posts as $mainKey => $mainPost)
                    @php
                        $bannerUrl = isset($mainPost->banner->name) ? 'images/blogs/1000x600/' . $mainPost->banner->name : 'images/no-image.png';
                    @endphp
                    <article class="col-lg-4 col-md-6 mb-30 wow animated fadeIn">
                        <figure class="mb-20">
                            <a href="{{ route('post.details', $mainPost->slug) }}"><img src="{{ asset($bannerUrl) }}" alt="{{ $mainPost->banner->alt_tag ?? config('app.name') }}"></a>
                            <div class="post-meta font-primary text-uppercase rotate-90 top-left">
                                <span>{{ \Carbon\Carbon::parse($mainPost->updated_at)->format('d, F Y') }}</span>
                            </div>
                        </figure>
                        <h4 class="post-title">
                            <a href="{{ route('post.details', $mainPost->slug) }}">{{ $mainPost->title }}</a>
                        </h4>
                    </article>
                @empty
                <p>No post found!</p>
                @endforelse
            </div>
            <!--Pagination-->
            <div class="view-more text-right wow animated fadeIn">
                {{ $posts->withQueryString()->links() }}
            </div>
        </div>
    </div>
    @include('front.includes.instagram-block')
</main>
@endsection
