@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-in fade-in duration-500">
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black text-logos-blue dark:text-white italic">Reserved Inventory</h2>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-logos-gold">Manage items currently held for customers</p>
        </div>
        <div class="glass-card px-6 py-3 rounded-2xl">
            <p class="text-[10px] font-black text-gray-400 uppercase">Active Bookings</p>
            <p class="font-black text-lg text-logos-gold">{{ $reservations->count() }} Items</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4">
        @forelse($reservations as $res)
        <div class="glass-card p-6 rounded-3xl flex items-center justify-between group hover:border-logos-gold/50 transition">
            <div class="flex items-center gap-6">
                <div class="w-12 h-12 bg-logos-blue/10 rounded-2xl flex items-center justify-center text-logos-blue dark:text-logos-gold">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div>
                    <h4 class="font-black text-logos-blue dark:text-white tracking-tight">
                        {{ $res->inventory->item_name }} 
                        <span class="text-logos-gold ml-2">[{{ $res->inventory->size_label }}]</span>
                    </h4>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        Expires: {{ $res->expires_at->format('d M, Y') }} at {{ $res->expires_at->format('h:i A') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-12">
                <div class="text-center">
                    <p class="text-[9px] font-black text-gray-400 uppercase mb-1">Quantity</p>
                    <p class="font-black text-lg">{{ $res->quantity }}</p>
                </div>
                
                <form action="{{ route('admin.reservations.restore', $res->id) }}" method="POST" onsubmit="return confirm('Restore this item to available stock?')">
                    @csrf
                    <button type="submit" class="bg-amber-500/10 text-amber-600 hover:bg-amber-500 hover:text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase transition border border-amber-500/20 shadow-sm">
                        <i class="fas fa-undo-alt mr-2"></i> Restore to Stock
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-20 glass-card rounded-[3rem] border-dashed">
            <div class="w-20 h-20 bg-slate-100 dark:bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-calendar-check text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-400 font-bold uppercase tracking-widest">No pending reservations found.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection