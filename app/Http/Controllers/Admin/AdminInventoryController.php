<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminInventoryController extends Controller
{
    public function index(Request $request){
        $query = Inventory::query();

        if ($request->filled('search')){
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('item_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('category', 'LIKE', "%{$searchTerm}%");
                  
            });
        }

        $inventories = $query->orderBy('category')->get()->groupBy('category');
        return view('admin.inventory.index', compact('inventories'));
    }


    public function store(Request $request){
        $request->validate([
            'item_name' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'low_stock_threshold' => 'required|integer',
            'size_label' => 'required|string'
        ]);

        Inventory::create($request->all());
        return redirect()->route('admin.inventory.index')->with('success', 'New inventory item added successfully!');
    }

    public function destroy($id){
        Inventory::findOrFail($id)->delete();
        return redirect()->route('admin.inventory.index')->with('success', 'Item removed from system.');
        
    }
    
    public function updatePrice(Request $request, $id){
        $item = Inventory::findOrFail($id);

        //Admin Password Check for Locked Prices
        if ($item->is_locked) {
            // Verify the admin's password
            if (!Hash::check($request->password, auth()->user()->password)) {
                return back()->with('error', 'Incorrect admin password for locked item.');
            }
        }

        $item->update([
            'price'=> $request->price,
            'is_locked' => $request->has('is_locked')  || $request->is_locked == true      
            
        ]);

        return back()->with('success', 'Price updated and secured');

    }

    public function applyBulkDiscount(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'percentage' => 'required|numeric'
        ]);

        $multiplier = 1 - ($request->percentage / 100);

        // Fetch and update
        Inventory::where('category', $request->category)
            ->get()
            ->each(function ($item) use ($multiplier) {
                $item->price = $item->price * $multiplier;
                $item->save();
            });

        return back()->with('success', "Applied {$request->percentage}% discount.");
    }

    public function restockPdf(){
        $items = Inventory::whereColumn('stock_quantity', '<=', 'low_stock_threshold')->get();
        $pdf=Pdf::loadView('pdf.restock_list', compact('items'));
        return $pdf->download('restock_priority_'.now()->format('d_m_Y').'.pdf');
    }

}
