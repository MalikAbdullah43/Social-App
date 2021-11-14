<?php

namespace App\Http\Controllers;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Mail\PasswordMail;
use Illuminate\Support\Facades\mail;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Models\User;

class PasswordController extends Controller
{
    public function forgetPassword(ForgetPasswordRequest $req)
    {
     $req->validated(); 
     $otp = rand(111111,999999);
     //Token
     $user = DB::table('users')
     ->where('email',$req->email)->first();   //Data Checking From Database user Exist or Not
     
   
    if(!empty($user->id)){
        $password          = $user->password;  //Hashing Purpose
        $user_data['id']   = $user->id;
        $user_data['email']= $user->email;
     $secret_key="Malik$43";
            $iss = "localhost";
            $iat = time(); 
            $nbf = $iat+10; 
            $exp = $iat+1800; 
            $aud = "user"; 

            $payload_info= array(
                "iss" =>$iss,
                "iat" =>$iat, 
                "nbf" =>$nbf,
                "exp" =>$exp, 
                "aud" =>$aud, 
                "data" =>$user_data,
                );
                //Token JWT END Payload
                    $sql= DB::table('users')
                    ->where('email', $req->email)
                    ->whereNotNull('email_verified_at')   //Checking if user Email Verify or Not
                    ->where('status',1)
                    ->get();
                    
                    $count = Count($sql);

            if($count>0)  //If Verify Then This Code Execute
            {
                
                $Auth_key = JWT::encode($payload_info,$secret_key);     //JWT Updation And Printing Message of Log in
                $user = DB::table('users')
                ->where('email',$req->email)
                ->update(['remember_token'=> $Auth_key,"otp"=>$otp ,'updated_at' => now()->addMinutes(30) ]);
            }
    }

     //end
      //Token Validity Increase if All Activity Perfoam And Message Show
     if(self::mail($req->email,$otp)){
     return response([
         "Message"=>"Otp Send On Email","Status"=>"200","Auth_key2"=>$Auth_key],200
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

   //Reset Password
   public function passwordReset(ResetPasswordRequest $req)
   {
     $req->validated();
     $otp = $req->otp;
     $jwt = $req->bearerToken();
     $token = DB::table('users')
     ->where('remember_token',$jwt)->where('updated_at','>=',now())->where('otp',$otp)
     ->get();
    
     $count = Count($token);
    if($count>0)
    {
        
     $update= DB::table('users')->where('id',$token[0]->id)->update(['password'=>Hash::make($req->new_password),'remember_token'=>'']); 
     if($update>0){
         return response(["Message"=>"Password Change Succesfully","Status"=>"200"],200);
     }  
     else{
    return response(["Message"=>"OTP Expire","Status"=>"404"],404);
     }
    }
    else{
    return response(["Message"=>"OTP Expire or Invalid Otp","Status"=>"404"],404);
     }  

   }
  
}
