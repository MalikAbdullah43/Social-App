<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\Post;
use App\Models\User;
use App\Http\Requests\PostRequest;

class PostController extends Controller
{
    //This Function For Create Post
    public function postCreate(PostRequest $req)
    {
        $req->validated();   
        $token=request()->bearerToken();
        $secret_key="Malik$43";

        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        $post=new Post;
        $post->text=$req->text;
        if(!empty($post->access))
        {
            $post->access=$req->access;
        }
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

    //This Function Use For Check Which posts User Post
    public function userPosts(Request $request){
        
        $token=request()->bearerToken();
        $secret_key="Malik$43";
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        $user=User::with('Post')->where('id',$decoded->data->id)->get();
        return response($user[0]->Post);
      //  $arrays[] =  (array) $red;

        
       // else
        //return response(["Message"=>"Post Not Found","Status"=>"404",],404);

  
      }
      //This Function For User Which Post User Want to Update
    public function postUpdate(PostRequest $req)
    { 
        $req->validated(); 
        $postId = $req->pid;  
        $token=request()->bearerToken();
        $secret_key="Malik$43";
        
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        $post = Post::find($postId);

        if(!empty($post)){
        $post->text=$req->text;
        $post->user_id=$decoded->data->id;
        if(!empty($post->access))
        {
            $post->access=$req->access;
        }
        if(!empty($req->file('file'))){
            $result = $req->file('file')->store('userposts');
            $post->file=$result;
            
        }
        
        $post->update();
        
        if(!empty($post->text))
        return response(["Message"=>"Successfully Update Post","Status"=>"200",],200);
    }
    else{
    
        return response(["Message"=>"Post Not Found","Status"=>"404",],404);
    }     
    }



    //This Function For Delete Post
    public function postDelete(Request $req)
    {
         $postId = Post::find($req->pid);
         if(!empty($postId->id)){
            $id=$postId->id;
            $delete =  Post::find($id)->delete();
            if($delete)
               return response(["Message"=>"Successfully Post Delete","Status"=>"200"],200);
               else
               return response(["Message"=>"Error Occure in Post Delete","Status"=>"500"],500);
         }
     
      
      else
      return response(["Message"=>"Post Not Found","Status"=>"404"],404);
    } 

}
