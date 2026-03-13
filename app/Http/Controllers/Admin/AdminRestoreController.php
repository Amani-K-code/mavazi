<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminRestoreController extends Controller
{
    public function restoreToStock($id){
        $reservation = Reservation::findOrFail($id);
        $inventory = Inventory::find($reservation->inventory_id);

        DB::transaction(function () use ($reservation, $inventory) {
            $inventory ->increment('stock_quantity', $reservation->quantity);
            $reservation-> delete();
        });

        return back()->with('success', 'Stock restored successfully');
        }

    }
