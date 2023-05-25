<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Categories;
use App\Models\Blogs;
use App\Models\Medias;
use App\Models\BlogContents;

use Auth;

use Illuminate\Support\Str;
use Validator;
use Image;
use Illuminate\Support\Facades\File; 
use Response;
use Carbon\Carbon;

class BlogController extends Controller
{
    public $categories;

    protected $statusArray = [
        1 => 'Active',
        0 => 'Inactive',
        2 => 'Drafted',
        3 => 'Scheduled',
    ];

    protected $statusColorClass = [
        1 => '',
        0 => 'table-danger',
        2 => 'table-secondary',
        3 => 'table-warning',
    ];

    public function __construct(){
        $this->categories = Categories::orderBy('name', 'asc')->get();
    }

    public function index(Request $request){
        $blogs_qry = Blogs::with(['categories', 'banner'])->where('page_type', 'blog_page');

        if( $request->filled('blog_title') ){
            $blogs_qry->where('title', 'like', '%' . $request->blog_title . '%');
        }

        if( $request->filled('blog_category') ){
            $category_id = $request->blog_category;
            $blogs_qry->whereHas('categories', function ($q) use ($category_id) {
                $q->where('id', $category_id);
            });
        }

        if( $request->filled('blog_status') ){
            $blogs_qry->where('status', $request->blog_status);
        }

        $blogs = $blogs_qry->orderby('updated_at','desc')->paginate(10);
        
        return view('admin.blog.index')->with([
                                        'blogs' => $blogs, 
                                        'categories' => $this->categories,
                                        'statusArray' => $this->statusArray,
                                        'statusColorClass' => $this->statusColorClass
                                    ]);
    }

    public function redirectToCreate(){
        $blog = Blogs::create(['status' => '2']);
        return redirect()->route('admin.blog.create', $blog->uuid);
    }

    public function create($uuid){
        $blog = Blogs::with(['categories', 'banner', 'contents'])
                        ->where('uuid', $uuid)
                        ->where('page_type', 'blog_page')
                        ->first();

        if( !isset($blog->id) ){
            abort(404);
        }

        return view('admin.blog.create', compact('blog'))->with([
                                            'categories' => $this->categories,
                                            'blog_uuid' => $uuid,
                                            'post_type' => 'create'
                                        ]);
    }

    public function edit($uuid){
        $blog = Blogs::with(['categories', 'banner', 'contents'])
                        ->where('uuid', $uuid)
                        ->where('page_type', 'blog_page')
                        ->first();
        
        if( !isset($blog->id) ){
            abort(404);
        }

        return view('admin.blog.create', compact('blog'))->with([
                                            'categories' => $this->categories,
                                            'blog_uuid' => $uuid,
                                            'post_type' => 'edit'
                                        ]);
    }

    public function regenerateSlug(Request $request){
        $response = [];

        $response['status'] = '';
        $response['blog_slug'] = '';

        try {
            $blog_title = $request->blog_title ?? '';

            if( $blog_title == '' ){
                return response()->json(['status' => 'failed', 'error' => ['message' => 'No blog title found!']]);
            }

            $response['blog_slug'] = Blogs::generateSlug($blog_title);           
            $response['status'] = 'success';

        } catch (\Exception $e) {
            report($e);
            return response()->json(['status' => 'failed', 'error' => ['message' => $e->getMessage()], 'e' => $e]);
        }

        return response()->json($response);
    }

    public function blogSubmit(Request $request){
        $response = [];

        $response['status'] = '';
        
        //try {
            $post_type = $request->post_type ?? null;
            $blog_uuid = $request->blog_uuid ?? null;
            $blog_title = $request->blog_title ?? null;

            if( $post_type == 'edit' ){
                $slug_editable = $request->slug_editable ?? 0;
                $slug_modify = $request->slug_modify ?? 0;
                $blog_slug = $request->blog_slug ?? null;
            }

            $blog = Blogs::with(['categories', 'banner'])->where('uuid', $blog_uuid)->first();
            $existing_blog_title = $blog->title ?? null;
            
            $validator_array = [];

            $validator_array['blog_uuid'] = 'required';

            if( $request->blog_status != '2' ){
                $validator_array['blog_title'] = 'required|max:255';
                $validator_array['blog_category'] = 'required';
                $validator_array['blog_content'] = 'required';
                $validator_array['short_content'] = 'required';
            }

            if( $request->blog_status == '3' ){
                $validator_array['schedule_at'] = 'required';
            }

            if( $post_type == 'edit' ){
                if( ($slug_editable || $slug_modify) && is_null($blog_slug) && !is_null($existing_blog_title) ){
                    $validator_array['blog_slug'] = 'required';
                }
            }

            $validator_array['banner'] = 'mimes:jpeg,jpg,png,gif|max:10000';
            $validator_array['blog_status'] = 'required';

            $validator = Validator::make($request->all(), $validator_array);

            $validator_errors = implode('<br>', $validator->errors()->all());

            if ($validator->fails()) {
                return response()->json(['status' => 'failed', 'error' => ['message' => $validator_errors]]);
            }

            if( ($post_type == 'edit') && !is_null($existing_blog_title) && ($slug_editable || $slug_modify) ){
                $duplicate_slug = Blogs::withTrashed()
                                        ->where('slug', $blog_slug)
                                        ->where('uuid', '!=', $blog_uuid)
                                        ->count();
                
                if( $duplicate_slug > 0 ){
                    return response()->json(['status' => 'failed', 'error' => ['message' => 'This slug can\'t be used!']]);
                }
            }

            $existingBanner = $blog->banner->name ?? null;

            if( is_null($existingBanner) && !$request->hasFile('banner') && ($request->banner_alt != '') ){
                return response()->json(['status' => 'failed', 'error' => ['message' => 'Please attach a banner image to provide it\'s alt tag!']]);
            }

            //+++++++++++++++++++++++++++ COMPARE SCHEDULED DATETIME WITH CURRENT DATETIME  :: Start +++++++++++++++++++++++++++//
            if( $request->blog_status == '3' ){
                $nowDate = Carbon::now();
                $checkDiff = $nowDate->gt($request->schedule_at);

                if( $checkDiff ){
                    return response()->json(['status' => 'failed', 'error' => ['message' => 'Schedule date time should be greater than current time!']]);
                }
            }
            //+++++++++++++++++++++++++++ COMPARE SCHEDULED DATETIME WITH CURRENT DATETIME  :: End +++++++++++++++++++++++++++//

            //+++++++++++++++++++++++++++ SAVE BLOG DETAILS :: Start +++++++++++++++++++++++++++//
            $blog->title = $blog_title;

            if( $post_type == 'create' ){
                $blog->slug = Blogs::generateSlug($blog_title);
            }
            elseif( $post_type == 'edit' ){
                if( ($slug_editable || $slug_modify) && !is_null($existing_blog_title) ){
                    $blog->slug = $blog_slug;
                }
                elseif(!is_null($blog_title) && is_null($blog_slug)){
                    $blog->slug = Blogs::generateSlug($blog_title);
                }
            }
            $blog->content = $request->blog_content ?? null;
            $blog->short_content = $request->short_content ?? null;
            $blog->page_title = $request->page_title ?? null;
            $blog->metadata = $request->metadata ?? null;
            $blog->status = $request->blog_status ?? null;
            $blog->scheduled_at = ($request->blog_status == '3') ? $request->schedule_at : null;

            $blog->save();
            //+++++++++++++++++++++++++++ SAVE BLOG DETAILS :: End +++++++++++++++++++++++++++//

            //+++++++++++++++++++++++++++ SAVE BLOG CATEGORIES :: Start +++++++++++++++++++++++++++//
            $blog->categories()->detach();

            $blog_category = $request->blog_category ?? [];
            $blog->categories()->attach($blog_category);
            //+++++++++++++++++++++++++++ SAVE BLOG CATEGORIES :: End +++++++++++++++++++++++++++//

            //+++++++++++++++++++++++++++ STORE & CROP IMAGES :: Start +++++++++++++++++++++++++++//
            if($request->hasFile('banner')) {
                $bannerDir = 'images/blogs/';
                
                //------------- DELETE EXISTING IMAGES :: Start -------------//
                if( !is_null($existingBanner) ){
                    File::delete( $bannerDir . 'main/' . $existingBanner );
                    File::delete( $bannerDir . '1000x600/' . $existingBanner );
                    File::delete( $bannerDir . '200x160/' . $existingBanner );

                    $blog->banner->delete();
                }
                //------------- DELETE EXISTING IMAGES :: End -------------//
                
                $banner = Image::make($request->file('banner'));

                $bannerName = time() . '-' . uniqid() . '.' . $request->file('banner')->getClientOriginalExtension();

                //------------- MAIN BANNER UPLOAD :: Start -------------//
                $destinationPath = public_path( $bannerDir . 'main/' );
                $banner->save($destinationPath . $bannerName);
                //------------- MAIN BANNER UPLOAD :: End -------------//

                //------------- 1000 x 600 BANNER UPLOAD :: Start -------------//
                $destinationPathThumbnail = public_path( $bannerDir . '1000x600/' );
                $banner->resize(1000, 600);
                $banner->save($destinationPathThumbnail . $bannerName);
                //------------- 1000 x 600 BANNER UPLOAD :: End -------------//
    
                //------------- 200 x 160 BANNER UPLOAD :: Start -------------//
                $destinationPathThumbnail = public_path( $bannerDir . '200x160/' );
                $banner->resize(200, 160);
                $banner->save($destinationPathThumbnail . $bannerName);
                //------------- 200 x 160 BANNER UPLOAD :: End -------------//

                Medias::create([
                    'user_id' => Auth::user()->id,
                    'media_type' => 'blog_banner',
                    'source_uuid' => $blog_uuid,
                    'name' => $bannerName,
                    'alt_tag' => $request->banner_alt ?? null,
                    'is_active' => 1
                ]);
            }
            else{
                if(!is_null($existingBanner)){
                    $blog->banner->alt_tag = $request->banner_alt ?? null;
                    $blog->banner->save();
                }
            }
            //+++++++++++++++++++++++++++ STORE & CROP IMAGES :: End +++++++++++++++++++++++++++//

            $response['status'] = 'success';
        /* } catch (\Exception $e) {
            report($e);
            return response()->json(['status' => 'failed', 'error' => ['message' => $e->getMessage()], 'e' => $e]);
        } */

        return response()->json($response);
    }

    public function changeBanner(Request $request){
        $response = [];

        $response['status'] = '';

        try {
            $validator_array = [];

            $validator_array['blog_modal_uuid'] = 'required';
            $validator_array['banner'] = 'required|mimes:jpeg,jpg,png,gif|max:10000';

            $validator = Validator::make($request->all(), $validator_array);

            $validator_errors = implode('<br>', $validator->errors()->all());

            if ($validator->fails()) {
                return response()->json(['status' => 'failed', 'error' => ['message' => $validator_errors]]);
            }

            //+++++++++++++++++++++++++++ STORE & CROP IMAGES :: Start +++++++++++++++++++++++++++//
            if($request->hasFile('banner')) {
                $blog_uuid = $request->blog_modal_uuid ?? '';

                $blog = Blogs::with('banner')->where('uuid', $blog_uuid)->where('page_type', 'blog_page')->first();

                if ( !isset($blog->id) ) {
                    return response()->json(['status' => 'failed', 'error' => ['message' => 'No blog found!']]);
                }

                $bannerDir = 'images/blogs/';

                //------------- DELETE EXISTING IMAGES :: Start -------------//
                $existingBanner = $blog->banner->name ?? null;

                if( !is_null($existingBanner) ){
                    File::delete( $bannerDir . 'main/' . $existingBanner );
                    File::delete( $bannerDir . '1000x600/' . $existingBanner );
                    File::delete( $bannerDir . '200x160/' . $existingBanner );

                    $blog->banner->delete();
                }
                //------------- DELETE EXISTING IMAGES :: End -------------//

                $banner = Image::make($request->file('banner'));

                $bannerName = time() . '-' . uniqid() . '.' . $request->file('banner')->getClientOriginalExtension();

                //------------- MAIN BANNER UPLOAD :: Start -------------//
                $destinationPath = public_path( $bannerDir . 'main/' );
                $banner->save($destinationPath . $bannerName);
                //------------- MAIN BANNER UPLOAD :: End -------------//

                //------------- 1000 x 600 BANNER UPLOAD :: Start -------------//
                $destinationPathThumbnail = public_path( $bannerDir . '1000x600/' );
                $banner->resize(1000, 600);
                $banner->save($destinationPathThumbnail . $bannerName);
                //------------- 1000 x 600 BANNER UPLOAD :: End -------------//
    
                //------------- 200 x 160 BANNER UPLOAD :: Start -------------//
                $destinationPathThumbnail = public_path( $bannerDir . '200x160/' );
                $banner->resize(200, 160);
                $banner->save($destinationPathThumbnail . $bannerName);
                //------------- 200 x 160 BANNER UPLOAD :: End -------------//

                Medias::create([
                    'user_id' => Auth::user()->id,
                    'media_type' => 'blog_banner',
                    'source_uuid' => $blog_uuid,
                    'name' => $bannerName,
                    'is_active' => 1
                ]);

                $response['banner_title'] = $bannerName;
                $response['status'] = 'success';
            }
            //+++++++++++++++++++++++++++ STORE & CROP IMAGES :: End +++++++++++++++++++++++++++//

        } catch (\Exception $e) {
            report($e);
            return response()->json(['status' => 'failed', 'error' => ['message' => $e->getMessage()], 'e' => $e]);
        }

        return response()->json($response);
    }

    public function deleteBlog(Request $request){
        $response = [];

        $response['status'] = '';

        try {
            $blog_uuid = $request->blog_uuid ?? '';

            if( $blog_uuid == '' ){
                return response()->json(['status' => 'failed', 'error' => ['message' => 'No blog found!']]);
            }

            $blog = Blogs::where('uuid', $blog_uuid)->where('page_type', 'blog_page')->first();
            $blog->delete();            

            $response['status'] = 'success';
        } catch (\Exception $e) {
            report($e);
            return response()->json(['status' => 'failed', 'error' => ['message' => $e->getMessage()], 'e' => $e]);
        }

        return response()->json($response);
    }

    public function addBlogContent(Request $request){
        $response = [];

        $response['status'] = '';

        try {
            $blog_uuid = $request->blog_uuid ?? '';

            $blog_content = BlogContents::create([
                                'blog_uuid' => $blog_uuid
                            ]);

            $content_block_cnt = $request->content_block_cnt ?? 0;
            $html_view = view('admin.blog.content-block', compact('content_block_cnt'))->with(['blog_content' => $blog_content])->render();

            $response['status'] = 'success';
            $response['html_view'] = $html_view;
        } catch (\Exception $e) {
            report($e);
            return response()->json(['status' => 'failed', 'error' => ['message' => $e->getMessage()], 'e' => $e]);
        }

        return response()->json($response);
    }

    public function addBlogContentImages(Request $request){
        $response = [];

        $response['status'] = '';

        try {
            $content_uuid = $request->content_uuid ?? '';

            $blog_content = BlogContents::where('uuid', $content_uuid)->first();
            $blog_images = $blog_content->images ?? [];

            $image_uuid = (string) Str::uuid();
            $blog_images[$image_uuid] = ['image_uuid' => $image_uuid];

            $blog_content->images = $blog_images;

            $blog_content->save();

            $html_view = view('admin.blog.content-images-block')->with(['blog_img' => $blog_images[$image_uuid]])->render();

            $response['status'] = 'success';
            $response['html_view'] = $html_view;
        } catch (\Exception $e) {
            report($e);
            return response()->json(['status' => 'failed', 'error' => ['message' => $e->getMessage()], 'e' => $e]);
        }

        return response()->json($response);
    }

    public function uploadBlogContentImage(Request $request){
        $response = [];

        $response['status'] = '';

        try {
            $validator = Validator::make($request->all(), [
                'content_uuid' => 'required',
                'image_uuid' => 'required',
                'file' => 'required|mimes:png,jpg,jpeg|max:2048'
            ]);

            $validator_errors = implode('<br>', $validator->errors()->all());
    
            if ($validator->fails()) {
                return response()->json(['status' => 'failed', 'error' => ['message' => $validator_errors]]);
            }

            if($request->hasFile('file')) {
                $content_image = $request->file('file');
                $imageMake = Image::make($content_image);

                $imageName = time() . '-' . uniqid() . '.' . $content_image->getClientOriginalExtension();

                $imageDir = 'images/blogs/main/';

                $destinationPath = public_path($imageDir);
                $imageMake->save($destinationPath . $imageName);

                $content_uuid = $request->content_uuid ?? null;
                $image_uuid = $request->image_uuid ?? null;

                //++++++++++++++ UPDATE IMAGE INFO IN DATABASE :: Start ++++++++++++++//
                $blog_content = BlogContents::where('uuid', $content_uuid)->first();
                $blog_images = $blog_content->images ?? [];

                $existing_image = $blog_images[$image_uuid]['name'] ?? null;

                if( !is_null($existing_image) ){
                    if(File::exists($destinationPath . $existing_image)){
                        File::delete($destinationPath . $existing_image);
                    }
                }

                $originalName = $content_image->getClientOriginalName();

                $blog_images[$image_uuid] = [
                                                'image_uuid' => $image_uuid,
                                                'name' => $imageName,
                                                'original_name' => $originalName,
                                            ];

                $blog_content->images = $blog_images;
                $blog_content->save();
                //++++++++++++++ UPDATE IMAGE INFO IN DATABASE :: End ++++++++++++++//

                $image_url = url($imageDir . $imageName);

                $image_html = '<img src="' . $image_url . '" class="profile-user-img img-fluid img-circle" style="height:100px;">';
                $image_html .= '<button type="button" class="btn btn-outline-info btn-sm ml-3 change_image_btn">Change Image</button>';
                $image_html .= '<button type="button" class="btn btn-outline-warning btn-sm ml-3 download_image_btn" data-original-name="' . $originalName . '">Download Image</button>';

                $response['image_name'] = $imageName;
                $response['image_url'] = $image_url;
                $response['image_html'] = $image_html;
                $response['status'] = 'success';
            }
        } catch (\Exception $e) {
            report($e);
            return response()->json(['status' => 'failed', 'error' => ['message' => $e->getMessage()], 'e' => $e]);
        }

        return response()->json($response);
    }

    public function deleteBlogContentImageRow(Request $request){
        $response = [];

        $response['status'] = '';

        try {
            $validator = Validator::make($request->all(), [
                'content_uuid' => 'required',
                'image_uuid' => 'required',
            ]);

            $validator_errors = implode('<br>', $validator->errors()->all());
    
            if ($validator->fails()) {
                return response()->json(['status' => 'failed', 'error' => ['message' => $validator_errors]]);
            }

            $content_uuid = $request->content_uuid ?? null;
            $image_uuid = $request->image_uuid ?? null;

            //++++++++++++++ DELETE IMAGE INFO FROM DATABASE :: Start ++++++++++++++//
            $blog_content = BlogContents::where('uuid', $content_uuid)->first();
            $blog_images = $blog_content->images ?? [];

            $existing_image = $blog_images[$image_uuid]['name'] ?? null;

            if( !is_null($existing_image) ){
                $imageDir = 'images/blogs/main/';
                $destinationPath = public_path($imageDir);

                if(File::exists($destinationPath . $existing_image)){
                    File::delete($destinationPath . $existing_image);
                }
            }

            unset( $blog_images[$image_uuid] );

            $blog_content->images = $blog_images;
            $blog_content->save();
            //++++++++++++++ UPDATE IMAGE INFO FROM DATABASE :: End ++++++++++++++//

            $response['status'] = 'success';
        } catch (\Exception $e) {
            report($e);
            return response()->json(['status' => 'failed', 'error' => ['message' => $e->getMessage()], 'e' => $e]);
        }

        return response()->json($response);
    }

    public function downloadBlogContentImage(Request $request){
        $response = [];

        $response['status'] = '';

        try {
            $validator = Validator::make($request->all(), [
                'content_uuid' => 'required',
                'image_uuid' => 'required',
            ]);

            $validator_errors = implode('<br>', $validator->errors()->all());
    
            if ($validator->fails()) {
                return response()->json(['status' => 'failed', 'error' => ['message' => $validator_errors]]);
            }

            $content_uuid = $request->content_uuid ?? null;
            $image_uuid = $request->image_uuid ?? null;

            //++++++++++++++ DOWNLOAD IMAGE :: Start ++++++++++++++//
            $blog_content = BlogContents::where('uuid', $content_uuid)->first();
            $blog_images = $blog_content->images ?? [];

            $image_name = $blog_images[$image_uuid]['name'] ?? null;

            if( is_null($image_name) ){
                return response()->json(['status' => 'failed', 'error' => ['message' => 'No image found!']]);
            }

            $imageDir = 'images/blogs/main/';
            $destinationPath = public_path($imageDir);
            $imagePath = $destinationPath . $image_name;
            
            //$imageMimeType = $imagePath->getClientMimeType();
            $imageMimeType = File::mimeType($imagePath);
            $headers = ['Content-Type: ' . $imageMimeType];
  
            return response()->download($imagePath, $image_name, $headers);
            //++++++++++++++ DOWNLOAD IMAGE :: End ++++++++++++++//
        } catch (\Exception $e) {
            report($e);
            return response()->json(['status' => 'failed', 'error' => ['message' => $e->getMessage()], 'e' => $e]);
        }
    }

    public function saveBlogContentImageFields(Request $request){
        $response = [];

        $response['status'] = '';

        try {
            $validator = Validator::make($request->all(), [
                'content_uuid' => 'required',
                'image_uuid' => 'required',
            ]);

            $validator_errors = implode('<br>', $validator->errors()->all());
    
            if ($validator->fails()) {
                return response()->json(['status' => 'failed', 'error' => ['message' => $validator_errors]]);
            }

            $content_uuid = $request->content_uuid ?? null;
            $image_uuid = $request->image_uuid ?? null;
            $image_url = $request->image_url ?? null;
            $image_alt_tag = $request->image_alt_tag ?? null;
            $image_copyright = $request->image_copyright ?? null;

            $blog_content = BlogContents::where('uuid', $content_uuid)->first();
            $blog_images = $blog_content->images ?? [];

            $img_array = [
                            'affiliate_url' => $image_url,
                            'alt_tag' => $image_alt_tag,
                            'copyright' => $image_copyright
                        ];
            
            $blog_images[$image_uuid] = array_merge($blog_images[$image_uuid], $img_array);

            $image_name = $blog_images[$image_uuid]['name'] ?? null;

            if( is_null($image_name) ){
                return response()->json(['status' => 'failed', 'error' => ['message' => 'Upload the image first!']]);
            }

            $blog_content->images = $blog_images;

            $blog_content->save();

            $response['status'] = 'success';
            $response['image_url'] = $blog_images[$image_uuid]['affiliate_url'] ?? '';
            $response['image_alt_tag'] = $blog_images[$image_uuid]['alt_tag'] ?? '';
            $response['image_copyright'] = $blog_images[$image_uuid]['copyright'] ?? '';
        } catch (\Exception $e) {
            report($e);
            return response()->json(['status' => 'failed', 'error' => ['message' => $e->getMessage()], 'e' => $e]);
        }

        return response()->json($response);
    }

    public function saveBlogContentFields(Request $request){
        $response = [];

        $response['status'] = '';

        try {
            $validator = Validator::make($request->all(), [
                'content_uuid' => 'required',
            ]);

            $validator_errors = implode('<br>', $validator->errors()->all());
    
            if ($validator->fails()) {
                return response()->json(['status' => 'failed', 'error' => ['message' => $validator_errors]]);
            }

            $content_uuid = $request->content_uuid ?? null;

            $blog_content = BlogContents::where('uuid', $content_uuid)->first();

            $content_title_field = $request->content_title_field ?? $blog_content->content_title;
            $content_text_field = $request->content_text_field ??  $blog_content->content;

            $blog_content->content_title = $content_title_field;
            $blog_content->content = $content_text_field;

            $blog_content->save();

            $response['status'] = 'success';
        } catch (\Exception $e) {
            report($e);
            return response()->json(['status' => 'failed', 'error' => ['message' => $e->getMessage()], 'e' => $e]);
        }

        return response()->json($response);
    }

    public function deleteBlogContentRow(Request $request){
        $response = [];

        $response['status'] = '';

        try {
            $validator = Validator::make($request->all(), [
                'content_uuid' => 'required',
            ]);

            $validator_errors = implode('<br>', $validator->errors()->all());
    
            if ($validator->fails()) {
                return response()->json(['status' => 'failed', 'error' => ['message' => $validator_errors]]);
            }

            $content_uuid = $request->content_uuid ?? null;

            //++++++++++++++ DELETE CONTENT INFO FROM DATABASE :: Start ++++++++++++++//
            $blog_content = BlogContents::where('uuid', $content_uuid)->first();
            $blog_images = $blog_content->images ?? null;

            if( !is_null($blog_images) ){
                $imageDir = 'images/blogs/main/';

                foreach($blog_images as $img){
                    if(isset($img['name'])){
                        $image_name = $img['name'];

                        $destinationPath = public_path($imageDir);

                        if(File::exists($destinationPath . $image_name)){
                            File::delete($destinationPath . $image_name);
                        }
                    }
                }
            }

            $blog_content->delete();
            //++++++++++++++ DELETE CONTENT INFO FROM DATABASE :: End ++++++++++++++//

            $response['status'] = 'success';
        } catch (\Exception $e) {
            report($e);
            return response()->json(['status' => 'failed', 'error' => ['message' => $e->getMessage()], 'e' => $e]);
        }

        return response()->json($response);
    }

    public function downloadBannerImage(Request $request){
        $response = [];

        $response['status'] = '';

        try {
            $validator = Validator::make($request->all(), [
                'blog_uuid' => 'required',
                'image_uuid' => 'required',
            ]);

            $validator_errors = implode('<br>', $validator->errors()->all());
    
            if ($validator->fails()) {
                return response()->json(['status' => 'failed', 'error' => ['message' => $validator_errors]]);
            }

            $blog_uuid = $request->blog_uuid ?? null;
            $image_uuid = $request->image_uuid ?? null;

            //++++++++++++++ DOWNLOAD IMAGE :: Start ++++++++++++++//
            $blog_image = Medias::where('media_type', 'blog_banner')
                                    ->where('uuid', $image_uuid)
                                    ->where('source_uuid', $blog_uuid)
                                    ->first();
            
            $image_name = $blog_image->name ?? null;

            if( is_null($image_name) ){
                return response()->json(['status' => 'failed', 'error' => ['message' => 'No image found!']]);
            }

            $imageDir = 'images/blogs/main/';
            $destinationPath = public_path($imageDir);
            $imagePath = $destinationPath . $image_name;
            
            $imageMimeType = File::mimeType($imagePath);
            $headers = ['Content-Type: ' . $imageMimeType];
  
            return response()->download($imagePath, $image_name, $headers);
            //++++++++++++++ DOWNLOAD IMAGE :: End ++++++++++++++//
        } catch (\Exception $e) {
            report($e);
            return response()->json(['status' => 'failed', 'error' => ['message' => $e->getMessage()], 'e' => $e]);
        }
    }

}
