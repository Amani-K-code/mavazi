<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    public function index(Request $request){
        $query = Sale::with('user', 'saleItems.inventory');

        if($request->month){
            $query->whereMonth('created_at', $request->month);
        }

        $transactions = $query->latest()->paginate(20);
        return view('admin.transactions.index', compact('transactions'));
    }

    public function generateReceiptNumber($sale){
        $year = date('Y');
        $id = str_pad($sale->id, 6, '0', STR_PAD_LEFT);
        return "LCS-{$year}-{$id}";
    }
}
