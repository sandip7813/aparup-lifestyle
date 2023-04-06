@extends('front.layouts.app')

@section('metaTitle', 'Home')

@section('content')
<main class="main-content bg-white">
    <div class="container mb-30 mt-50">
        <div class="sidebar-widget post-module-1">
            <div class="row mb-30">
                @forelse($posts as $postKey => $post)
                    @php
                        if($postKey < 2){
                            $blockSize = 'large';
                            $articleClass = 'col-md-6 mb-50';
                        }
                        elseif(($postKey >= 2) && ($postKey < 5)){
                            $blockSize = 'medium';
                            $articleClass = ($postKey == 4) ? 'col-lg-4 col-md-6 d-none d-lg-block mb-50' : 'col-lg-4 col-md-6 mb-50';
                        }
                        else{
                            $blockSize = 'small';
                            $articleClass = 'col-lg-3 col-md-6 mb-30';
                        }
                        
                        $bannerUrl = isset($post->banner->name) ? 'images/blogs/1000x600/' . $post->banner->name : 'images/no-image.png';
                    @endphp
                    <article class="{{ $articleClass }}">
                        <figure class="mb-20">
                            <a href="{{ route('post.details', $post->slug) }}"><img src="{{ asset($bannerUrl) }}" alt="{{ $post->banner->alt_tag ?? config('app.name') }}"></a>
                            @if( $blockSize != 'small' )
                            <div class="post-meta font-primary text-uppercase rotate-90 top-left">
                                <span>{{ \Carbon\Carbon::parse($post->updated_at)->format('d, F Y') }}</span>
                            </div>
                            @endif
                        </figure>
                        @if($post->categories->count() > 0)
                        <div class="post-meta mb-10 font-primary text-uppercase">
                            <span class="category">
                                @foreach($post->categories as $category)
                                <a href="{{ route('category.posts', $category->slug) }}">{{ $category->name }}</a>
                                @endforeach
                            </span>
                        </div>
                        @endif
                        <h3 class="post-title">
                            <a href="{{ route('post.details', $post->slug) }}">{{ $post->title }}</a>
                        </h3>
                    </article>
                @empty
                @endforelse
            </div>
        </div>
    </div>

    @include('front.includes.instagram-block')
</main>
@endsection