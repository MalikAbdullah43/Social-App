<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\mail;
use App\Mail\TestMail;

class MailVerify extends Controller
{
   public function verify($email)
    {
        
        
        $sql= DB::table('users')
        ->where('email', $email)->where('email_verified_at' , NULL)
        ->update(['email_verified_at' => now(),'status'=>'1']);
        if($sql)
        {
            return "Successfully Verify";
        }
        else{
            return "Already Verified";
        }
    }





    public function regenrate_link($email)
    {
        $details = ['title' => 'This Social Application Verifacation',
                    'link'  => 'http://127.0.0.1:8000/api/verification'.'/'.$email,
                    'link1' => 'http://127.0.0.1:8000/api/regenrate'.'/'.$email];
    //Mail Sending Facade
                    Mail::to('malikabdullah4300@gmail.com')->send(new TestMail($details));
                    //Database Query 
                    $sql= DB::table('users')
                    ->where('email', $email)
                    ->where('email_verified_at' , NULL)
                    ->where('status' ,'0')
                    ->increment('count', 1);
                    //check if User Link is Valid regenerate link
                    if($sql)
                    return "Email Regenrate Successfully";
                    else
                    return "Link Already Verify";
                
    }




}
