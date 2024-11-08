<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class GetPostController extends Controller
{
    // all posts show using pagination
    public function index()
    {
        try  {
            $posts = Post::with('categorys')->orderBy('id', 'desc')->get();
            return response()->json([
                'success' => true,
                'posts' => $posts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // most viewed posts

    //currently not necessary
    public function viewPosts()
    {
        try {
            $posts = Post::with('categorys')->where('views', '>', 0)->orderBy('id', 'desc')->get();
            return response()->json([
                'success' => true,
                'posts' => $posts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    // find single post with it's category use for showing that single post
    public function getPostById($id)
    {
        try {
            $posts = Post::with('categorys')->findOrFail($id);
            $posts->views = $posts->views + 1;
            $posts->save();
            return response()->json([
                'success' => true,
                'posts' => $posts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
    // like filter functionality on frontend blogs page,  we show all categories and when cliked whow all posts with that category
    public function getPostByCategory($id)
    {
        try {
            $posts = Post::where('cat_id', $id)->orderBy('id', 'desc')->get();
            return response()->json([
                'success' => true,
                'posts' => $posts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
      }

      
      //not important
    public function searchPost($search)
    {
        try {
            $posts = Post::with('categorys')->where('title', 'LIKE', '%' . $search . '%')->orderBy('id', 'desc')->get();
            return response()->json([
                'success' => true,
                'posts' => $posts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }



    // public function getCat($id){
        
    //     $postcmnt =  Comment::where('post_id','like',$id)->orderBy('id', 'desc')->get();
    //     return response()->json(['postcmnt'=>$postcmnt]);
    // }

}