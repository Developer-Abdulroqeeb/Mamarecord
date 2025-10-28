<?php

namespace App\Http\Controllers;
use App\Mail\welcomeMail;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;


class AuthController extends Controller
{
//    sign up to log in
public function register(Request $request){
  $validator = Validator::make($request->all(),[
    'name' => 'required|string|max:255',
    'email' => 'required|string|max:255|unique:users',
    'password' => 'required|string|min:4|max:8',
    'StoreName' => 'required|string|max:255|unique:users',
    'Location' => 'required|string|max:255',
    'GoodsType' => 'nullable|string|max:255'

  ]);

  if($validator->fails()){
     return response()->json([
        "success" => false,
        "error" => $validator->errors()->first()
     ], 422);
  }

 $user = User::create([
    'name' => $request->name,
    'StoreName' =>$request->StoreName,
    'Location' => $request->Location,
    'GoodsType' => $request->GoodsType,
    'email' => $request->email,
    'password' => Hash::make($request->password)
 ]);
 $token = $user->createToken('auth_token')->plainTextToken;
 $mail = Mail::to($request->email)->send(new welcomeMail($user));

//  if($mail){
    return response()->json([
        "message" => "User Created Successfully",
         'data' => $user,
      "token" => $token
       ], 201);
//  }

                           
}

// login to the dashboard
public function login(Request $request){


$check = Validator::make($request->all(), [
    'email' => 'required|string|max:255|',
    'password' => 'required|string|min:4|max:8'
]);
if($check->fails()){
    return response()->json([
"status" => false,
"error" => $check->errors()->first()
    , 422]);
}
  $user = User::where('email', $request->email)->first();  
  $token = $user->createToken('auth_token')->plainTextToken;
  if(!$user || !Hash::check( $request->password,$user->password)){
  return response()->json([
     "status" => false,
     "message" => "Inavalid Credentials"
  ], 401);
  }
    return response()->json([
  "data" => $user,
  "status" => true,
  "token" => $token
    ]);
    

}

}
