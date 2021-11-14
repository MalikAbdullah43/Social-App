<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\mail;
use App\Models\User;
use App\Mail\PasswordMail;
use Illuminate\Support\Facades\DB;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\ForgetPasswordRequest;

header('content-type: application/json');

class UserController extends Controller
{

    //For Updation User Data
  public function edit(UpdateProfileRequest $req){
  
     $req->validated();
    $jwt = $req->bearerToken();
     $user = User::where('remember_token',$jwt)->first();
     
     $result = $req->file('image')->store('apidoc'); //Image Saving

    
     $user->name = $req->name;
     $user->password = Hash::make($req->password);
     $user->gender = $req->gender;
     $user->image = $result;
     $res  =  $user->update();
     if($res)
     { 
        $user = DB::table('users')
        ->where('remember_token', $jwt)->update(['updated_at' => now()->addMinutes(15)]);  //Token Validity Increase if All Activity Perfoam And Message Show
         return response([
            "Message"=>"Data Update Successfully","Status"=>"200"],200
         );

     }
     else{
        return response([
            "Message"=>"Data Not Update","Status"=>"500"],500   //If any Error Occure Then Error Show
         ); 
     }
 }


      public function logOut(Request $req)
      {
        $jwt = $req->bearerToken();
        $sql = DB::table('users')->where('remember_token',$jwt)->update(['remember_token'=>'']);
        if($sql)
        return response(['Message'=>'Successfully Logout','Status'=>'200'],200);
        
        else
        return response(['Message'=>'May Be SomeThing Wrong!!','Status'=>'404'],404);

         
      }

     public function forgetPassword(ForgetPasswordRequest $req)
     {
      $req->validated(); 
      $otp = rand(111111,999999);
      $user = DB::table('users')
      ->where('email', $req->email)->update(["otp"=>$otp,'updated_at' => now()->addMinutes(15)]);  //Token Validity Increase if All Activity Perfoam And Message Show
      if(self::mail($req->email,$otp)){
      return response([
          "Message"=>"Otp Send On Email","Status"=>"200"],200
       );
      }
      
     }

     public function mail($email,$otp)
{
    $details = ['title'=>'Hello Dear User',
                'Message'=>'This is  Your Otp:'.$otp,
];
               

                Mail::to('malikabdullah4300@gmail.com')->send(new PasswordMail($details));
                return "Email Send";
            
}

   
}
