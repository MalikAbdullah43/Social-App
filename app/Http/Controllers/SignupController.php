<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\mail;
use App\Mail\TestMail;
use App\Mail\LoginMail;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SignupRequest;

class SignupController extends Controller
{
    function signUp(SignupRequest $req)
    {
      
        
      $req->validated();

     //Here We Create Instance of User Model For Passing Values in Model
        $results = $req->file('image')->store('apidoc');
        $email = $req->email;
        $users = new User([
           'name'     => $req->name,
           'email'    => $req->email,
           'password' => Hash::make($req->password),
           'gender'   => $req->gender,
           'image'    =>  $results,
           
        ]);

        //Here We save Data in Database if Not Any Error Ocuure
        if($users->save())
        {

        //Send Mail New Registered 
         if(self::mail($email)){
         return response()->json(["Message"=>"save","Status"=>"200"],200);}
         else
         {
            return response()->json(["Message"=>"Not save","Status"=>"404"],404);
         }
        }

    
    }
public function mail($email)
{
    $details = ['title'=>'Hello Malik',
                'link' =>'http://127.0.0.1:8000/api/verification'.'/'.$email,
                'link1' => 'http://127.0.0.1:8000/api/regenrate'.'/'.$email];

                Mail::to('malikabdullah4300@gmail.com')->send(new TestMail($details));
                return "Email Send";
            
}

}
