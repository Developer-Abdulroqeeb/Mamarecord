<?php

namespace App\Http\Controllers;
use App\Models\returned_good;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
class ReturnController extends Controller
{
public function addreturn(Request $request){
  $validate = Validator::make($request->all(),[
      "qnty_return" => 'required|numeric',
      "cost_per_unit" => 'required|numeric',
      "reason" => 'required|numeric',
      "product_id" => 'required|numeric',
      "mode_refund" => 'required|numeric',
     

  ]);
  if($validate->fails()){
    return response()->json([
    "message" => false,
    "error" => $validate->errors()->first()
    ]);
  }
   $returned_product = returned_good::create([
         "qnty_return" => $request->qnty_return,
         "cost_per_unit" => $request->cost_per_unit,
         "reason" => $request->reason,
         "mode_refund" => $request->mode_refund,
         "product_id" => $request->product_id
   ]);
}

}
