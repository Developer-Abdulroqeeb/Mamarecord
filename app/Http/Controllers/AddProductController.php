<?php

namespace App\Http\Controllers;
use App\Models\producthistory;
use Illuminate\Routing\Controller;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AddProductController extends Controller
{
    // Add new product
    public function addproduct(Request $request){

        $validator = Validator::make($request->all(), [
            'ProductName' => 'required|string|',
            'StockQnty' => 'required|numeric',
            'Unit' => 'required|string',
            'CostPerUnit' => 'required|numeric',
            'SellinPerUnit' => 'required|numeric',
            'ReorderLevel' => 'numeric',
            'SupplierName' => 'required|string',
            
            'description' => 'required|string'
        ]);
        
        if($validator->fails()){
            return response()->json([
               "status" => false,
               "error" => $validator->errors()->first()
            ]);
        }
        $userId = auth()->user()->id;
        //  $user = $request->user();
        $today = Carbon::now()->format('Y-m-d'); // "2025-10-27"
    $checkproduct =   product::where([ 'userId' => $userId , 'ProductName' => $request->ProductName])->first();
    producthistory::create([
        'ProductName' => $request->ProductName,
        'StockQnty' => $request->StockQnty,
        'Unit' => $request->Unit,
        'CostPerUnit' => $request->CostPerUnit,
        'SellinPerUnit' => $request->SellinPerUnit,
        'ReorderLevel' => $request->ReorderLevel,
        'SupplierName' => $request->SupplierName,
       
        'userId' => $userId,
        'description' => $request->description
    ]);
      
  
    if($checkproduct){
        $stock = $checkproduct->StockQnty + $request->StockQnty;
      $updateproduct = product::where(['UserId'=> $userId, "id" => $checkproduct->id ])->update([
                'StockQnty' => $stock,
                'Unit' => $request->Unit,
                'CostPerUnit' => $request->CostPerUnit,
                'SellinPerUnit' => $request->SellinPerUnit,
                'ReorderLevel' => $request->ReorderLevel,
                'SupplierName' => $request->SupplierName,
           
                'description' => $request->description
            ]);
            return response()->json([
                 "message" => "product updated successfully",
                 "status" => true,
                 "data" => $updateproduct 

                ]);
         }else{
            $insert = product::create([
                'ProductName' => $request->ProductName,
                'StockQnty' => $request->StockQnty,
                'Unit' => $request->Unit,
                'CostPerUnit' => $request->CostPerUnit,
                'SellinPerUnit' => $request->SellinPerUnit,
                'ReorderLevel' => $request->ReorderLevel,
                'SupplierName' => $request->SupplierName,
         
                'userId' => $userId,
                'description' => $request->description
            ]);
            
            if(!$insert){
                return response()->json([
                         "status" => false,
                         "message" => "Unsuccessful"
                ],202);
            }
                 return response()->json([
                         "status" => true,
                          "data" => $insert
                 ]);
         }     
    }
    // All products
    public function allproduct(Request $request){
        $userId = auth()->user()->id;

     $product =   product::where('userId', $userId)->get();
        return response()->json([
"status" => true,
"data" => $product
        ]);
    }
    public function producthistory(Request $request){
        $userId = auth()->user()->id;
        $producthistory = producthistory::where("userId",  $userId)->get();
  return response()->json([
  "status" => true,
  "data" => $producthistory
  ], 302);
    }

    // search for product
    public function searchproduct(Request $request)
{
    $userId = auth()->user()->id;

    $query = product::where('userId', $userId);

    // Optional filter by payment method
    if ($request->has(' ProductName')) {
        $query->where('ProductName', $request->ProductName);
    }
  // Optional filter by date range
  if ($request->has(['from_date', 'to_date'])) {
    $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
}
    $product = $query->get();

    return response()->json([
        'message' => true,
        'data' => $product
    ]);
}

}

