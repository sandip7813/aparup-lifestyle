@php
$commentsWithDescendants = commentsWithDescendants($post_uuid);
@endphp
@forelse($commentsWithDescendants as $comment)
    @php $commentDescendants = $comment->descendants; @endphp
    <div class="comment-list wow fadeIn animated">
        <div class="single-comment justify-content-between d-flex">
            <div class="user justify-content-between d-flex" style="width:100%;">
                <div class="desc" style="width:100%;">
                    <p class="comment">{!! $comment->content !!}</p>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <h5>{{ $comment->name }}</h5>
                            <p class="date">{{ \Carbon\Carbon::parse($comment->created_at)->format('d F Y') }}</p>
                        </div>
                        <div class="reply-btn">
                            <a href="javascript:void(0);" class="btn-reply" data-uuid="{{ $comment->uuid }}">Reply</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @forelse($commentDescendants as $replies)
        <div class="single-comment depth-2 justify-content-between d-flex mt-50">
            <div class="user justify-content-between d-flex">
                <div class="desc">
                    <p class="comment">{!! $replies->content !!}</p>
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <h5>{{ $replies->name }}</h5>
                            <p class="date">{{ \Carbon\Carbon::parse($replies->created_at)->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        @endforelse
        <div class="reply-form-wrap" id="reply-form-{{ $comment->uuid }}"></div>
    </div>
@empty
    <p>Be the first to post a comment!</p>
@endforelse