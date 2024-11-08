<?php

use App\Http\Controllers\Admin\AdminAuth;
use App\Http\Controllers\Admin\AudioController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Frontend\Contact;
use App\Http\Controllers\Frontend\GetPostController;
use App\Http\Controllers\Frontend\SubscribeController;
use App\Http\Controllers\Admin\SubscribeController as AdminSubscribeController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Frontend\CommentController as FrontendCommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::controller(AuthController::class)->group(function(){
  Route::post('/login','login');
  Route::post('/register','register');
  
});
// -> middleware(['auth:sanctum']) | down
Route::controller(AuthController::class)->group(function(){
  Route::post('/logout','logout');
});
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('/admin')->group(function (){
        Route::get('/admins', [AdminAuth::class, 'admins']);
        // Route::post('/logout', [AdminAuth::class, 'logout']);
    
          // category
      
          Route::get('/categorys', [CategoryController::class,'index']);
          Route::post('/categorys', [CategoryController::class,'store']);
          Route::put('/categorys/{id}', [CategoryController::class,'edit']);
          Route::post('/categorys/{id}', [CategoryController::class,'update']);
          Route::delete('/categorys/{id}', [CategoryController::class,'delete']);
          Route::get('/categorys/{search}', [CategoryController::class,'search']);
          Route::get('/hola', [CategoryController::class,'some']);

          // post
          Route::get('/posts', [PostController::class,'index']);
          Route::post('/posts', [PostController::class,'store']);    
          Route::put('/posts/{id}', [PostController::class,'edit']);   
          Route::post('/posts/{id}', [PostController::class,'update']);
          Route::delete('/posts/{id}', [PostController::class,'delete']);   
          Route::get('/posts/{search}', [PostController::class,'search']);   
          Route::get('/someposts', [PostController::class,'someposts']);



          // setting routes
          Route::get('/setting', [SettingController::class, 'index']);
          Route::post('/setting/{id}', [SettingController::class, 'update']);
      
      
          // contact routes
          Route::get('/contacts', [ContactController::class, 'getContects']);
          Route::delete('/contacts/{id}', [ContactController::class, 'delete']);
      
          // subscribe routes
          Route::get('/subscribe', [AdminSubscribeController::class, 'index']);
          Route::delete('/subscribe/{id}', [AdminSubscribeController::class, 'delete']);
      
          // comments
          Route::get('/comments', [CommentController::class, 'index']);
          Route::delete('/comments/{id}', [CommentController::class, 'delete']);       

          //Events

        Route::get('/events' , [EventController::class,'index']);
        Route::put('/events/{id}' , [EventController::class,'edit']);
        Route::post('/events/{id}/edit' , [EventController::class,'update']);
        Route::delete('/events/{id}/delete' , [EventController::class,'destroy']);
        Route::post('/events' , [EventController::class,'store']);
        Route::get('/events/{id}' , [EventController::class,'show']);
        Route::get('/evento/recent', [EventController::class,'recent']);
        Route::get('/someevents', [EventController::class,'someevents']);


      // Demo sound 

        Route::get('/audio' , [AudioController::class,'index']);
        Route::post('/audio' , [AudioController::class,'store']);
        Route::get('/download/{file}', [AudioController::class,'download']);
        Route::get('/view/{id}', [AudioController::class,'view']);
        Route::delete('audio/{id}' , [AudioController::class,'destroy']);



        // Project

        Route::get('/projects' , [ProjectController::class,'index']);
        Route::put('/projects/{id}' , [ProjectController::class,'edit']);
        Route::post('/projects/{id}' , [ProjectController::class,'update']);

        Route::delete('/projects/{id}' , [ProjectController::class,'destroy']);
        Route::post('/projects' , [ProjectController::class,'store']);
        Route::get('/projects/{id}' , [ProjectController::class,'show']);

      // count length
      Route::get('/numcmnt',[CommentController::class,'numcmnt']);
      Route::get('/numcat',[CategoryController::class,'numcat']);
      Route::get('/numevent',[EventController::class,'numevent']);
      Route::get('/numpost',[PostController::class,'numpost']);
      Route::get('/numproj',[ProjectController::class,'numproj']);



      
    
      });
      
// });

// Route::post('/login', [AdminAuth::class, 'login']);


Route::prefix('/front')->group(function () {
    
    Route::get('/all-posts', [GetPostController::class, 'index']);
    Route::get('/viewd-posts', [GetPostController::class, 'viewPosts']);
    Route::get('/single-posts/{id}', [GetPostController::class, 'getPostById']);
    Route::get('/category-posts/{id}', [GetPostController::class, 'getPostByCategory']);
    Route::get('/search-posts/{search}', [GetPostController::class, 'searchPost']);
    Route::post('/contact', [Contact::class, 'store']);
    Route::post('/subscribe', [SubscribeController::class, 'store']);
    Route::get('/comments', [FrontendCommentController::class, 'getComments']);
    Route::post('/comments/{id}', [FrontendCommentController::class, 'store']);
    Route::get('/comments/{id}',[FrontendCommentController::class, 'getC']);
    Route::get('/getcountcomment/{id}', [FrontendCommentController::class, 'getcountcomment']);

});