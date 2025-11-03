<?php

use App\Http\Controllers\AddProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SalesController;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// register
Route::post("/register", [AuthController::class, 'register']);
Route::post('/verifyMail', [ForgetPasswordController::class, "verifyMail"]);
// Login
Route::post("/login", [AuthController::class, "login"]);
// forgot password
Route::post('/resetPassword', [ForgetPasswordController::class, "resetPassword"]);
// verify otp
Route::post('/verifyOtp', [ForgetPasswordController::class, "verifyOtp"]);
// resend otp
Route::post('resendOtp/', [ForgetPasswordController::class, 'resendOtp']);
// Route::get('/test', function () {
//      return 'ok';
// });

Route::middleware('auth:sanctum')->group(function(){
  //  product side
  Route::post('/addproduct', [AddProductController::class, 'addproduct']);
  Route::get('/allproduct', [AddProductController::class, "allproduct"]);
  Route::get("/producthistory", [AddProductController::class, "producthistory"]);
   Route::get("/searchproduct", [AddProductController::class, "searchproduct"]);
  //  Sales side
   Route::post("/addsale", [SalesController::class, "addsale"]);
   Route::get("/searchsale", [SalesController::class, "searchsale"]);
   Route::get("/allsale", [SalesController::class, "allsale"]);

   // expenses
Route::post("/addexpense", [ExpenseController::class, "addexpense"]);
Route::get("/expensehistory", [ExpenseController::class, "expensehistory"]);

// Payment History/sum of payment/Search for Payment
 Route::get('/payment_history', [PaymentController::class, 'payment_history']); 
 Route::get('totalpayment',[PaymentController::class, 'totalpayment'] );
 Route::get('countpayment',[PaymentController::class, 'countpayment'] );

});

