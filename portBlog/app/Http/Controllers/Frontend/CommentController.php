<?php

namespace App\Http\Controllers\Frontend;

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    // user stores comment of his with email and comment 
    public function store(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'comment' => ['required']
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => true,
                'message' => $validation->errors()->all(),
            ]);
        } else {
            $result = Comment::create([
                'post_id' => $id,
                'name' => $request->name,
                'email' => $request->email,
                'comment' => $request->comment,
            ]);
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Comment Successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Some Problem'
                ]);
            }
        }
    }
    //get all comments 

    public function getComments()
    {
        try {
            $comments = Comment::orderBy('id', 'desc')->get();
            return response()->json([
                'success' => true,
                'comments' => $comments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'comments' => $e->getMessage(),
            ]);
        }
    }

    // get comments for specific posts
    public function getC($id){
        
        $postcmnt =  Comment::where('post_id','like',$id)->orderBy('id', 'desc')->get();
        return response()->json(['postcmnt'=>$postcmnt]);
    }

    public function getcountcomment($id){
        $postcmntcount =  Comment::where('post_id','like',$id)->count();
        return response()->json([ 'postcmntcount'=>$postcmntcount] );
    }
    
}