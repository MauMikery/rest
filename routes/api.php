<?php

use App\Http\Controllers\api\BlogController;
use App\Http\Controllers\api\BlogPublicController;
use App\Http\Controllers\api\CommentController;
use App\Http\Controllers\api\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Endpoints para la autenticacion del customer 
Route::controller(CustomerController::class)->group(function (){
    Route::post('/login', 'login');
    Route::post('/register', 'register'); 
    Route::post('/logout', 'logout')->middleware('auth:sanctum'); 
});

//Endpoints para los blogs
Route::middleware('auth:sanctum')->controller(BlogController::class)->group(function (){
    Route::get('/blog', 'index');
    
    Route::get("/blog/public", 'showPublicBlog'); 

    Route::get("/blog/{id}", 'show');

    Route::get("/blog/{blog}/image", 'showImage');
    
    Route::post("/blog", 'store');
    
    Route::put("/blog/{id}", 'update');
    
    Route::delete("/blog/{id}", 'destroy');  
});

//Endpoints para los comentarios 
Route::middleware('auth:sanctum')->controller(CommentController::class)->group(function (){
    Route::get("/blog/{id}/comment", 'index'); 
    Route::post("/blog/{id}/comment", 'store'); 
}); 

//Endpoints publicos 
Route::controller(BlogPublicController::class)->group(function (){
    Route::get("/public", "index"); 
    Route::get("/public/{id}", "show"); 
}); 


