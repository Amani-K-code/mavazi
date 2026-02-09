<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Models\Inventory;
use Illuminate\Http\Request;

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

        return view('cashier.dashboard', compact('inventory'));


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

    /**
     * Show the form for creating a new resource.
     */
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
