@extends('admin.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalActiveCategories }}</h3>
                            <p>Active Categories</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <a href="{{ route('admin.category.index', ['cat_status' => 1]) }}" class="small-box-footer">View All Active Categories <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $totalActiveBlogs }}</h3>
                            <p>Active Blogs</p>
                        </div>
                        <div class="icon">
                            <i class="far fa-newspaper"></i>
                        </div>
                        <a href="{{ route('admin.blog.index', ['blog_status' => 1]) }}" class="small-box-footer">View All Active Blogs <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $totalDraftedBlogs }}</h3>
                            <p>Drafted Blogs</p>
                        </div>
                        <div class="icon">
                            <i class="far fa-edit"></i>
                        </div>
                        <a href="{{ route('admin.blog.index', ['blog_status' => 2]) }}" class="small-box-footer">View All Drafted Blogs <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $totalInactiveBlogs }}</h3>
                            <p>Inactive Blogs</p>
                        </div>
                        <div class="icon">
                            <i class="far fa-eye-slash"></i>
                        </div>
                        <a href="{{ route('admin.blog.index', ['blog_status' => 0]) }}" class="small-box-footer">View All Inactive Blogs <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <!-- /.row -->

                <div class="row">
                    
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title">Recent Active Blogs</h3>
                                <div class="card-tools">
                                    <a href="{{ route('admin.blog.index', ['blog_status' => 1]) }}" class="btn btn-tool btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-striped table-valign-middle">
                                    <thead>
                                        <tr>
                                            <th>Banner</th>
                                            <th>Tite</th>
                                            <th>Category</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    @if( $recentActiveBlogs->count() > 0 )
                                    <tbody>
                                        @foreach($recentActiveBlogs as $blog)
                                        @php
                                            $category_names_array = ( $blog->categories()->count() > 0 ) ? $blog->categories()->pluck('name')->toArray() : [];
                                            $category_names = ( count($category_names_array) > 0 ) ? implode(', ', $category_names_array) : '';
                                        @endphp
                                        <tr>
                                            <td>
                                                @if( isset($blog->banner->name) )
                                                    <img src="{{ asset('images/blogs/200x160/' . $blog->banner->name) }}" class="img-circle img-size-32">
                                                @else
                                                    <img src="{{ asset('images/no-image.png') }}" class="img-circle img-size-32">
                                                @endif
                                            </td>
                                            <td>{{ $blog->title }}</td>
                                            <td>{{ $category_names }}</td>
                                            <td>
                                                <a href="{{ route('admin.blog.edit', $blog->uuid) }}" data-toggle="tooltip" data-placement="top" title="Edit this Blog info"><i class="fas fa-edit"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    @endif
                                </table>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title">Recent Categories</h3>
                                <div class="card-tools">
                                    <a href="{{ route('admin.category.index') }}" class="btn btn-tool btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-striped table-valign-middle">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Total Blogs</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    @if( $recentCategories->count() > 0 )
                                    <tbody>
                                        @foreach($recentCategories as $cat)
                                        <tr>
                                            <td>{{ $cat->name }}</td>
                                            <td>{{ $cat->blogs->count() }}</td>
                                            <td>@if( $cat->status == 1 ) Active @else Inactive @endif</td>
                                            <td>
                                                <a href="{{ route('admin.category.edit', $cat->uuid) }}" data-toggle="tooltip" data-placement="top" title="Edit this Category info"><i class="fas fa-edit"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    @endif
                                </table>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>

                </div>

            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

  @endsection