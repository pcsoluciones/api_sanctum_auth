<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BlogController;


Route::post('register', [UserController::class, 'register'] );
Route::post('login', [UserController::class, 'login']);

Route::group( ['middleware' => ["auth:sanctum"]], function(){
    //rutas
    Route::get('user-profile', [UserController::class, 'userProfile']);
    Route::get('logout', [UserController::class, 'logout']);

    //rutas para el blog
    Route::post("create-blog", [BlogController::class, "createBlog"]);
    Route::get("list-blog", [BlogController::class, "listBlog"]);
    Route::post("show-blog/{id}", [BlogController::class, "showBlog"]);
    Route::put("update-blog/{id}", [BlogController::class, "updateBlog"]);
    Route::delete("delete-blog/{id}", [BlogController::class, "deleteBlog"]);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
