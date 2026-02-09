<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Inventory;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function store(Request $request){
        return DB::transaction(function() use ($request) {
            $receiptNo= 'LCS-' . NOW()->format('dmY') . '-' . str_pad(Sale::count() + 1, 4, '0', STR_PAD_LEFT);

            $referenceId = $request->reference_id;

            if ($request->status === 'BOOKED'){
                $bookingCount = Sale::where('status', 'BOOKED')->count() + 1;
                $referenceId = 'BK-' . str_pad($bookingCount, 4, '0', STR_PAD_LEFT);
            }
            
            
            
            $sale = Sale::create([
                'receipt_no' => $receiptNo,
                'customer_name' => $request->customer_name,
                'child_name' => $request->child_name,
                'payment_method' => $request->payment_method,
                'reference_id' => $referenceId, // Uses the genertaed ID or input ID based on the status
                'total_amount' => $request->total_amount,
                'status' => $request->status,
                'user_id' => Auth::id(),
                'expiry_date' => $request->status === 'BOOKED' ? now()->addMonth() : null,
            ]);

        $cartItems = json_decode($request->cart_data, true);
        foreach($cartItems as $item){
            $sale->items()->create([
                'inventory_id' => $item['id'],
                'quantity' => $item['qty'],
                'unit_price' => $item['price'],
            ]);

            $inventory = Inventory::find($item['id']);
            if ($inventory){
                if ($request->status === 'CONFIRMED'){
                    //DIRECT SALE: REDUCE PHYSICAL STOCK
                    $inventory->decrement('stock_quantity', $item['qty']);
                } elseif($request->status === 'BOOKED'){
                    // Booking: moved to reserved column in order not to be sold to someone else
                    $inventory->increment('reserved_quantity', $item['qty']);
                }
            }
            // NB: If BOOKED, transaction is saved and tracked but not deducted from stock until fully paid.
        }

        return view('cashier.feedback', compact('sale'));
        });
    }



    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
    }

    public function showPayment(Request $request){
        $cartData = json_decode($request->cart_data, true);
        $total = collect($cartData)->sum(fn($i) => $i['price'] * $i['qty']);
        return view('cashier.payment', compact('cartData', 'total'));
    }

    

    public function downloadReceipt($id){
        $sale = Sale::with('items.inventory')->findOrFail($id);
        $pdf = Pdf::loadView('pdf.receipt', compact('sale'));
        return $pdf->download($sale->receipt_no . '.pdf');
    }

    public function storeFeedback(Request $request, Sale $sale){
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string|max:500',
        ]);

        $sale->feedback()->create([
            'rating' => $request->rating,
            'comments' => $request->comments,
        ]);

        return redirect()->route('cashier.dashboard')->with('success', 'Feedback received! Thank you for your input.');
    }
}
