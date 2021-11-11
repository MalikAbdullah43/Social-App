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



Route::get('verification/{email}',[MailVerify::class,"verify"]);                        //Link Verification Route
Route::get('regenrate/{email}',[MailVerify::class,"regenrate_link"]);                   //Verirfy Link Re Create Route
Route::post('login',[LoginController::class,"logIn"]);                                 //Login Route

Route::post('signup',[SignupController::class,"signUp"]);          //Sign Up Route


Route::middleware(['verify'])->group(function(){
    Route::post('logout',[UserController::class,"logOut"]);    //Log Out Route
    Route::post('postcreate',[PostController::class,"postCreate"]);  //Post Create
    Route::get('UserPosts',[UserController::class,"userPosts"]); //Post Create
    Route::post('uedit',[UserController::class,"edit"]);
    Route::post('postupdate',[PostController::class,"postUpdate"]);  //Post Update
});




