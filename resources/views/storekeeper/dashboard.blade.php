@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <h2 class="text-2xl font-black text-logos-blue uppercase">Inventory Overview</h2>
        <p class="text-gray-500">Managing stock for Logos Christian School.</p>
    </div>

    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addStock{{ $item->id }}">
    Add Stock
    </button>

    <div class="modal fade" id="addStock{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('inventory.addStock', $item->id) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Restock: {{ $item->item_name }} ({{ $item->size_label }})</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Quantity to Add</label>
                            <input type="number" name="amount" class="form-control" placeholder="e.g. 25" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reason for Adjustment</label>
                            <select name="reason" class="form-select" required>
                                <option value="New Shipment">New Shipment Received</option>
                                <option value="Return to Stock">Customer Return</option>
                                <option value="Stock Correction">Inventory Audit Correction</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Update Stock Level</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection