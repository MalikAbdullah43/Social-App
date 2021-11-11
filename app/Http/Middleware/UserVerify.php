<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $jwt = $request->bearerToken();
        $token = DB::table('users')
        ->where('remember_token',$jwt)->where('updated_at','>=',now())
        ->get();
       
        $count = Count($token);
        if ($count==0){
            return response(["Message"=>"Unauthorized Access or Token Expire","Status"=>"404"],404);
        }
        else{
        return $next($request);}
        }
}
