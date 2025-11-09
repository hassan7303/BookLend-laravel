<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fine;

class FineController extends Controller
{
    public function index()
    {
        return Fine::with('borrow.bookCopy.book')->paginate(30);
    }

    public function pay(Request $request, $fineId)
    {
        $fine = Fine::findOrFail($fineId);
        if ($fine->paid) return response()->json(['message'=>'قبلا پرداخت شده است'], 400);
        $fine->paid = true;
        $fine->paid_date = now()->toDateString();
        $fine->save();
        return response()->json($fine);
    }
}
