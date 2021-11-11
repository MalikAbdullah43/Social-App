<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\Post;

class PostController extends Controller
{
    public function postCreate(Request $req)
    {
        
        $token=request()->bearerToken();
        $secret_key="Malik$43";

        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        $post=new Post;
        $post->text=$req->text;
        $post->access=$req->acess;
        $post->user_id=$decoded->data->id;
        if(!empty($req->file('file'))){
            $result = $req->file('file')->store('userposts');
            $post->file=$result;
        }
        
        $post->save();
        if(!empty($post))
        return response(["Message"=>"Successfully Created Posts","Status"=>"200"],200);
        else
        return response(["Message"=>"Error Occure in Post Create Posts","Status"=>"404"],404);
    }
    public function postUpdate(Request $req)
    {

    }
}
