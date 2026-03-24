<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryAdjustment;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    public function index(Request $request){
        $cashiers = User::where('role',  'Cashier')->get();

        $query = Sale::with(['user', 'saleItems.inventory'])->latest();

        if($request->month){
            $query->whereMonth('created_at', $request->month);
        }

        //Filter by Cashier
        if ($request->filled('cashier_id')){
            $query->where('user_id', $request->cashier_id);
        }


        //Filter by Customer Name
        if($request->filled('customer')){
            $query->where('customer_name', 'like', '%' . $request->customer . '%');
        }

        //Filter by specific date
        if($request->filled('date')){
            $query->whereDate('created_at', $request->date);
        }

        $sales = $query->paginate(12);
        return view('admin.transactions.index', compact('sales', 'cashiers'));
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
