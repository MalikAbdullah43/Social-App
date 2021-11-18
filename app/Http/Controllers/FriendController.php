<?php

namespace App\Http\Controllers;
use App\Models\Friend;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function addFriends(Request $req) {
    
        $friend = Friend::query('friends')->where('friend_id',$req->friend_id)->first();
    
        if(empty($friend))
        {
            $user = User::query('users')->where('id',$req->friend_id)->get();
            $count = Count($user);
          if(!empty($count))
        {
              
        $secret_key="Malik$43";
        $token=request()->bearerToken();
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        
        ///
        if($decoded->data->id != $req->friend_id){
        
        $friend = new Friend;
        $friend->user_id = $decoded->data->id;
       
        $friend->friend_id = $req->friend_id;
        
        $friend->save();
        return response(["Message"=> "Successfully Add","Status"=>"200"],200); 
        }
        else
        return response(["Message"=> "User Cannot Add Yourself","Status"=>"200"],200); 
        }

          else{
            return response(["Message"=>"This User Not Found","Status"=>"404"],404);
          }
        }

       return response(["Message"=>"This User Already Friend","Status"=>"403"],403);
    
       
    }
//List Friends
    public function showFriends(){

        $secret_key="Malik$43";
        $token=request()->bearerToken();
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        $user=User::with('friends')->where('id',$decoded->data->id)->get();
        return response($user);
    }

    public function remove(Request $req) {
       $secret_key="Malik$43";
       $token=request()->bearerToken();
       $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        $friend = Friend::all()->where('user_id', $decoded->data->id)->where('friend_id', $req->friend_id)->first();
        $friend->delete();

        return [
            'friend_id' => $req->friend_id
        ];
    }
}
