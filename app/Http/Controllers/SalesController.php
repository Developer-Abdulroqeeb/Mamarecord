<?php

namespace App\Http\Controllers;
use App\Models\Salesummary;
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
        'sales.*.seller_name' => 'nullable|string',
        'amount_paid' => 'required|numeric',
        'payment_method' => 'nullable|string'

    ]);

    if ($validator->fails()) {
        return response()->json([
            "message" => false,
            "error" => $validator->errors()->first()
        ]);
    }
//  record the amount
$totalAmount = 0;
foreach ($request->sales as $sale) {
    $totalAmount += $sale['price'] * $sale['Quantity'];
}

$balance = $totalAmount - $request->amount_paid;

// Step 2: create ONE summary record
$summary = Salesummary::create([
    'userId' => $userId,
    'total_amount' => $totalAmount,
    'amount_paid' => $request->amount_paid,
    'balance' => $balance,
    'payment_method' => $request->payment_method
]);


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
            'paymentId' => $summary->id
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

public function searchsale(Request $request)
{
    $userId = auth()->user()->id;

    $query = sale::where('userId', $userId);

    // Optional filter by payment method
    if ($request->has('product_name')) {
        $query->where('product_name', $request->product_name);
    }
  // Optional filter by date range
  if ($request->has(['from_date', 'to_date'])) {
    $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
}
  
 // Optional filter by seller name
 if ($request->has('seller_name')) {
    $query->where('seller_name', $request->seller_name);
}

    $sale = $query->get();

    return response()->json([
        'message' => true,
        'data' => $sale
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
