<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminInventoryController extends Controller
{
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
}
