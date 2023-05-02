@php
$comment_uuid = $comment_uuid ?? '';
$header_label = 'Post a Comment';
$submit_btn = 'Post Comment';

if($comment_uuid != ''){
    $header_label = 'Reply to this Comment';
    $submit_btn = 'Post Reply';
}

$button_id = ( $comment_uuid != '' ) ? 'btn-' . $comment_uuid : 'btn-comment';

$properties = [
    'class' => 'btn button button-contactForm comment_btn',
    'data-callback' => 'onCaptchaValidated',
    'data-button-id' => $button_id
];
@endphp
<!--comment form-->
<div class="comment-form wow fadeIn animated">
    <div class="widget-header-2 position-relative mb-30">
        <h5 class="mt-5 mb-30">{{ $header_label }}</h5>
    </div>
    <form class="form-contact comment_form commentForm" action="javascript:void(0);">
        <input type="hidden" name="comment_uuid" value="{{ $comment_uuid }}">
        <div class="row">
            <div class="col-12 mb-5">
                <div class="form-group">
                    <textarea class="form-control w-100" name="comment" cols="30" rows="9" placeholder="Write Comment"></textarea>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <input class="form-control" name="name" type="text" placeholder="Name">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <input class="form-control" name="email" type="email" placeholder="Email">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <input class="form-control" name="website" type="text" placeholder="Website">
                </div>
            </div>

        </div>
        <div class="form-group">
            {{-- 
            <button type="submit" class="btn button button-contactForm comment_btn">{{ $submit_btn }}</button>
            {!! htmlFormButton($submit_btn, $properties) !!} 
            --}}

            {{-- <button class="btn button button-contactForm comment_btn g-recaptcha" data-sitekey="6LeY0pUlAAAAAP62VtcJY8Rui3sYo9E_7kc48tke" data-callback="onCaptchaValidated" onClick="commentFormSubmit(this);">Post Comment</button> --}}

            <button class="btn button button-contactForm comment_btn g-recaptcha" data-sitekey="6LeY0pUlAAAAAP62VtcJY8Rui3sYo9E_7kc48tke" data-callback="onCaptchaValidated" onClick="commentFormSubmit(this);">{{ $submit_btn }}</button>
        </div>
    </form>
</div>