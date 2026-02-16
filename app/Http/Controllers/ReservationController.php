<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::with('inventory')->where('status', 'pending')->get();
        return view('admin.reservations', compact('reservations'));
    }

    public function manualRestore($id)
    {
        $res = Reservation::findOrFail($id);

        if ($res->status !== 'pending') {
            DB::transaction(function() use ($res) {
                $inventory = $res->inventory;

                $inventory->increment('stock_quantity', $res->quantity);
                $inventory->decrement('reserved_quantity', $res->quantity);

                $res->update(['status' => 'restored']);
            });
            return back()->with('success', 'Stock has been manually restored');
        }

        return back()->with('error', 'This reservation is no longer pending.');
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
    public function store(StoreReservationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }
}
