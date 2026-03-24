@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- 1. INTEGRATED HEADER (Restored the Counter) --}}
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-logos-blue dark:text-white uppercase tracking-tighter">Delivery Oversight</h2>
            <p class="text-sm text-gray-500 font-medium">Review and verify incoming stock shipments</p>
        </div>
        <span class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 px-4 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest border border-amber-200 dark:border-amber-800">
            <i class="fas fa-clock mr-2"></i> Awaiting Approval: {{ $deliveries->where('status', 'PENDING')->count() }}
        </span>
    </div>

    <div class="grid gap-8">
        @forelse($deliveries as $delivery)
        {{-- 2. PREMIUM DELIVERY CARD --}}
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-xl border border-gray-100 dark:border-slate-700 overflow-hidden transition hover:shadow-2xl group">
            
            {{-- Card Top: Status & Actions --}}
            <div class="p-6 flex flex-col md:flex-row justify-between items-center gap-4 bg-slate-50 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-logos-blue dark:bg-slate-800 text-white dark:text-logos-gold rounded-2xl flex items-center justify-center text-xl shadow-lg shadow-blue-200 dark:shadow-none">
                        <i class="fas fa-truck-loading"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-black text-lg text-logos-blue dark:text-white uppercase tracking-tight">Delivery #{{ $delivery->id }}</h3>
                            <span class="text-[9px] font-black px-2 py-0.5 rounded uppercase {{ $delivery->status == 'PENDING' ? 'bg-amber-500 text-white' : 'bg-green-600 text-white' }}">
                                {{ $delivery->status }}
                            </span>
                        </div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            Submitted By: <span class="text-logos-gold uppercase">{{ $delivery->user->name }}</span> • {{ $delivery->created_at->format('d M, Y') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="text-right hidden md:block">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Total Invoice Value</p>
                        <p class="text-xl font-black text-logos-blue dark:text-white">KSh {{ number_format($delivery->total_invoice_amount) }}</p>
                    </div>

                    @if($delivery->status == 'PENDING')
                        {{-- INTEGRATED APPROVAL FORM WITH CATEGORY FIX --}}
                        <form action="{{ route('admin.deliveries.approve', $delivery->id) }}" method="POST" class="flex gap-2 items-center bg-white dark:bg-slate-800 p-2 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm">
                            @csrf @method('PATCH')
                            
                            <select name="category" required class="bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-2 text-[10px] font-black uppercase text-logos-blue dark:text-white focus:ring-2 focus:ring-logos-gold">
                                <option value="">Assign Category</option>
                                <option value="Shirts">Shirts</option>
                                <option value="Bottoms">Bottoms</option>
                                <option value="Outerwear">Outerwear</option>
                                <option value="Accessories">Accessories</option>
                            </select>

                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-xl font-black text-[10px] hover:bg-green-700 transition uppercase tracking-widest shadow-lg shadow-green-100 dark:shadow-none">
                                Approve & Restock
                            </button>
                        </form>
                    @else
                        <div class="flex items-center gap-2 text-green-600 bg-green-50 dark:bg-green-900/20 px-4 py-2 rounded-xl border border-green-100 dark:border-green-800">
                            <i class="fas fa-check-circle"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest">Stock Updated</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card Bottom: Items Grid --}}
            <div class="p-8">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center">
                    <span class="w-8 h-[1px] bg-gray-200 mr-2"></span> Manifest Details
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($delivery->items as $item)
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/30 rounded-2xl border border-gray-100 dark:border-slate-700 hover:border-logos-gold transition group/item">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-logos-blue dark:text-white uppercase group-hover/item:text-logos-gold transition">{{ $item->item_name }}</span>
                                <span class="text-[10px] font-bold text-gray-400">Size: {{ $item->size }}</span>
                            </div>
                            <span class="bg-white dark:bg-slate-800 px-3 py-1 rounded-lg text-xs font-black text-logos-blue dark:text-logos-gold border border-gray-100 dark:border-slate-700">
                                x{{ $item->quantity }}
                            </span>
                        </div>
                        
                        {{-- Enhanced Note Visibility --}}
                        <div class="p-2 bg-white dark:bg-slate-800/50 rounded-xl border border-dashed border-gray-200 dark:border-slate-700">
                            <p class="text-[9px] font-bold text-gray-500 dark:text-gray-400 italic">
                                <i class="fas fa-sticky-note mr-1 text-logos-gold opacity-50"></i>
                                Note: {{ $item->note ?? 'No storekeeper remarks.' }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @empty
        {{-- 3. ORIGINAL EMPTY STATE --}}
        <div class="text-center py-32 bg-white dark:bg-slate-800 rounded-[3rem] border-2 border-dashed border-gray-100 dark:border-slate-700 shadow-xl">
            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-clipboard-check text-4xl text-gray-200 dark:text-slate-700"></i>
            </div>
            <h3 class="text-xl font-black text-logos-blue dark:text-white uppercase">All Caught Up!</h3>
            <p class="text-gray-400 font-bold max-w-xs mx-auto mt-2">There are currently no pending deliveries waiting for stock approval.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection