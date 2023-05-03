<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Blogs;
use App\Models\Comments;

use Validator;

class PostController extends Controller
{
    public function postDetails($slug){
        $post = Blogs::with(['banner', 'categories', 'contents'])
                    ->where('slug', $slug)
                    ->where('page_type', 'blog_page')
                    ->where('status', '1')
                    ->first();

        if( !isset($post->id) ){
            abort(404);
        }

        $category_ids = [];

        if( $post->categories->count() > 0 ){
            foreach($post->categories as $category){
                $category_ids[] = $category->id;
            }
        }

        $relatedPosts = Blogs::with('banner')
                            ->whereHas('categories', function($q) use($category_ids) {
                                $q->whereIn('id', $category_ids);
                            })
                            ->where('slug', '!=', $slug)
                            ->where('page_type', 'blog_page')
                            ->where('status', '1')
                            ->inRandomOrder()
                            ->limit(2)
                            ->get();
        
        $page_title = !is_null($post->page_title) ? $post->page_title : $post->name;

        $shareComponent = \Share::page(
                            url()->full(),
                            $page_title,
                        )
                        ->facebook()
                        ->twitter()
                        ->linkedin();
                        ///->telegram()
                        //->whatsapp();
                        //->reddit();

        return view('front.post.details', compact('post', 'relatedPosts', 'shareComponent'));
    }

    public function commentSubmit(Request $request, $blog_uuid){
        $response = [];

        $response['status'] = '';
        
        try {
            $validator = Validator::make($request->all(), [
                'comment' => 'required',
                'name' => 'required',
                'email' => 'required|email',
                'website' => 'nullable|url'
            ]);

            if( $validator->fails() ){
                $validator_errors = implode('<br>', $validator->errors()->all());
                return response()->json(['status' => 'failed', 'error' => ['message' => $validator_errors]]);
            }

            $comment_array = [
                'blog_uuid' => $blog_uuid,
                'name' => $request->name,
                'email' => $request->email,
                'website' => $request->website ?? null,
                'content' => $request->comment
            ];

            $comment_uuid = ( isset($request->comment_uuid) && !empty($request->comment_uuid) ) ? $request->comment_uuid : null;

            if( !is_null($comment_uuid) ){
                $parent_comment = Comments::where('uuid', $comment_uuid)->first();
                $comment_array['parent_id'] = $parent_comment->id ?? null;
            }

            $comment = Comments::create($comment_array);

            return response()->json([
                'status' => 'success',
                'message' => 'Comment posted successfully!',
                'comment_uuid' => $comment->uuid ?? null
            ], 200);
        } catch (\Exception $e) {
            report($e);
            return response()->json(['status' => 'failed', 'error' => ['message' => $e->getMessage()], 'e' => $e]);
        }
    }

    public function replyForm(Request $request){
        $comment_uuid = $request->comment_uuid ?? null;

        if( is_null($comment_uuid) ){
            return response()->json(['status' => 'failed', 'message' => 'Invalid request!'], 400);
        }

        $html = view('front.post.comment-form', compact('comment_uuid'))->render();

        return response()->json([
            'status' => 'success',
            'message' => 'Comment posted successfully!',
            'html' => $html
        ], 200);
    }

    public function regenerateCommentsList($post_uuid){
        $html = view('front.post.comments-list', compact('post_uuid'))->render();

        return response()->json([
            'status' => 'success',
            'message' => 'Comments regenerated successfully!',
            'html' => $html
        ], 200);
    }

    public function searchPosts(Request $request){
        $keyword = $request->get('keyword', '');

        $posts = Blogs::with('banner')
                        ->where('title', 'like', '%' . $keyword . '%')
                        ->where('page_type', 'blog_page')
                        ->where('status', '1')
                        ->orderBy('updated_at', 'DESC')
                        ->paginate(18);

        return view('front.post.search-result', compact('posts', 'keyword'));
    }
}
