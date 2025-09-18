<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function getData()
    {
        $transactions = Transaction::latest()->get();

        return response()->json([
            'data' => $transactions
        ]);
    }
}
