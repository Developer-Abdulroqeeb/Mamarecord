<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Salesummary;
class PaymentController extends Controller
{
    //   payment history
  public function payment_history(Request $request){
    $userId = auth()->user()->id;

    $payment = Salesummary::where('userId',$userId )->get();
  
    return response()->json([
          "message" => true,
          "data" => $payment
    ]);
  }

 //   total payment/perdate
public function totalpayment(Request $request)
{
    $userId = auth()->user()->id;

    $query = Salesummary::where('userId', $userId);

    // Optional filter by payment method
    if ($request->has('payment_method')) {
        $query->where('payment_method', $request->payment_method);
    }

    // Optional filter by date range
    if ($request->has(['from_date', 'to_date'])) {
        $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
    }

    $total = $query->sum('amount_paid');

    return response()->json([
        'message' => true,
        'total_payment' => $total
    ]);
}

// counting  payment
public function countpayment(Request $request)
{
    $userId = auth()->user()->id;

    $query = Salesummary::where('userId', $userId);

    // Optional filter by payment method
    if ($request->has('payment_method')) {
        $query->where('payment_method', $request->payment_method);
    }

    // Optional filter by date range
    if ($request->has(['from_date', 'to_date'])) {
        $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
    }

    $total = $query->count('amount_paid');

    return response()->json([
        'message' => true,
        'total_payment' => $total
    ]);
}

}
