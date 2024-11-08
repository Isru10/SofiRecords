<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::orderBy('id', 'desc')->with('categorys')->get();
            if ($posts) {
                return response()->json([
                    'success' => true,
                    'posts' => $posts,
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getTotalPost()
    {
        try {
            $posts = Post::count();
            if ($posts) {
                return response()->json([
                    'success' => true,
                    'posts' => $posts,
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        // try {
        //     $validation = Validator::make($request->all(), [
        //         'title' => ['required', 'string', 'max:100', 'min:10', 'unique:posts'],
        //         'description' => ['required', 'string', 'max:1000', 'min:10'],
        //         'cat_id' => ['required'],
        //         'image' => ['required'],
        //         'shortdesc'=>['required'],
        //         'conclusion'=>['required']
        //     ]);
        //     if ($validation->fails()) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => $validation->errors()->all(),
        //         ]);
        //     } else {
        //         $filename = "";
        //         if ($request->file('image')) {
        //             $filename = $request->file('image')->store('posts', 'public');
        //         } else {
        //             $filename = "null";
        //         }
        //         $result = Post::create([
        //             'title' => $request->title,
        //             'description' => $request->description,
        //             'image' => $filename,
        //             'cat_id' => $request->cat_id,
        //             'shortdesc'=>$request->shortdesc,
        //             'conclusion'=>$request->conclusion,
        //             'views' => 0
        //         ]);
        //         if ($result) {
        //             return response()->json([
        //                 'success' => true,
        //                 'message' => 'Post Add Successfully'
        //             ]);
        //         } else {
        //             return response()->json([
        //                 'success' => false,
        //                 'message' => "Some Problem"
        //             ]);
        //         }
        //     }
        // } catch (Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => $e->getMessage(),
        //     ]);
        // }


        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image' => 'nullable',
            'cat_id' => 'required|string',
            'shortdesc' => 'required|string',
            'conclusion' => 'required|string',
 ]);


        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        } else {
            $imagePath = null;
        }

        $post = Post::create([
            'views' => $request->views ?? 0, // provide default value if field is not set
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'cat_id' => $request->cat_id,
            'shortdesc'=>$request->shortdesc,
            'conclusion'=>$request->conclusion,
        ]);


        return response()->json($post, 201);
 
    }

    public function edit($id)
    {
        try {
            $posts = Post::findOrFail($id);
            return response()->json([
                'success' => true,
                'posts' => $posts
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request,$id)
    {
        //   try {
        //     // $posts = Post::findOrFail($request->id);
        //     $posts = Post::findOrFail($id);
        //     $validation = Validator::make($request->all(), [
        //         'title' => ['required', 'string', 'max:100', 'min:10'],
        //         'description' => ['required', 'string', 'max:1000', 'min:10'],
        //         'cat_id' => ['required'],
        //         'shortdesc'=>['required'],
        //         'conclusion'=>['required']
        //     ]);
        //     if ($validation->fails()) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => $validation->errors()->all(),
        //         ]);
        //     } else {
        //         $filename = "";
        //         $destination = public_path('storage\\' . $posts->image);
        //         if ($request->file('new_image')) {
        //             if (File::exists($destination)) {
        //                 File::delete($destination);
        //             }
        //             $filename = $request->file('new_image')->store('posts', 'public');
        //         } else {
        //             $filename = $request->old_image;
        //         }
        //         $posts->title = $request->title;
        //         $posts->description = $request->description;
        //         $posts->cat_id = $request->cat_id;
        //         $posts->image = $filename;
        //         $posts->shortdesc=$request->shortdesc;
        //         $posts->conclusion=$request->conclusion;

        //         $result = $posts->save();
        //         if ($result) {
        //             return response()->json([
        //                 'success' => true,
        //                 'message' => "Post Update Successfully",
        //             ]);
        //         } else {
        //             return response()->json([
        //                 'success' => false,
        //                 'message' => "Some Problem",
        //             ]);
        //         }
        //     }
        // } catch (Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => $e->getMessage(),
        //     ]);
        // }


        try {
            // $posts = Post::findOrFail($request->id);
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'cat_id' => 'required|string',
                'shortdesc' => 'required|string',
                'conclusion' => 'required|string',
     ]);
     $post = Post::find($id);

                if ($request->hasfile('image')) {
                    if ($post->image) {
                        Storage::disk('public')->delete($post->image);
                    }
        
                    $imagePath = $request->file('image')->store('posts', 'public');
                } else {
                    $imagePath = $post->image;
                }

                $post->update([
                    
                    'title' => $request->title,
                    'description' => $request->description,
                    'image' => $imagePath,
                    'cat_id' => $request->cat_id,
                    'shortdesc'=>$request->shortdesc,
                    'conclusion'=>$request->conclusion,
                ]);
                    return response()->json([
                        'success' => true,
                        'message' => "Post Update Successfully",
                        'updated post'=>$post
                    ]);
                
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function delete($id)
    {
        $posts = Post::findOrFail($id);
        $destination = public_path('storage\\' . $posts->image);

        if (File::exists($destination)) {
            File::delete($destination);
        }

        $result = $posts->delete();
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => "Post Delete Successfully",
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Some Problem",
            ]);
        }
    }

    public function search($search)
    {
        try {
            $posts = Post::where('title', 'LIKE', '%' . $search . '%')->orderBy('id', 'desc')->with('categorys')->get();
            if ($posts) {
                return response()->json([
                    'success' => true,
                    'posts' => $posts,
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function numpost(){
        $post=Post::count();
        return response()->json([
            'success' => true,
            'post' =>$post,
        ]);
}


    public function someposts(){
        $posts = Post::latest()->take(3)->get();
        return response()->json([
            'success' => true,
            'posts' =>$posts,
        ]);
    }
}
