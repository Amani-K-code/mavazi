<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryAdjustment;
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

    public function auditindex(Request $request){
        $query = Sale::where('payment_method', 'M-PESA');

        //Allow filtering by date if needed
        if ($request->has('date')){
            $query->whereDate('created_at', $request->date);
        }

        $mpesaSales = $query->latest()->get();
        return view('admin.transactions.audit', compact('mpesaSales'));
    }


    public function updateStatus(Request $request, $id){
        $sale = Sale::findOrFail($id);

        $request->validate([
            'status' => 'required|in:CONFIRMED,BOOKED,CANCELLED'
        ]);

        $oldStatus = $sale->status;
        $sale->update(['status' => $request->status]);

        InventoryAdjustment::create([
            'inventory_id' => $sale->items->first()->inventory_id ?? null, //link to first item for context
            'quantity_before' => 0,
            'quantity_change' => 0,
            'quantity_after' => 0,
            'reason' => "Sale status changed from {$oldStatus} to {$request->status} (Sale #{$sale->receipt_no})",
            'user_id'=> auth()->id(),
        ]);

        return back()->with('success', "Sale #{$sale->receipt_no} status updated to {$request->status}.");
    }
}
