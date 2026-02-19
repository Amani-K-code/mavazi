<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Models\Inventory;
use App\Models\InventoryAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Inventory::query();

        if($request->has('search')){
            $query->where('item_name', 'like', '%'. $request->search.'%')
            ->orWhere('size_label', 'like', '%'. $request->search.'%');
        }

        $inventory = $query->get()->groupBy('item_name');
        $items = $query->get();

        return view('cashier.inventory', compact('items'));


    }



    public function dashboard(Request $request) {
    // 1. Get the search input [cite: 7]
    $searchTerm = $request->input('search');
    $category = $request->input('category');

    $query = Inventory::query();

    if ($searchTerm){
        $query->where(function($q) use ($searchTerm){
            $q->where('item_name', 'like', "%{$searchTerm}%")
              ->orWhere('category', 'like', "%{$searchTerm}%")
              ->orWhere('size_label', 'like', "%{$searchTerm}%");
        });
    }

    //Filter by Category Tab
    if ($category && $category !== 'ALL') {
        $query->where('category', $category);
    }

    $inventory = $query->get()->groupBy('item_name');

    $tabs = ['ALL', 'Shirts', 'Bottoms', 'Outerwear', 'Sportswear', 'Accessories', 'Junior School'];

    return view('cashier.dashboard', compact('inventory', 'tabs'));
    
    }


    public function restock(Request $request, Inventory $item){
        $request->validate(['restock_amount' => 'required|integer|min:1']);

        $oldQuantity = $item->stock_quantity;
        $item->increment('stock_quantity', $request->restock_amount);

        InventoryAdjustment::create([
            'inventory_id'=>$item->id,
            'quantity_before'=>$oldQuantity,
            'quantity_change'=>$request->restock_amount,
            'quantity_after'=>$item->stock_quantity,
            'reason'=>'RESTOCK: Added by Storekeeper' . auth()->user()->name,
            'user_id'=>auth()->id(),
        ]);
        
        return back()->with('success', "Added {$request->restock_amount} units to {$item->item_name}");
    }
    /**
     * Show the form for creating a new resource.
     */
    public function addStock(Request $request, $id)
    {
        $item = Inventory::findOrFail($id);
        $old = $item->stock_quantity;
        $item->increment('stock_quantity', $request->amount);
        
        InventoryAdjustment::create([
            'inventory_id' => $item->id,
            'quantity_before' => $old,
            'quantity_change' => $request->amount,
            'quantity_after' => $item->stock_quantity,
            'reason' => 'RESTOCK: '. $request->reason,
            'user_id' => Auth::id(),
        ]);

        return back();

    }

    public function storekeeperDashboard(){
        $items = Inventory::orderBy('item_name')->get()->groupBy('item_name');
        return view('storekeeper.dashboard', compact('items'));
    }

    public function flaggedItems(){
        $items = Inventory::where('is_flagged', true)->orderBy('item_name')->get()->groupBy('item_name');
        return view('storekeeper.dashboard', compact('items'));
    }

    public function restockHistory(){
        $history = InventoryAdjustment::with(['inventory', 'user'])
                    ->where('reason', 'like', 'RESTOCK:%')
                    ->latest()
                    ->get();
        return view('storekeeper.history', compact('history'));
    }

    
    
    
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventoryRequest $request, Inventory $inventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        //
    }
}
