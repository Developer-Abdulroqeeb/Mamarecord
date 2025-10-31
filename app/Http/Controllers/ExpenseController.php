<?php

namespace App\Http\Controllers;

use App\Models\expense;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function addexpense(Request $request){
        $userId = auth()->user()->id;
        
        $validator = Validator::make($request->all(), [
         "expense_description" => "string|required",
         "expense_category" => "string|nullable",
         "amount" => "required|numeric",
         "payment_method" => "required|string|"
        ]);

        if($validator->fails()){
            return response()->json([
     "message" => false,
     "error" => $validator->errors()->first()
            ]);
        }

        $addexpense = expense::create([
            "expense_description" => $request->expense_description,
            "expense_category" => $request->expense_category,
            "amount" => $request->amount,
            "payment_method" => $request->payment_method,
            "userId" => $userId
        ]);
        return response()->json([
    "message" => true,
    "data" => $addexpense
        ]);
    }

    // get all expense

    public function expensehistory(Request $request){
        $userId = auth()->user()->id;
        $getexpense = expense::where("userId", $userId)->get();
        return response()->json([
      "message" => true,
      "data" => $getexpense
        ], 302);

    }
}
