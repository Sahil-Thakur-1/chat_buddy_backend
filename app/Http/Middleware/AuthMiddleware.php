<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        $token = $request->header('Authorization');

        if(!$token){
            return respose()->json(["status"=>false, "message" => "token is required"],401);
        }

        $token = str_replace("Bearer ","",$token);

        $session = Session::where('access_token',$token)->first();

        if(!$session){
           reposnse()->json(["status"=>false,"message"=>"invalid access token"],401);
        }

        $user = User::find($session->user_id);

        if(!$user){
          reposnse()->json(["status"=>false,"message"=>"User not found"],400);
        }

        request->merge(['auth_user' => $user]);
        
        return $next($request);
    }
}
