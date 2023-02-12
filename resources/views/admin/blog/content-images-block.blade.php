<div class="card-body row content_images_wrapper" id="{{ $blog_img['image_uuid'] }}">
    <div class="col col-sm-4 content_image_section">
        @if( isset($blog_img['name']) && !empty($blog_img['name']) )
            <img src="{{ url('images/blogs/main/' . $blog_img['name']) }}" class="profile-user-img img-fluid img-circle" style="height:100px;">
            <button type="button" class="btn btn-outline-info btn-sm ml-3 change_image_btn">Change Image</button>
            <button type="button" class="btn btn-outline-warning btn-sm ml-3 download_image_btn" data-original-name="{{ $blog_img['original_name'] ?? '' }}">Download Image</button>
        @else
            <input class="form-control content_image" type="file" name="content_image[{{ $blog_img['image_uuid'] }}]">
        @endif
    </div>
    <div class="col col-sm-4"><input type="text" name="image_url[{{ $blog_img['image_uuid'] }}]" class="form-control mr-2 image_fields" placeholder="Image URL" value="{{ $blog_img['affiliate_url'] ?? '' }}"></div>
    <div class="col col-sm-4">
        <input type="text" name="image_alt_tag[{{ $blog_img['image_uuid'] }}]" class="form-control d-inline-block mr-2 image_fields" placeholder="Image Alt Tag" value="{{ $blog_img['alt_tag'] ?? '' }}" style="width:85%;">
        <div class="d-inline delete_image_wrap"><a href="javascript:void(0);" class="delete_image_btn"><i class="fas fa-trash-alt"></i></a></div>
    </div>
</div>