<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Exception;

use App\Models\User;


class AuthController extends Controller
{
    function register(Request $request){
        try{
        $data = $request->all();
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        $session = Session::create([
            'user_id' => $user->id,
            'revoked' => false
        ]);


        $payload = [
            'session_id' => $session->id,
            'user_id' => $user->id,
            'exp' => time() + 3600
        ];

        $token = generateToken($payload);
        

        return respose()->json([
            'status' => true,
            'message' => "user registered successfully",
            'refresh_token'=> $token['refresh_token'],
            'access_token'=> $token['access_token']
        ]);
        }
        catch(Exception $e){
            return response()->json([
            'status' => false,
            'message' => $e->getMessage()
            ], 500); 
        }
    }


    function login(Request $request){
    try{
       $data = $request->all();
       
       $user = User::where(['email'=>$request['email']]);

       if($user!=null){
        return reponse()->json(["status"=>false,'message'=>"user with this email for found"]);
       }
       
       if($user->passord != hash::make($user->password) ){
          return reponse()->json(["status"=>false,'message'=>"unauthorized user"]);
       }


       $session = Session::create([
            'user_id' => $user->id,
            'revoked' => false
        ]);

       $payload = [
            'session_id' => $session->id,
            'user_id' => $user->id,
            'exp' => time() + 3600
        ];

       $token = generateToken($payload);

       $refresh_token = $token['refresh_token'];
       $access_token = $token['access_token'];
  
       
        return response()->json([
        'status' => true,
        'message' => "Login successfully",
        'refresh_token' => $refresh_token,
        'access_token' => $access_token
       ],200);
    }
    catch(Exception $e){
       return response()->json([
        'status' => false,
        'message' => $e->getMessage()
       ],200);
    }
    }


   function generateToken($payload){
    try{
    $secret = env('JWT_SECRET');

    $access_token = JWT::encode($payload, $secret, 'HS256');
    $refresh_token = Str::random(64);

    return [
        'access_token' => $access_token,
        'refresh_token' => $refresh_token
    ];
    }
    catch(Exception $e){
        throw $e;
    }
    }
}
