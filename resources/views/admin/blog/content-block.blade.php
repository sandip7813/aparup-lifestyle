@php
$content_uuid = $blog_content->uuid ?? null;
$image_block = $blog_content->images ?? [];
@endphp
<div class="card card-info blog_content_wrapper" id="{{ $content_uuid }}">
    <div class="card-header">
        <h3 class="card-title">Content Block - {{ $content_block_cnt + 1 }}</h3>
        <div class="delete_content_icon_wrap float-right"><a href="javascript: void(0);" class="delete_content_block_btn"><i class="fas fa-trash-alt"></i></a></div>
    </div>
    <div class="card-body">
        <label>Content Title</label>
        <div class="input-group mb-3 title_row">
            <input type="text" name="content_title[{{ $content_uuid }}]" class="form-control mr-2 content_fields" placeholder="Content Title" value="{{ $blog_content->content_title ?? '' }}">
        </div>

        <label>Blog Content</label>
        <textarea name="content_field[{{ $content_uuid }}]" class="blog_content_field">{{ $blog_content->content ?? '' }}</textarea>
    </div>

    @if( !empty($image_block) )
        @foreach ($image_block as $blog_img)
            @include('admin.blog.content-images-block', ['blog_img' => $blog_img])
        @endforeach
    @endif

    <div class="add-image-block"></div>

    <div class="input-group mb-3 block">
        <div class="col-md-4"></div>
        <div class="col-md-4"><button type="button" class="btn btn-block btn-info add-image-block-btn"><i class="fas fa-plus-circle"></i> Add Image Block</button></div>
        <div class="col-md-4"></div>
    </div>
</div>