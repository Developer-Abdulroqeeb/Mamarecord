<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Routing\Controller;
use App\Models\sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
// add new sales

public function addsale(Request $request)
{
    $userId = auth()->user()->id;

    $validator = Validator::make($request->all(), [
        'sales' => 'required|array',
        'sales.*.product_name' => 'required|string',
        'sales.*.product_id' => 'required|numeric',
        'sales.*.Quantity' => 'required|numeric',
        'sales.*.price' => 'required|numeric',
        'sales.*.seller_name' => 'nullable|string'
    ]);

    if ($validator->fails()) {
        return response()->json([
            "message" => false,
            "error" => $validator->errors()->first()
        ]);
    }

    $inserted = [];
    $totalAmount = 0;
    foreach ($request->sales as $sale) {
        // Find the product
        $product = product::where([
            'id' => $sale['product_id'],
            'userId' => $userId
        ])->first();

        if (!$product) {
            return response()->json([
                "message" => false,
                "error" => "Product not found: " . $sale['product_name']
            ]);
        }
         
        // Check if enough stock is available
        if ($product->StockQnty < $sale['Quantity']) {
            return response()->json([
                "message" => false,
                "error" => "Not enough stock for " . $sale['product_name']
            ]);
        }
        //  check if the price is  the same thing ass the stock quantity
         if($product->CostPerUnit != $sale['price']){
            return response()->json([
                "message" => false,
                "error" => "The price did not correlate with our price tag for"." " . $sale['product_name']
            ]);
         }
        // Deduct the sold quantity
        $newQuantity = $product->StockQnty - $sale['Quantity'];

        // Update the product stock
        $product->update([
            'StockQnty' => $newQuantity
        ]);

        // Record the sale
        $inserted[] = Sale::create([
            'product_name' => $sale['product_name'],
            'product_id' => $sale['product_id'],
            'Quantity' => $sale['Quantity'],
            'price' => $sale['price'],
            'seller_name' => $sale['seller_name'] ?? null,
            'userId' => $userId,
        ]);

        $itemTotal = $sale['Quantity'] * $sale['price'];
        $totalAmount += $itemTotal; // 👈 add to total
    }

    return response()->json([
        "message" => true,
        "data" => $inserted,
        "total" => $totalAmount
    ]);
}

//    search for sale
public function searchsale(Request $request){
  
       $validate = Validator::make($request->all(),
         [
            "date" => 'nullable|date',
            "search" => 'nullable|string'
         ]
);
if($validate->fails()){
     return response()->json([
     "messag" => false,
     "error" => $validate->errors()->first()
     ]);
}
$userId = auth()->user()->id;
    $date = $request->date;
    $search = $request->search;
       
    $sales = sale::where('userId', $userId)
    ->when($request->date, function ($query, $date) {
        $query->whereDate('date', $date);
    })
    ->when($request->search, function ($query, $search) {
        $query->where(function ($q) use ($search) {
            $q->where('product_name', 'LIKE', "%{$search}%")
              ->orWhere('seller_name', 'LIKE', "%{$search}%");
        });
    })
    ->get();
    return response()->json([
        "message" => true,
        "data" => $sales
    ]);
}
  public function allsale(Request $request){
    $userId = auth()->user()->id;

     $allsale = sale::where('userId',$userId )->get();

     return response()->json([
    "message" => true,
    "data" => $allsale
     ],202);
  
  }
}
