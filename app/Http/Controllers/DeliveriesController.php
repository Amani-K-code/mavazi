<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeliveriesRequest;
use App\Http\Requests\UpdateDeliveriesRequest;
use App\Models\Deliveries;
use App\Models\Inventory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveriesController extends Controller
{
    public function approve(Request $request, Deliveries $delivery)
    {
        $request->validate([
            'category' => 'required|string',
            'admin_note' => 'nullable|string',
        ]);

        foreach($delivery->items as $item){
            if($item->inventory){
                $item->inventory->increment('stock_quantity', $item->quantity);
                // Update category if changed
                $item->inventory->update(['category' => $request->category]);
            }
        }


        if($delivery->status !== 'PENDING') return back()->with('error', 'Processed already.');

        $delivery->update([
            'status' => 'CONFIRMED',
            'admin_note' => $request->admin_note]);
        return back()->with('success', 'Delivery confirmed.Stock updated under category: ' . $request->category . '. Stock levels updated!');
    }




    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $deliveries=Deliveries::where('user_id', auth()->id())->latest()->take(10)->get();
        return view('deliveries.index', compact('deliveries'));
    }  
    
    public function adminIndex()
    {
        $deliveries = Deliveries::with('user', 'items')->latest()->get();
        return view('admin.deliveries.index', compact('deliveries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Inventory::all()->groupBy('item_name');

        $tabs = ['ALL', 'SHIRTS', 'BOTTOMS', 'OUTERWEAR', 'SPORTSWEAR', 'ACCESSORIES'];
        
        return view('deliveries.create', compact('items', 'tabs'));
    }






    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'delivery_date' => 'required|date',
            'total_invoice_amount' => 'required|numeric',
            'payment_due_date' => 'required|date',
            'items' => 'required|array|min:1',
        ]);

        return DB::transaction(function() use ($request) {
            $delivery = Deliveries::create([
                'delivery_date' => $request->delivery_date,
                'total_invoice_amount' => $request->total_invoice_amount,
                'payment_due_date' => $request->payment_due_date,
                'user_id' => auth()->id(),
                'status' => 'PENDING',
            ]);

            foreach($request->items as $data) {
                if (empty($data['quantity']) || $data['quantity'] <= 0) continue;

                $inventoryId = $data['inventory_id'] ?? null;

                if(!$inventoryId){
                    $newItem = Inventory::create([
                        'item_name' => $data['item_name'],
                        'size_label' => $data['size'],
                        'stock_quantity' => 0,
                        'category' => 'General',
                        'low_stock_threshold' => 5,
                        'price' => $data['price'],
                    ]);
                    $inventoryId = $newItem->id;
                    $itemName = $data['item_name'];
                    $size = $data['size'];
                } else {
                    $existingItem = Inventory::find($inventoryId);
                    $itemName = $existingItem->item_name;
                    $size = $existingItem->size_label;
                }

                $delivery->items()->create([
                    'inventory_id' => $inventoryId,
                    'item_name' => $itemName,
                    'size' => $size,
                    'quantity' => $data['quantity'],
                    'note' => $data['note'] ?? null,
                ]);
            }

            //Check if user clicked "Submit & Download"
            if($request->has('submit_with_pdf')){
                session()->flash('success', 'Delivery recorded succesfully');
                return response()->streamDownload(function() use ($delivery) {
                    echo Pdf::loadView('deliveries.pdf', compact('delivery'))->output();
                }, "Delivery_Record_{$delivery->id}.pdf");
            }

            return redirect()->route('storekeeper.deliveries.index')
                ->with('success', 'Delivery recorded and awaiting Admin Approval.');
        });
    }



    /**
     * Display the specified resource.
     */
    public function show(Deliveries $deliveries)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deliveries $deliveries)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeliveriesRequest $request, Deliveries $deliveries)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deliveries $deliveries)
    {
        //
    }

    public function downloadPDF(Deliveries $delivery){

    $delivery->load(['items', 'user']);

    $pdf = Pdf::loadView('deliveries.pdf', compact('delivery'))
            ->setPaper('a4', 'portrait');

    
    return $pdf->download("Delivery_Record_{$delivery->id}.pdf");
    }
    
    
    }
