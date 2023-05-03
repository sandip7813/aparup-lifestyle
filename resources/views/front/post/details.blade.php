@php
    $page_title = !is_null($post->page_title) ? $post->page_title : $post->name;
    $meta_description = !is_null($post->metadata) ? $post->metadata : $post->name;
    $bannerUrl = isset($post->banner->name) ? 'images/blogs/1000x600/' . $post->banner->name : 'images/no-image.png';
@endphp
@extends('front.layouts.app')

@section('metaTitle', $page_title)
@section('metaDescription', $meta_description)

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
@section('styles')
<style>
    div#social-links {
        margin: 0 auto;
        max-width: 500px;
    }
    div#social-links ul li {
        display: inline-block;
    }          
    div#social-links ul li a {
        padding: 10px;
        /* border: 1px solid #ccc; */
        margin: 10px;
        font-size: 30px;
        /* color: #222; */
        background-color: #fafafa;
    }
</style>
@endsection

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
                {{-- <div class="col-md-6 text-right d-none d-md-inline">
                    <ul class="header-social-network d-inline-block list-inline mr-15">
                        <li class="list-inline-item text-muted"><span>Share this: </span></li>
                        <li class="list-inline-item"><a class="social-icon fb text-xs-center" target="_blank" href="#"><i class="athena-facebook"></i></a></li>
                        <li class="list-inline-item"><a class="social-icon tw text-xs-center" target="_blank" href="#"><i class="athena-twitter"></i></a></li>
                        <li class="list-inline-item"><a class="social-icon pt text-xs-center" target="_blank" href="#"><i class="athena-instagram"></i></a></li>
                    </ul>
                    {!! $shareComponent !!}
                </div> --}}
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
                            <li class="blocks-gallery-item">
                                <a href="{{ $subImage['affiliate_url'] ?? '' }}"><img src="{{ asset('images/blogs/main/' . $subImage['name']) }}" alt=""></a>
                                <figcaption> <i class="ti-credit-card mr-5"></i>{{ $subImage['copyright'] ?? '' }}</figcaption>
                            </li>
                            @endforeach
                        </ul>
                    </figure>
                    @endif
                @empty
                @endforelse
                <hr class="section-divider">
            </div>
            <div class="single-social-share text-center border bg-lighter p-50 mt-50 mb-50 clearfix wow fadeIn animated">
                <p>If you enjoyed reading this story, then we'd love it if you would <strong>share it!</strong></p>
                {!! $shareComponent !!}
            </div>

            @if( $relatedPosts->count() > 0 )
            <!--More posts-->
            <div class="single-more-articles border">
                <div class="widget-header-4 position-relative mb-30">
                    <h6 class="mt-5 mb-30 text-uppercase">Related posts</h6>
                    <button class="single-more-articles-close"><i class="athena-cancel"></i></button>
                </div>
                @foreach($relatedPosts as $relPost)
                    @php
                        $bannerUrl = isset($relPost->banner->name) ? 'images/blogs/1000x600/' . $relPost->banner->name : 'images/no-image.png';
                    @endphp
                    <article class="row align-items-center mb-30">
                        <figure class="col-sm-4 mb-0">
                            <a href="{{ route('post.details', $relPost->slug) }}"><img src="{{ asset($bannerUrl) }}" alt=""></a>
                        </figure>
                        <div class="col-sm-8 pl-0">
                            <h6 class="post-title">
                                <a href="{{ route('post.details', $relPost->slug) }}">{{ $relPost->title }}</a>
                            </h6>
                            <div class="post-meta mb-10 font-primary text-uppercase">
                                <span class="date">{{ \Carbon\Carbon::parse($relPost->updated_at)->format('d, F Y') }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            @endif

            <!--Comments-->
            <hr class="section-divider">
            <div class="comments-area">
                <div class="widget-header-2 position-relative mb-30">
                    <h2 class="mt-5 mb-30">Comments</h2>
                </div>
                <div id="comments-list"></div>
            </div>

            @include('front.post.comment-form')
        </article>
    </div>
    <!--container-->
    @include('front.includes.instagram-block')
</main>
@endsection

@section('scripts')
<script>
    regenerate_comments_list();
    
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.btn-reply', function (e) {
            this_obj = $(this);
            comment_uuid = this_obj.data('uuid');

            $.ajax({
                dataType: 'json',
                type: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    comment_uuid : comment_uuid 
                },
                url: "{{ route('post.comment.reply-form') }}",
                success:function(data) {
                    if( data.status == 'failed' ){
                        swal_fire_error(data.error.message);
                        return false;
                    }
                    else if( data.status == 'success' ){
                        reply_div = $('#reply-form-' + comment_uuid);
                        reply_div.html(data.html);

                        $([document.documentElement, document.body]).animate({
                            scrollTop: reply_div.offset().top
                        }, 1000);
                    }
                }
            });
        });

        /* $(document).on('submit', '.commentForm', function (e) {
            this_obj = $(this);
            submit_btn = this_obj.find('.comment_btn');
            comment_uuid = this_obj.find('input[name="comment_uuid"]').val();
            
            e.preventDefault();
            var formData = new FormData(this);

            submit_btn.html('<i class="fa fa-spinner" aria-hidden="true"></i> Posting...').attr('disabled', true);
            btn_text = ( comment_uuid != '' ) ? 'Post Reply' : 'Post Comment';

            $.ajax({
                dataType: 'json',
                type: 'POST',
                data: formData ,
                url: "{{ route('post.comment.submit', $post->uuid) }}",
                cache: false,
                contentType: false,
                processData: false,
                success:function(data) {
                    submit_btn.html(btn_text).attr('disabled', false);

                    if( data.status == 'failed' ){
                        swal_fire_error(data.error.message);
                        return false;
                    }
                    else if( data.status == 'success' ){
                        swal_fire_success(data.message);
                        regenerate_comments_list();
                        this_obj[0].reset();
                    }
                }
            });
        }); */

        
    });

    function regenerate_comments_list(){
        $.ajax({
            dataType: 'json',
            url: "{{ route('post.comments.regenerate-list', $post->uuid) }}",
            success:function(data) {
                if( data.status == 'failed' ){
                    swal_fire_error(data.error.message);
                    return false;
                }
                else if( data.status == 'success' ){
                    $('#comments-list').html(data.html);
                }
            }
        });
    }

    var isCaptchaValidated = 0;

    function onCaptchaValidated(){
        // isCaptchaValidated = true;
        var response = grecaptcha.getResponse();

        isCaptchaValidated = response.length;
    }

    function commentFormSubmit(thisObj){
        var response = grecaptcha.getResponse();

        if(response.length === 0){
            swal_fire_error('Captcha validation failed!');
            return false;
        }

        //onCaptchaValidated = onCaptchaValidated();

        console.log( isCaptchaValidated );
        
        thisForm = $(thisObj).parents('form');

        comment_uuid = thisForm.find('input[name="comment_uuid"]').val();
        comment = thisForm.find('input[name="comment"]').val();
        name = thisForm.find('input[name="name"]').val();
        email = thisForm.find('input[name="email"]').val();
        website = thisForm.find('input[name="website"]').val();

        $(thisObj).html('<i class="fa fa-spinner" aria-hidden="true"></i> Posting...').attr('disabled', true);
        btn_text = ( comment_uuid != '' ) ? 'Post Reply' : 'Post Comment';

        $.ajax({
            dataType: 'json',
            type: 'POST',
            data: {
                comment_uuid: comment_uuid,
                comment: comment,
                name: name,
                email: email,
                website: website,
            } ,
            url: "{{ route('post.comment.submit', $post->uuid) }}",
            cache: false,
            contentType: false,
            processData: false,
            success:function(data) {
                $(thisObj).html(btn_text).attr('disabled', false);

                if( data.status == 'failed' ){
                    swal_fire_error(data.error.message);
                    return false;
                }
                else if( data.status == 'success' ){
                    swal_fire_success(data.message);
                    regenerate_comments_list();
                    thisForm[0].reset();
                }
            }
        });
    }

    
</script>
@endsection