<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreateCommentRequest;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
//use App\Models\Post;
use App\Models\Comment;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function commentCreate(CreateCommentRequest $req)
    {
        $req->validated(); 
        //Finding Active User ID
        $token=request()->bearerToken();
        $secret_key="Malik$43";
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        //End
   
    
        

        $comment =  Comment::create([
           // 'comment' => $req->comment,
           // 'post_id' =>$req->postid,
            'user_id' => $decoded->data->id,

        ]);

    
    }
}
