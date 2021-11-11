<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\Post;

class PostController extends Controller
{
    public function post_create(Request $req)
    {
        
        $token=request()->bearerToken();
        $secret_key="Malik$43";

        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        $post=new Post;
        $post->text=$req->text;
        $post->access=$req->acess;
        $post->user_id=$decoded->data->id;
        $post->save();
        return "ok";
    }
}
