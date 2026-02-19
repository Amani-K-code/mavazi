@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-end px-4">
    <div>
        <h2 class="text-3xl font-black text-logos-blue dark:text-white uppercase tracking-tight">Storekeeper Inventory</h2>
        <p class="text-gray-500 text-base font-bold text-sm">Welcome back, {{ auth()->user()->name }}</p>
    </div>
</div>

@if(session('success'))
    <div class="mx-4 mb-6 bg-green-600 text-white p-4 rounded-xl shadow-lg flex items-center border-l-4 border-green-400">
        <i class="fas fa-check-circle fa-lg mr-3"></i>
        <span class="font-bold text-lg uppercase tracking-tight">{{ session('success') }}</span>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 px-4">
    @foreach($items as $itemName => $variants)
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 flex flex-col overflow-hidden transition-all relative">

        @php $anyFlagged = $variants->contains('is_flagged', true); @endphp
        @if($anyFlagged)
        <div class="absolute top-0 right-0 mt-4 mr-4 text-logos-gold z-10">
            <i class="fas fa-flag text-2xl animate-bounce" title="Priority Item"></i>
        </div>
        @endif
        
        <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-900/40">
            <h3 class="font-black text-xl text-logos-blue dark:text-white uppercase leading-tight">
                {{ $itemName }}
            </h3>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mt-1">
                {{ $variants->count() }} Variants
            </p>
        </div>

        <div class="flex-grow overflow-y-auto max-h-[500px] p-4 space-y-3 custom-scrollbar">
            @foreach($variants as $variant)
            <div class="flex items-center justify-between p-4 rounded-2xl border border-gray-50 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                
                <div class="flex flex-col">
                    <span class="text-base font-black text-logos-blue dark:text-slate-100 uppercase tracking-tight">
                        {{ $variant->size_label }}
                    </span>
                    <div class="flex items-center mt-0.5">
                        <span class="text-xl font-black {{ $variant->stock_quantity <= $variant->low_stock_threshold ? 'text-red-600' : 'text-green-600' }}">
                            {{ $variant->stock_quantity }}
                        </span>
                        <span class="ml-1.5 text-[10px] font-bold text-gray-400 uppercase">In Stock</span>
                    </div>
                </div>

                <form action="{{ route('storekeeper.restock', $variant->id) }}" method="POST" class="flex items-center space-x-2">
                    @csrf
                    <input type="number" name="restock_amount" min="1" required
                        class="w-16 h-12 bg-slate-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-600 focus:border-logos-blue rounded-xl px-2 text-lg font-black text-center transition-all"
                        placeholder="+">
                    
                    <button type="submit" 
                        class="bg-logos-blue text-white w-12 h-12 rounded-xl hover:bg-logos-gold hover:text-logos-blue transition-all flex items-center justify-center shadow-md active:scale-95">
                        <i class="fas fa-plus fa-sm"></i>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
</style>
@endsection