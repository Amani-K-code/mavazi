@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-black text-logos-blue uppercase tracking-tight">Pending Deliveries</h2>
        <span class="bg-amber-100 text-amber-700 px-4 py-1 rounded-full text-xs font-bold">
            Awaiting Approval: {{ $deliveries->where('status', 'PENDING')->count() }}
        </span>
    </div>

    <div class="grid gap-6">
        @forelse($deliveries as $delivery)
        <div class="glass-card p-6 rounded-[2rem] border border-gray-100 bg-white shadow-sm hover:shadow-md transition">
            <div class="flex flex-col md:flex-row justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="p-2 bg-blue-50 text-blue-600 rounded-lg"><i class="fas fa-truck-loading"></i></span>
                        <h3 class="font-black text-lg text-logos-blue">Delivery #{{ $delivery->id }}</h3>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase {{ $delivery->status == 'PENDING' ? 'bg-amber-500 text-white' : 'bg-green-500 text-white' }}">
                            {{ $delivery->status }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 font-bold uppercase">Submitted By: <span class="text-logos-gold">{{ $delivery->user->name }}</span></p>
                    <p class="text-xs text-gray-400">Date: {{ $delivery->delivery_date }}</p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right mr-4">
                        <p class="text-[10px] font-black text-gray-400 uppercase">Total Value</p>
                        <p class="text-xl font-black text-logos-blue">KSh {{ number_format($delivery->total_invoice_amount) }}</p>
                    </div>
                    
                    @if($delivery->status == 'PENDING')
                        <form action="{{ route('admin.deliveries.approve', $delivery->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-xl font-black text-xs hover:bg-green-700 transition shadow-lg shadow-green-200 uppercase tracking-widest">
                                Approve & Add to Stock
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="mt-6 border-t border-gray-50 pt-4">
                <p class="text-[10px] font-black text-gray-400 uppercase mb-3">Items in this delivery:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($delivery->items as $item)
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <div class="flex justify-between">
                            <span class="font-bold text-sm text-logos-blue">{{ $item->item_name }}</span>
                            <span class="font-black text-logos-gold">x{{ $item->quantity }}</span>
                        </div>
                        <p class="text-[10px] text-gray-400">Size: {{ $item->size }}</p>
                        @if($item->note)
                            <p class="mt-2 text-[10px] italic text-blue-600 bg-blue-50 p-1 rounded">Note: {{ $item->note }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-20 bg-gray-50 rounded-[2rem] border-2 border-dashed border-gray-200">
            <i class="fas fa-clipboard-check text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 font-bold">No pending deliveries found.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection