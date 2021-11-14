<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MailVerify;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\UserVerify;
use App\Http\Middleware\Validation;
use App\Http\Middleware\UpdateValidation;
use App\Http\Controllers\FriendController;


//User Routes
Route::get('verification/{email}',[MailVerify::class,"verify"]);                        //Link Verification Route
Route::get('regenrate/{email}',[MailVerify::class,"regenrate_link"]);                   //Verirfy Link Re Create Route
Route::post('login',[LoginController::class,"logIn"]);                                 //Login Route
Route::post('signup',[SignupController::class,"signUp"]);          //Sign Up Route


Route::middleware(['verify'])->group(function(){
    //User Routes
    Route::post('logout',[UserController::class,"logOut"]);    //Log Out Route
    Route::post('uedit',[UserController::class,"edit"]);
    //Post Routes
    Route::post('postcreate',[PostController::class,"postCreate"]);  //Post Create
    Route::get('UserPosts',[PostController::class,"userPosts"]); //Post Create
    Route::post('postupdate/{pid}',[PostController::class,"postUpdate"]);  //Post Update
    Route::get('postdelete/{pid}',[PostController::class,"postDelete"]);  //Post Delete
    Route::post('search',[PostController::class,"postSearch"]);  //Post Search
    //Comment Routes
    Route::post('commentcreate/{postid}',[PostController::class,"commentCreate"]);  //Comment Create
    //Friend Routes
    Route::post('/friend', [FriendController::class,"addFriends"]);
    Route::get('/friendlist', [FriendController::class,"showFriends"]);
    Route::post('/friend/remove', [FriendController::class,"remove"]);
    Route::post('/request', 'FriendController@request');
});




