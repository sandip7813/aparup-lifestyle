@extends('admin.layouts.app')

@php
$category_ids = ( $blog->categories()->count() > 0 ) ? $blog->categories()->pluck('id')->toArray() : [];
@endphp

@section('styles')
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<!-- summernote -->
<link rel="stylesheet" href="{{ asset('admin/plugins/summernote/summernote-bs4.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Post Blog</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">Blog</a></li>
              <li class="breadcrumb-item active">Post New</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <!-- Input addon -->
            <div class="card card-info">
              <div class="card-header">
                  <h3 class="card-title">Post New Blog</h3>
              </div>
              <form id="post-blog-form" action="javascript: void(0);" enctype="multipart/form-data">
                <input type="hidden" name="blog_uuid" id="blog_uuid" value="{{ $blog_uuid }}">

                <div class="card-body">
                  <label>Blog Title</label>
                  <div class="input-group mb-3 title_row">
                    <input type="text" name="blog_title" class="form-control mr-2" placeholder="Blog Title" value="{{ $blog->title }}">

                    @if($post_type == 'edit')
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" name="slug_modify" id="slug_modify" value="1" data-toggle="tooltip" data-placement="top" title="Check this box to regenerate the slug.">
                        <label for="slug_modify"></label>
                    </div>
                    @endif
                  </div>

                  @if($post_type == 'edit')
                  <label>Blog Slug</label>
                  <div class="input-group mb-3 title_row">
                      <input type="text" name="blog_slug" class="form-control mr-2" value="{{ $blog->slug }}" placeholder="Blog Slug" disabled>

                      <div class="icheck-primary d-inline">
                          <input type="checkbox" name="slug_editable" id="slug_editable" value="1" data-toggle="tooltip" data-placement="top" title="Check this box to make the slug field editable.">
                          <label for="slug_editable"></label>
                      </div>
                  </div>
                  @endif

                  @if( $categories )
                    <label>Select Categories</label>
                    <div class="form-group">
                      <select name="blog_category[]" id="blog_category" class="select2" multiple="multiple" data-placeholder="Select Categories" style="width: 100%;">
                        @foreach($categories as $cat)
                          <option value="{{ $cat->id }}" @if( in_array($cat->id, $category_ids) ) selected @endif>{{ $cat->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  @endif

                  <label>Blog Content</label>
                  <textarea name="blog_content" id="blog_content" class="main_content_field">{{ $blog->content }}</textarea>
                  
                  <label>Upload Banner</label>
                  <div class="input-group mb-3 title_row">
                    <input class="form-control" type="file" name="banner" id="blog_banner">
                  </div>
                  
                  @if( isset($blog->contents) && $blog->contents->count() > 0 )
                    @foreach ($blog->contents as $content_block_cnt => $blog_content)
                      @include('admin.blog.content-block', ['content_block_cnt' => $content_block_cnt, 'blog_content' => $blog_content])
                    @endforeach
                  @endif

                  <div id="add-content-block"></div>

                  <div class="input-group mb-3 block">
                    <div class="col-md-3"></div>
                    <div class="col-md-6"><button type="button" class="btn btn-block btn-info" id="content-block-btn"><i class="fas fa-plus-circle"></i> Add Content Block</button></div>
                    <div class="col-md-3"></div>
                  </div>

                  <label>Page Title</label>
                  <div class="input-group mb-3 title_row">
                    <input type="text" name="page_title" class="form-control mr-2" placeholder="Page Title" value="{{ $blog->page_title }}">
                  </div>

                  <label>Meta Data</label>
                  <div class="input-group mb-3 title_row">
                    <textarea name="metadata" id="metadata" class="form-control" rows="3" placeholder="Enter Meta Data">{{ $blog->metadata }}</textarea>
                  </div>

                  <label>Save Blog As</label>
                  <div class="input-group mb-3 title_row">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                      <label class="btn bg-olive @if($blog->status == '1') active @endif">
                        <input type="radio" name="blog_status" id="blog_active" autocomplete="off" value="1" @if($blog->status == '1') checked @endif> Active
                      </label>
                      <label class="btn bg-olive @if($blog->status == '0') active @endif">
                        <input type="radio" name="blog_status" id="blog_inactive" autocomplete="off" value="0" @if($blog->status == '0') checked @endif> Inactive
                      </label>
                      <label class="btn bg-olive @if($blog->status == '2') active @endif">
                        <input type="radio" name="blog_status" id="blog_draft" autocomplete="off" value="2" @if($blog->status == '2') checked @endif> Draft
                      </label>
                    </div>
                  </div>

                </div>

                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-success float-right submit_btn" id="submit-blog-btn">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (left) -->

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection

@section('scripts')
<!-- Select2 -->
<script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script>
  $(function () {
    blog_content = $('.blog_content_field, .main_content_field');
    blog_category = $('#blog_category');
    post_blog_form = $('#post-blog-form');
    blog_uuid = $('#blog_uuid').val();
    add_content_block = $('#add-content-block');

    @if($post_type == 'edit')
      slug_modify_field = $('input[name="slug_modify"]');
      slug_editable_field = $('input[name="slug_editable"]');
      blog_slug_field = $('input[name="blog_slug"]');
    @endif

    blog_category.select2({
      theme: 'bootstrap4'
    });

    // Summernote
    blog_content.summernote({height: 300});

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    @if($post_type == 'edit')
      //++++++++++++++++++++ REGENERATE SLUG :: Start ++++++++++++++++++++//
      $('input[name="blog_title"]').on('blur', function(){
        blog_title = $(this).val().trim();

        if( slug_modify_field.is(':checked') ){
          regenerate_slug();
        }
        else{
          blog_slug_field.val('{{ $blog->slug }}');
        }
      });

      slug_modify_field.on('change', function(){
        if( $(this).is(':checked') ){
          regenerate_slug();
        }
        else{
          blog_slug_field.val('{{ $blog->slug }}');
        }
      });
      //++++++++++++++++++++ REGENERATE SLUG :: End ++++++++++++++++++++//

      $('input[name="slug_editable"]').on('change', function(){
        if( $(this).is(':checked') ){
          blog_slug_field.prop('disabled', false);
        }
        else{
          blog_slug_field.val('{{ $blog->slug }}').prop('disabled', true);
        }
      });
    @endif

    //+++++++++++++++ POST BLOG :: Start +++++++++++++++//
    post_blog_form.submit(function(e){
      this_obj = $(this);
      submit_btn = $('#submit-blog-btn');

      e.preventDefault();
      var formData = new FormData(this);
      formData.append('post_type', '{{ $post_type }}');

      submit_btn.html('<i class="fa fa-spinner" aria-hidden="true"></i> Submitting...').attr('disabled', true);

      $.ajax({
        dataType: 'json',
        type: 'POST',
        data: formData ,
        url: "{{ route('admin.blog.submit') }}",
        cache: false,
        contentType: false,
        processData: false,
        success:function(data) {
          $('#blog_banner').val('');
          submit_btn.html('Submit').attr('disabled', false);

          if( data.status == 'failed' ){
            swal_fire_error(data.error.message);
            return false;
          }
          else if( data.status == 'success' ){
            @if($post_type == 'create')
              swal_fire_success('Blog created successfully!');
            @elseif($post_type == 'edit')
              swal_fire_success('Blog updated successfully!');
            @endif
          }
        }
      });
    });
    //+++++++++++++++ POST BLOG :: End +++++++++++++++//

    //+++++++++++++++ ADD CONTENT & IMAGE BLOCK :: Start +++++++++++++++//
    $('#content-block-btn').on('click', function(){
      content_block_cnt = $('.blog_content_wrapper').length;

      $.ajax({
        dataType: 'json',
        type: 'POST',
        data:{
          blog_uuid: blog_uuid,
          content_block_cnt: content_block_cnt
        },
        url: "{{ route('admin.blog.add-blog-content') }}",
        success:function(data) {
          if( data.status == 'failed' ){
            swal_fire_error(data.error.message);
            return false;
          }
          else if( data.status == 'success' ){
            add_content_block.append(data.html_view);
            add_content_block.find('.blog_content_field').summernote({ height: 300 });
          }
        }
      });
    });
    
    $(document).on('click', '.add-image-block-btn', function(){
      thisObj = $(this);
      content_uuid = thisObj.parents('.blog_content_wrapper').attr('id');

      $.ajax({
        dataType: 'json',
        type: 'POST',
        data:{
          content_uuid: content_uuid,
        },
        url: "{{ route('admin.blog.add-blog-content-images') }}",
        success:function(data) {
          if( data.status == 'failed' ){
            swal_fire_error(data.error.message);
            return false;
          }
          else if( data.status == 'success' ){
            thisObj.parents('.blog_content_wrapper').find('.add-image-block').append(data.html_view);
          }
        }
      });
    }); 
    //+++++++++++++++ ADD CONTENT & IMAGE BLOCK :: End +++++++++++++++//

    //+++++++++++++++ UPLOAD CONTENT IMAGES ON SELECT :: Start +++++++++++++++//
    $(document).on('change', '.content_image', function(e){
      e.preventDefault();

      thisObj = $(this);
      content_uuid = thisObj.parents('.blog_content_wrapper').attr('id');
      image_uuid = thisObj.parents('.content_images_wrapper').attr('id');

      var formData = new FormData();
      var files = thisObj[0].files;

      formData.append('file',files[0]);
      formData.append('content_uuid', content_uuid);
      formData.append('image_uuid', image_uuid);

      thisObj.prop('disabled', true);

      $.ajax({
        dataType: 'json',
        type: 'POST',
        data: formData ,
        url: "{{ route('admin.blog.upload-blog-content-image') }}",
        cache: false,
        contentType: false,
        processData: false,
        success:function(data) {
          if( data.status == 'failed' ){
            swal_fire_error(data.error.message);
            return false;
          }
          else if( data.status == 'success' ){
            thisObj.parents('.content_images_wrapper').find('.content_image_section').html(data.image_html);
          }
        }
      });
    });

    $(document).on('click', '.change_image_btn', function(e){
      e.preventDefault();

      thisObj = $(this);
      content_uuid = thisObj.parents('.blog_content_wrapper').attr('id');
      image_uuid = thisObj.parents('.content_images_wrapper').attr('id');

      image_field = '<input class="form-control content_image" type="file" name="content_image['+ image_uuid +']">';
      thisObj.parents('.content_image_section').html(image_field);
    });
    //+++++++++++++++ UPLOAD CONTENT IMAGES ON SELECT :: End +++++++++++++++//

    //+++++++++++++++++++ DELETE IMAGE ROW :: Start +++++++++++++++++++//
    $(document).on('click', '.delete_image_btn', function(e){
      thisObj = $(this);

      deleteIconHtml = '<a href="javascript:void(0);" class="delete_image_btn"><i class="fas fa-trash-alt"></i></a>';
      loaderHtml = '<div class="spinner-border text-info" role="status"><span class="sr-only">Deleting...</span></div>';

      content_uuid = thisObj.parents('.blog_content_wrapper').attr('id');
      image_uuid = thisObj.parents('.content_images_wrapper').attr('id');

      Swal.fire({
        title: 'Do you want to delete this image row permanently?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
      }).then((result) => {
        if (result.isConfirmed) {
          thisObj.parents('.delete_image_wrap').html(loaderHtml);

          $.ajax({
            dataType: 'json',
            type: 'POST',
            data: {
              content_uuid: content_uuid,
              image_uuid: image_uuid
            },
            url: "{{ route('admin.blog.delete-blog-content-image-row') }}",
            success:function(data) {
              if( data.status == 'failed' ){
                  swal_fire_error(data.error.message);
                  thisObj.parents('.delete_image_wrap').html(deleteIconHtml);
                  return false;
              }
              else if( data.status == 'success' ){
                  swal_fire_success('Image row deleted successfully!');
                  $('#' + image_uuid).fadeOut('slow');
              }
            }
          });
        }
      });
    });
    //+++++++++++++++++++ DELETE IMAGE ROW :: End +++++++++++++++++++//

    //+++++++++++++++++++ DOWNLOAD IMAGE :: Start +++++++++++++++++++//
    $(document).on('click', '.download_image_btn', function(e){
      thisObj = $(this);

      content_uuid = thisObj.parents('.blog_content_wrapper').attr('id');
      image_uuid = thisObj.parents('.content_images_wrapper').attr('id');

      original_name = thisObj.data('original-name');

      $.ajax({
            type: 'GET',
            data: {
              content_uuid: content_uuid,
              image_uuid: image_uuid
            },
            xhrFields: {
                responseType: 'blob'
            },
            url: "{{ route('admin.blog.download-blog-content-image') }}",
            success:function(data) {
              var blob = new Blob([data]);
              var link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              link.download = original_name;
              link.click();
            }
      });
    });
    //+++++++++++++++++++ DOWNLOAD IMAGE :: End +++++++++++++++++++//

    //+++++++++++++++++++ SAVE IMAGE FIELDS DATA INTO DATABASE :: Start +++++++++++++++++++//
    $(document).on('blur', '.image_fields', function(e){
      thisObj = $(this);

      content_uuid = thisObj.parents('.blog_content_wrapper').attr('id');
      image_uuid = thisObj.parents('.content_images_wrapper').attr('id');

      image_url_field = $('input[name="image_url[' + image_uuid + ']"]');
      image_alt_tag_field = $('input[name="image_alt_tag[' + image_uuid + ']"]');
      image_copyright_field = $('input[name="image_copyright[' + image_uuid + ']"]');

      image_url = image_url_field.val().trim();
      image_alt_tag = image_alt_tag_field.val().trim();
      image_copyright = image_copyright_field.val().trim();

      $.ajax({
            dataType: 'json',
            type: 'POST',
            data: {
              content_uuid: content_uuid,
              image_uuid: image_uuid,
              image_url: image_url,
              image_alt_tag: image_alt_tag,
              image_copyright: image_copyright
            },
            url: "{{ route('admin.blog.save-blog-content-image-fields') }}",
            success:function(data) {
              image_url_field.val(data.image_url);
              image_alt_tag_field.val(data.image_alt_tag);
              image_copyright_field.val(data.image_copyright);

              if( data.status == 'failed' ){
                  swal_fire_error(data.error.message);
                  return false;
              }
            }
          });
    });
    //+++++++++++++++++++ SAVE IMAGE FIELDS DATA INTO DATABASE :: End +++++++++++++++++++//

    //+++++++++++++++++++ SAVE CONTENT FIELDS DATA INTO DATABASE :: Start +++++++++++++++++++//
    $(document).on('blur', '.content_fields', function(e){
      thisObj = $(this);

      content_uuid = thisObj.parents('.blog_content_wrapper').attr('id');
      content_title_field = $('input[name="content_title[' + content_uuid + ']"]').val().trim();

      $.ajax({
            dataType: 'json',
            type: 'POST',
            data: {
              content_uuid: content_uuid,
              content_title_field: content_title_field,
            },
            url: "{{ route('admin.blog.save-blog-content-fields') }}",
            success:function(data) {
              if( data.status == 'failed' ){
                  swal_fire_error(data.error.message);
                  return false;
              }
            }
          });
    });

    $(document).on('summernote.blur', '.blog_content_field', function(){
      thisObj = $(this);

      content_uuid = thisObj.parents('.blog_content_wrapper').attr('id');
      content_text_field = $(this).val();

      $.ajax({
            dataType: 'json',
            type: 'POST',
            data: {
              content_uuid: content_uuid,
              content_text_field: content_text_field
            },
            url: "{{ route('admin.blog.save-blog-content-fields') }}",
            success:function(data) {
              if( data.status == 'failed' ){
                  swal_fire_error(data.error.message);
                  return false;
              }
            }
          });
    });
    //+++++++++++++++++++ SAVE CONTENT FIELDS DATA INTO DATABASE :: End +++++++++++++++++++//

    //+++++++++++++++++++ DELETE IMAGE ROW :: Start +++++++++++++++++++//
    $(document).on('click', '.delete_content_block_btn', function(e){
      thisObj = $(this);

      deleteIconHtml = '<a href="javascript: void(0);" class="delete_content_block_btn"><i class="fas fa-trash-alt float-right"></i></a>';
      loaderHtml = '<div class="spinner-border text-warning" role="status"><span class="sr-only">Deleting...</span></div>';

      content_uuid = thisObj.parents('.blog_content_wrapper').attr('id');

      Swal.fire({
        title: 'Do you want to delete this content row permanently?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
      }).then((result) => {
        if (result.isConfirmed) {
          thisObj.parents('.delete_content_icon_wrap').html(loaderHtml);

          $.ajax({
            dataType: 'json',
            type: 'POST',
            data: {
              content_uuid: content_uuid,
            },
            url: "{{ route('admin.blog.delete-blog-content-row') }}",
            success:function(data) {
              if( data.status == 'failed' ){
                  swal_fire_error(data.error.message);
                  thisObj.parents('.delete_content_icon_wrap').html(deleteIconHtml);
                  return false;
              }
              else if( data.status == 'success' ){
                  swal_fire_success('Content block deleted successfully!');
                  $('#' + content_uuid).fadeOut('slow');
              }
            }
          });
        }
      });
    });
    //+++++++++++++++++++ DELETE IMAGE ROW :: End +++++++++++++++++++//
    
  });

  @if($post_type == 'edit')
    function regenerate_slug(){
      blog_title = $('input[name="blog_title"]').val().trim();

      $.ajax({
        dataType: 'json',
        type: 'POST',
        data:{
          blog_title: blog_title,
        },
        url: "{{ route('admin.blog.regenerate-slug') }}",
        success:function(data) {
          if( data.status == 'failed' ){
            swal_fire_error(data.error.message);
            return false;
          }
          else if( data.status == 'success' ){
            blog_slug_field.val(data.blog_slug);
          }
        }
      });
    }
  @endif

</script>
@endsection
