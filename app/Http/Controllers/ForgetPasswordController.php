<?php

namespace App\Http\Controllers;

use App\Mail\resendMail;
use Illuminate\Routing\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use PharIo\Manifest\Email;
use App\Mail\verifyMail;
use App\Models\resetpassword;
use Carbon\Carbon;

use Illuminate\Support\Facades\Session;

class ForgetPasswordController extends Controller


{
    // verify mail
    public function verifyMail(Request $request){
   $validator = Validator::make($request->all(),[
    'email' => "string|required|max:255"
   ]);
 
    if($validator->fails()){
        return response()->json([
      "status" => false,
      "error" => $validator->errors()->first()
        ], 422);
    }
    $otp = rand(10000,999999);
    $user = User::where('email', $request->email)->first();
    if(!$user){
      
        return response()->json([
   "status" => false,
   "message" => "Email did not exist"
        ], 201);
    }
    Session::put('userId',$user->id);
    Session::put('email', $user->email);
    resetpassword::where('userId', $user->id)->delete();
    resetpassword::create([
        'email' => $request->email,
        'otp' => $otp,
        'userId' => $user->id,
        'expires_at' => Carbon::now()->addMinutes(10),
        // 'updated_at' => now(),
        // 'created_at' => now(),
           ]);
      

    $mail = Mail::to($user->email)->send(new verifyMail($otp));
    return response()->json([
  "status" => true,
  "message" => "Email sent"
    ], 200);

 
   
}
//    verify otp  
public function verifyOtp(Request $request)
{
    $validator = Validator::make($request->all(), [
       'userId' => "required|numeric",
        'otp' => 'required|numeric'
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => false, 'error' => $validator->errors()->first()], 422);
    }

  $userId  = Session::get('userId');
 $verify = resetpassword::where('userId', $userId)->first();
 resetpassword::where('userId', $userId)->delete();
  if(!$verify || $request->otp != $verify->otp){
    return response()->json([
        'status' => false, 
        'message' => 'Invalid or expired OTP.',
        
    ],
       
         400);

  }
  return response()->json([
     'status' => true,
     'Message' => 'OTP verified succcesfully',
    //  "data" => $verify
  ]);
    
}

  // resend otp
 
  public function resendOtp(Request $request)
  {
      $validator = Validator::make($request->all(), [
          'email' => 'required|string|email',
          'userId' => 'required|numeric'
      ]);
  
      if ($validator->fails()) {
          return response()->json([
              'status' => false,
              'error' => $validator->errors()->first()
          ], 422);
      }
  
      // 🔍 Find the user using the provided email and userId
      $user = User::where('id', $request->userId)
                  ->where('email', $request->email)
                  ->first();
  
      if (!$user) {
          return response()->json([
              'status' => false,
              'message' => 'User with provided email not found.'
          ], 404);
      }
  
      // 🔁 Generate a new OTP
      $otp = rand(10000, 999999);
  
      // 🧹 Delete old OTP if it exists
      resetpassword::where('userId', $user->id)->delete();
  
      // 🆕 Create new OTP record
      resetpassword::create([
          'email' => $user->email,
          'otp' => $otp,
          'userId' => $user->id,
          'expires_at' => Carbon::now()->addMinutes(10)
      ]);
  
      // ✉️ Send new OTP mail
      Mail::to($user->email)->send(new resendMail($otp));
  
      return response()->json([
          'status' => true,
          'message' => 'New OTP sent successfully. It will expire in 10 minutes.'
      ], 200);
  }
  




    // reset password
    public function resetPassword(Request $request){
        $validate = Validator::make($request->all(),[
       'password' => "required|string|max:8|confirmed",
       'password_confirmation' => "required|string|max:8"

        ]);

        if ($validate->fails()) {
            return response()->json([
                "status" => false,
                "error" => $validate->errors()->first()
            ], 422);
        }
        $userId = Session('userId');
            $user = User::find($userId);
            $user->password = Hash::make($request->password);
            $user->save();

        return response()->json([
            "status" => true,
            "message" => "updated successfully"
        ], 422);

       
    

           
    }
}
