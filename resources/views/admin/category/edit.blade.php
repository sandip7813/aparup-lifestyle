@extends('admin.layouts.app')

@php $parent_id = ( !is_null($category->parent_id) ) ? $category->parent_id : 0; @endphp

@section('styles')
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<!-- summernote -->
<link rel="stylesheet" href="{{ asset('admin/plugins/summernote/summernote-bs4.min.css') }}">
@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Category Details</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.category.index') }}">Category</a></li>
              <li class="breadcrumb-item active title_wrap">{{ $category->name }}</li>
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
          <div class="col-md-2"></div>

          <div class="col-md-8">

            <!-- Input addon -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title title_wrap">{{ $category->name }}</h3>
                </div>
                <form id="edit-category-form" action="javascript: void(0);" enctype="multipart/form-data">
                <div class="card-body">
                        <label>Category Title</label>
                        <div class="input-group mb-3 title_row">
                            <input type="text" name="category_name" class="form-control mr-2" value="{{ $category->name }}" placeholder="Category Title">

                            <div class="icheck-primary d-inline">
                                <input type="checkbox" name="slug_modify" id="slug_modify" value="1" data-toggle="tooltip" data-placement="top" title="Check this box to regenerate the slug.">
                                <label for="slug_modify"></label>
                            </div>
                        </div>

                        <label>Category Slug</label>
                        <div class="input-group mb-3 title_row">
                            <input type="text" name="category_slug" class="form-control mr-2" value="{{ $category->slug }}" placeholder="Category Slug" readonly>

                            <div class="icheck-primary d-inline">
                                <input type="checkbox" name="slug_editable" id="slug_editable" value="1" data-toggle="tooltip" data-placement="top" title="Check this box to make the slug field editable.">
                                <label for="slug_editable"></label>
                            </div>
                        </div>

                        <label>Select Parent Category</label>
                        <ul>
                          <li class="pt-2 pb-2" style="list-style-type:none;">
                            <div class="icheck-success d-inline mr-5">
                              <input type="radio" name="parent_category" id="parent_category_0" value="0" checked>
                              <label for="parent_category_0" style="font-weight: normal;">No Parent</label>
                            </div>
                          </li>
                            @if (count($categoriesTree) > 0)
                              @foreach ($categoriesTree as $categoryT)
                                @include('admin.category.tree-dropdown', ['categoryT' => $categoryT, 'p_id' => $parent_id])
                              @endforeach
                            @endif
                        </ul>

                        <label>Content</label>
                        <div class="input-group mb-3 title_row">
                          <textarea name="content" id="content">{{ $category->content }}</textarea>
                        </div>

                        <label>Page Title</label>
                        <div class="input-group mb-3 title_row">
                          <input type="text" name="page_title" class="form-control mr-2" value="{{ $category->page_title }}" placeholder="Page Title">
                        </div>

                        <label>Meta Data</label>
                        <div class="input-group mb-3 title_row">
                          <textarea name="metadata" id="metadata" class="form-control" rows="3" placeholder="Enter Meta Data">{{ $category->metadata }}</textarea>
                        </div>

                        <label>Keywords</label>
                        <div class="input-group mb-3 title_row">
                          <textarea name="keywords" id="keywords" class="form-control" rows="3" placeholder="Enter Keywords">{{ $category->keywords }}</textarea>
                        </div>

                        @if( $category->blogs->count() == 0 )
                          <label>Status</label>
                          <div class="input-group mb-3 title_row">
                              <div class="icheck-success d-inline mr-5">
                                <input type="radio" name="category_status" id="status_active" value="1" @if($category->status == '1')checked @endif>
                                <label for="status_active">Active</label>
                              </div>
                              <div class="icheck-danger d-inline">
                                <input type="radio" name="category_status" id="status_inactive" value="0" @if($category->status == '0')checked @endif>
                                <label for="status_inactive">Inactive</label>
                              </div>
                          </div>
                        @endif
                    </div>

                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success float-right" id="update-category-submit">Update</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (left) -->

          <div class="col-md-2"></div>

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection

@section('scripts')
<!-- Summernote -->
<script src="{{ asset('admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script>
  $(function () {
    content = $('#content');
    // Summernote
    content.summernote({
      height: 300
    });

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    slug_modify_field = $('input[name="slug_modify"]');
    slug_editable_field = $('input[name="slug_editable"]');
    category_slug_field = $('input[name="category_slug"]');

    //++++++++++++++++++++ REGENERATE SLUG :: Start ++++++++++++++++++++//
    $('input[name="category_name"]').on('blur', function(){
      regenerate_slug();
      if( slug_modify_field.is(':checked') ){
        regenerate_slug();
      }
      else{
        category_slug_field.val('{{ $category->slug }}');
      }
    });

    slug_modify_field.on('change', function(){
      if( $(this).is(':checked') ){
        regenerate_slug();
      }
      else{
        category_slug_field.val('{{ $category->slug }}');
      }
    });
    //++++++++++++++++++++ REGENERATE SLUG :: End ++++++++++++++++++++//

    slug_editable_field.on('change', function(){
      if( $(this).is(':checked') ){
        category_slug_field.prop('readonly', false);
      }
      else{
        category_slug_field.val('{{ $category->slug }}').prop('readonly', true);
      }
    });

    //++++++++++++++++++++ SUBMIT FORM :: Start ++++++++++++++++++++//
    $('#edit-category-form').submit(function(e){
      update_category_btn = $('#update-category-submit');

      e.preventDefault();
      var formData = new FormData(this);

      update_category_btn.html('<i class="fa fa-spinner" aria-hidden="true"></i> Updating...').attr('disabled', true);

      //
      $.ajax({
        dataType: 'json',
        type: 'POST',
        data: formData,
        url: "{{ route('admin.category.update-submit', $category->uuid) }}",
        cache: false,
        contentType: false,
        processData: false,
        success:function(data) {
          update_category_btn.html('Update').attr('disabled', false);

          if( data.status == 'failed' ){
            swal_fire_error(data.error.message);
            return false;
          }
          else if( data.status == 'success' ){
            swal_fire_success('Category info updated successfully!');

            $('.title_wrap').html(category_name);
          }

          $('.btn').attr('disabled', false);
          update_category_btn.html('Submit');
        }
      });
      //
    });
    //++++++++++++++++++++ SUBMIT FORM :: End ++++++++++++++++++++//
    
  });

  function regenerate_slug(){
    category_name = $('input[name="category_name"]').val().trim();

    $.ajax({
      dataType: 'json',
      type: 'POST',
      data:{
        category_name: category_name,
      },
      url: "{{ route('admin.category.regenerate-slug') }}",
      success:function(data) {
        if( data.status == 'failed' ){
          swal_fire_error(data.error.message);
          return false;
        }
        else if( data.status == 'success' ){
          category_slug_field.val(data.category_slug);
        }
      }
    });
  }

</script>
@endsection
