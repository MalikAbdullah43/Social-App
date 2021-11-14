<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreateCommentRequest;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

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
        //If User Want To Post Comment Own Post Then They Directly Post With No Restrictions
        $id  = $decoded->data->id;
        $ownPost = DB::table('posts')->where(['user_id'=>$id,'id'=>$req->postId])->whereNull('deleted_at')->get();
        $count = Count($ownPost);
        if($count>0)
        {
            $comment =   DB::table('comments')->insert([
                'comment' => $req->comment,
                'post_id' =>$req->postId,
                'user_id' => $id ,
            ]);
            if($comment)
        
                return response(["Message"=>"Your Own Post Comment Successfully","Status"=>"200"],200);
            else
            return response(["Message"=>"Error Occure","Status"=>"500"],500);
        }
        ///User Own Post End
        //Check Other Conditions
        $access = DB::table('posts')->where(['id'=>$req->postId,'access'=>1])->whereNull('deleted_at')->get();
        $count = Count($access);
       
        if($count>0)    //If Post Is Public And Not Deleted Then Just Friends Comments on Post
        {
            $user   = $decoded->data->id;
            $friends = $access[0]->user_id;
            $postid = $req->postId;

             
            $friend = DB::table("friends")->where([
                ['user_id', $user],
                ['friend_id', $friends],
            ])->get();
          
            $count1 = Count($friend);
            if( $count1>0)    //If Log in User is Friend of Which user own This Post Then They Allow For Comment
            {  
            if(empty($req->file)){
                $comment =   DB::table('comments')->insert([
                    'comment' => $req->comment,
                    'post_id' =>$postid,
                    'user_id' => $user,
                ]);
                if($comment)
            
                return response(["Message"=>"Your Comment Post Successfully","Status"=>"200"],200);
                 else
                 return response(["Message"=>"Error Occure","Status"=>"500"],500);
            }
            else
            {
                $results = $req->file('file')->store('commentfiles');
                $comment =   DB::table('comments')->insert([
                    'comment' =>$req->comment,
                    'post_id' =>$postid,
                    'user_id' =>$user,
                    'file'    =>$results,
                ]);
                if($comment)
            
                return response(["Message"=>"Your Comment Post Successfully","Status"=>"200"],200);
                 else
                 return response(["Message"=>"Error Occure","Status"=>"500"],500);
            }
           
            }
            else{  //If user Not a Friend Then Generate a Error
                   return response(["Message"=>"This Post Not Exist","Status"=>"404"],404);
            }
            


        }
        else{
            return response(["Message"=>"This Post Not Exist","Status"=>"404"],404);
     }
         
    

  }
   public function commentDelete(Request $req)
   {
      $token=request()->bearerToken();
       $secret_key="Malik$43";
       $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
       $user = $decoded->data->id;
    
       $cid=$req->cid;
       $pid=$req->pid;
       $select = DB::table('comments')->where(["id"=>$cid,'user_id'=> $user,'post_id'=> $pid])->whereNull('deleted_at')->get();
    
       $count = Count($select);
     
        if($count>0)
        {
        $delete =  Comment::find(17)->delete();

         if($delete)
          return response(["Message"=>"Successfully Post Delete","Status"=>"200"],200);
          else
          return response(["Message"=>"Error Occure in Comment Delete","Status"=>"500"],500);
        }
        else
        return response(["Message"=>"Comment Not Exist","Status"=>"404"],404);
         

   } 
        





}
