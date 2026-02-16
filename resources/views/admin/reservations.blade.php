@extends('layouts.app')

@section('content')

<div class="container">
    <h2>Pending Reservations</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Expires At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $res)
            <tr>
                <td>{{ $res->inventory->item_name }}</td>
                <td>{{ $res->inventory->size_label }}</td>
                <td>{{ $res->quantity }}</td>
                <td>{{ $res->expires_at->format('d M, h:i A') }}</td>
                <td>
                    <form action="{{ route('admin.reservations.restore', $res->id) }}" method="POST" onsubmit="return confirm('Restore this item to available stock?')">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm">Restore to Stock</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No pending reservations found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection