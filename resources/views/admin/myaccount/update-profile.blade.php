@extends('admin.layouts.app')

@php
$authUser = Auth::user();
$authUser->load(['profile_picture']);
@endphp

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Update Profile</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
        <div class="col-md-3"></div>

        <div class="col-md-6">

          <!-- Input addon -->
          <div class="card card-info">
              <div class="card-header">
                  <h3 class="card-title">Update Your Profile</h3>
              </div>

              <form id="update-profile-form" action="javascript: void(0);" enctype="multipart/form-data" class="form-horizontal">
                <div class="card-body">

                  <div class="form-group">
                      <label for="full_name" class="col-md-4 control-label">Full Name</label>

                      <div class="col-md-12">
                        <input id="full_name" type="text" class="form-control" name="full_name" value="{{ $authUser->name }}">
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="col-md-4 control-label">Email ID</label>

                      <div class="col-md-12">
                          <input type="text" class="form-control" value="{{ $authUser->email }}" disabled>
                      </div>
                  </div>

                  @if( isset($authUser->profile_picture->name) )
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-6">
                          <img src="{{ asset('images/users/300x300/' . $authUser->profile_picture->name) }}" width="150" style="width: 150px;">
                      </div>
                      <div class="col-md-6">
                        <div class="row h-100">
                          <div class="col-sm-12 my-auto">
                            <button type="button" class="btn btn-outline-danger btn-sm ml-3" id="delete_profie_picture_btn">Delete Profile Picture</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endif

                  <div class="form-group">
                      <label for="profile_picture" class="col-md-4 control-label">Upload Profile Picture</label>

                      <div class="col-md-12">
                          <input class="form-control" type="file" name="profile_picture" id="profile_picture">
                      </div>
                  </div>

                  <div class="form-group">
                      <label for="short_bio" class="col-md-4 control-label">Short Bio</label>

                      <div class="col-md-12">
                          <textarea name="short_bio" id="short_bio" class="form-control" rows="4" placeholder="Enter Short Bio">{{ $authUser->user_bio }}</textarea>
                      </div>
                  </div>

                  <div class="form-group">
                      <div class="col-md-12">
                          <button type="submit" class="btn btn-primary" id="update-profile-btn">Update Profile</button>
                      </div>
                  </div>
                </div>
              </form>
          </div>
          <!-- /.card -->
        </div>
        <!--/.col (left) -->

        <div class="col-md-3"></div>
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
<script>
  $(function () {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    //+++++++++++++++++++ UPDATE PROFILE :: Start +++++++++++++++++++//
    $('#update-profile-form').submit(function(e){
      this_obj = $(this);
      submit_btn = $('#update-profile-btn');

      e.preventDefault();
      var formData = new FormData(this);

      submit_btn.html('<i class="fa fa-spinner" aria-hidden="true"></i> Updating...').attr('disabled', true);

      $.ajax({
        dataType: 'json',
        type: 'POST',
        data: formData ,
        url: "{{ route('admin.myaccount.update-profile-submit') }}",
        cache: false,
        contentType: false,
        processData: false,
        success:function(data) {
          $('#blog_banner').val('');
          submit_btn.html('Update Profile').attr('disabled', false);

          if( data.status == 'failed' ){
            swal_fire_error(data.error.message);
            return false;
          }
          else if( data.status == 'success' ){
            swal_fire_success('Profile details updated successfully!');
            location.reload(true);
          }
        }
      });
    });
    //+++++++++++++++++++ UPDATE PROFILE :: Start +++++++++++++++++++//

    //+++++++++++++++++++ DELETE PROFILE PICTURE :: Start +++++++++++++++++++//
    $('#delete_profie_picture_btn').on('click', function(e){
      thisObj = $(this);

      e.preventDefault();

      Swal.fire({
        title: 'Do you want to delete your profile picture permanently?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
      }).then((result) => {
        if (result.isConfirmed) {
          
          thisObj.html('<i class="fa fa-spinner" aria-hidden="true"></i> Deleting...').attr('disabled', true);

          $.ajax({
            dataType: 'json',
            type: 'POST',
            url: "{{ route('admin.myaccount.delete-profile-picture') }}",
            success:function(data) {
              if( data.status == 'failed' ){
                  swal_fire_error(data.error.message);
                  thisObj.html('Delete Profile Picture').attr('disabled', false);
                  return false;
              }
              else if( data.status == 'success' ){
                  swal_fire_success('Profile picture deleted successfully!');
                  location.reload(true);
              }
            }
          });
        }
      });
    });
    //+++++++++++++++++++ DELETE PROFILE PICTURE :: End +++++++++++++++++++//
  });
</script>
@endsection
