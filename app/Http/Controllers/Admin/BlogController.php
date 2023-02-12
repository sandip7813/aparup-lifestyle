<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Categories;
use App\Models\Blogs;
use App\Models\Medias;
use App\Models\BlogContents;

use Illuminate\Support\Str;
use Validator;
use Image;
use Illuminate\Support\Facades\File; 
use Response;

class BlogController extends Controller
{
    public $categories;

    protected $statusArray = [
        1 => 'Avtive',
        0 => 'Inactive',
    ];

    public function __construct(){
        $this->categories = Categories::orderBy('name', 'asc')->get();
    }

    public function index(Request $request){
        $blogs_qry = Blogs::with(['category', 'banner'])->where('page_type', 'blog_page');

        if( $request->filled('blog_title') ){
            $blogs_qry->where('title', 'like', '%' . $request->blog_title . '%');
        }

        if( $request->filled('blog_category') ){
            $blogs_qry->where('category_id', $request->blog_category);
        }

        if( $request->filled('blog_status') ){
            $blogs_qry->where('status', $request->blog_status);
        }

        $blogs = $blogs_qry->orderby('id','desc')->paginate(10);

        return view('admin.blog.index')->with([
                                        'blogs' => $blogs, 
                                        'categories' => $this->categories,
                                        'statusArray' => $this->statusArray
                                    ]);
    }

    public function redirectToCreate(){
        $blog = Blogs::create(['status' => '2']);
        return redirect()->route('admin.blog.create', $blog->uuid);
    }

    public function create($uuid){
        $blog = Blogs::with('contents')->where('uuid', $uuid)->first();

        if( !isset($blog->id) ){
            abort(404);
        }

        return view('admin.blog.create')->with([
                                            'categories' => $this->categories,
                                            'blog_uuid' => $uuid,
                                            'blog' => $blog
                                        ]);
    }

    public function blogSubmit(Request $request){
        $response = [];

        $response['status'] = '';

        try {
            $validator_array = [];

            $validator_array['blog_uuid'] = 'required';
            $validator_array['blog_title'] = 'required|max:255';
            $validator_array['blog_category'] = 'required';
            $validator_array['blog_content'] = 'required';
            $validator_array['banner'] = 'required|mimes:jpeg,jpg,png,gif|max:10000';

            $validator = Validator::make($request->all(), $validator_array);

            $validator_errors = implode('<br>', $validator->errors()->all());

            if ($validator->fails()) {
                return response()->json(['status' => 'failed', 'error' => ['message' => $validator_errors]]);
            }

            //+++++++++++++++++++++++++++ STORE & CROP IMAGES :: Start +++++++++++++++++++++++++++//
            if($request->hasFile('banner')) {
                $banner = Image::make($request->file('banner'));

                $bannerName = time() . '-' . uniqid() . '.' . $request->file('banner')->getClientOriginalExtension();
                $bannerDir = 'images/blogs/';

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
            }
            //+++++++++++++++++++++++++++ STORE & CROP IMAGES :: End +++++++++++++++++++++++++++//

            $blog = Blogs::create([
                        'uuid' => $request->blog_uuid,
                        'category_id' => $request->blog_category,
                        'title' => $request->blog_title,
                        'slug' => Blogs::generateSlug($request->blog_title),
                        'content' => $request->blog_content,
                        'page_title' => $request->page_title ?? null,
                        'metadata' => $request->metadata ?? null,
                        'keywords' => $request->keywords ?? null,
                    ]);
            
            Medias::create([
                'user_id' => Auth::user()->id,
                'source_type' => 'blog_banner',
                'source_uuid' => $request->blog_uuid,
                'name' => $bannerName,
                'is_active' => 1
            ]);

            $response['status'] = 'success';
        } catch (\Exception $e) {
            report($e);
            return response()->json(['status' => 'failed', 'error' => ['message' => $e->getMessage()], 'e' => $e]);
        }

        return response()->json($response);
    }

    public function changeStatus(Request $request){
        $response = [];

        $response['status'] = '';

        try {
            $blog_uuid = $request->blog_uuid ?? '';

            if( $blog_uuid == '' ){
                return response()->json(['status' => 'failed', 'error' => ['message' => 'No blog found!']]);
            }

            $blog = Blogs::where('uuid', $blog_uuid)->where('page_type', 'blog_page')->first();

            $blog->status = ($blog->status == '1') ? '0' : '1';
            $blog->save();            

            $response['status'] = 'success';
        } catch (\Exception $e) {
            report($e);
            return response()->json(['status' => 'failed', 'error' => ['message' => $e->getMessage()], 'e' => $e]);
        }

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
                $existingBanner = $blog->banner->title ?? null;

                if( !is_null($existingBanner) ){
                    File::delete( $bannerDir . 'main/' . $existingBanner );
                    File::delete( $bannerDir . '1000x600/' . $existingBanner );
                    File::delete( $bannerDir . '200x160/' . $existingBanner );
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

                $blog->banner->title = $bannerName;
                $blog->banner->save();

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

    public function edit($uuid){
        $blog = Blogs::where('uuid', $uuid)->where('page_type', 'blog_page')->first();

        return view('admin.blog.edit', compact('blog'))->with(['categories' => $this->categories]);
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

    public function updateBlogSubmit(Request $request){
        $response = [];

        $response['status'] = '';

        try {
            $blog_uuid = $request->blog_uuid ?? '';

            if( $blog_uuid == '' ){
                return response()->json(['status' => 'failed', 'error' => ['message' => 'No blog found!']]);
            }

            $blog_title = $request->blog_title ?? '';
            $slug_editable = $request->slug_editable ?? 0;
            $blog_slug = $request->blog_slug ?? '';
            $blog_category = $request->blog_category ?? '';
            $blog_content = $request->blog_content ?? '';
            $page_title = $request->page_title ?? NULL;
            $metadata = $request->metadata ?? NULL;
            $keywords = $request->keywords ?? NULL;
            $blog_status = $request->blog_status ?? null;

            if( empty($blog_title) ){
                return response()->json(['status' => 'failed', 'error' => ['message' => 'No blog title found!']]);
            }

            if( empty($blog_slug) ){
                return response()->json(['status' => 'failed', 'error' => ['message' => 'No blog slug found!']]);
            }

            $duplicate_slug = Blogs::withTrashed()
                                    ->where('slug', $blog_slug)
                                    ->where('uuid', '<>', $blog_uuid)
                                    ->count();

            if( $duplicate_slug > 0 ){
                return response()->json(['status' => 'failed', 'error' => ['message' => 'This slug can\'t be used!']]);
            }

            if( is_null($blog_status) ){
                return response()->json(['status' => 'failed', 'error' => ['message' => 'Invalid blog status']]);
            }

            $blog = Blogs::where('uuid', $blog_uuid)->where('page_type', 'blog_page')->first();

            $blog->title = $blog_title;
            $blog->slug = $blog_slug;
            $blog->category_id = $blog_category;
            $blog->content = $blog_content;
            $blog->page_title = $page_title;
            $blog->metadata = $metadata;
            $blog->keywords = $keywords;
            $blog->status = $blog_status;
            $blog->save();            

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

            $imageMimeType = $imagePath->getClientMimeType();
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

            $blog_content = BlogContents::where('uuid', $content_uuid)->first();
            $blog_images = $blog_content->images ?? [];

            $img_array = [
                            'affiliate_url' => $image_url,
                            'alt_tag' => $image_alt_tag
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

}
