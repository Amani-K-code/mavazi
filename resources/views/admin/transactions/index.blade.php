@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- 1. INTEGRATED HEADER --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-logos-blue dark:text-white uppercase tracking-tighter">Transaction Oversight</h1>
            <p class="text-sm text-gray-500 font-medium">Review, audit, and deep-dive into system sales</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Original Export Button --}}
            <a href="{{ route('admin.report.download', request()->query()) }}" 
            class="px-6 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-300 rounded-2xl shadow-sm hover:bg-gray-50 transition font-black text-[10px] uppercase tracking-widest flex items-center">
                <i class="fas fa-file-pdf mr-2 text-red-500"></i> Export Filtered PDF
            </a>
            <div class="bg-logos-gold text-logos-blue px-4 py-3 rounded-2xl text-[10px] font-black uppercase shadow-lg">
                {{ $sales->total() }} Total Records
            </div>
        </div>
    </div>

    {{-- 2. ENHANCED SEARCH BAR (Deep Dive Filters) --}}
    <form action="{{ route('admin.transactions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 bg-white dark:bg-slate-800 p-5 rounded-[2rem] shadow-xl border border-gray-100 dark:border-slate-700">
        <div class="md:col-span-1">
            <label class="block text-[9px] font-black uppercase text-gray-400 mb-1 ml-2">Customer Name</label>
            <input type="text" name="customer" value="{{ request('customer') }}" placeholder="Search..." class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-logos-gold">
        </div>
        
        <div class="md:col-span-1">
            <label class="block text-[9px] font-black uppercase text-gray-400 mb-1 ml-2">Responsible Cashier</label>
            <select name="cashier_id" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-logos-gold">
                <option value="">All Staff</option>
                @foreach($cashiers as $cashier)
                    <option value="{{ $cashier->id }}" {{ request('cashier_id') == $cashier->id ? 'selected' : '' }}>{{ $cashier->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-1">
            <label class="block text-[9px] font-black uppercase text-gray-400 mb-1 ml-2">Specific Date</label>
            <input type="date" name="date" value="{{ request('date') }}" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-sm font-bold">
        </div>

        <div class="md:col-span-1">
            <label class="block text-[9px] font-black uppercase text-gray-400 mb-1 ml-2">Filter Month</label>
            <select name="month" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-sm font-bold">
                <option value="">Select Month</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="w-full bg-logos-blue text-white py-3 rounded-xl font-black uppercase text-xs hover:bg-logos-gold hover:text-logos-blue transition shadow-md">
                <i class="fas fa-filter mr-2"></i> Apply Audit
            </button>
        </div>
    </form>

    {{-- 3. THE VIEW TOGGLE (Optional but handy) --}}
    <div class="flex justify-end gap-2 px-2">
        <button onclick="switchView('grid')" id="btn-grid" class="p-2 rounded-lg bg-logos-blue text-white shadow-md"><i class="fas fa-th-large"></i></button>
        <button onclick="switchView('table')" id="btn-table" class="p-2 rounded-lg bg-white dark:bg-slate-800 text-gray-400 shadow-sm border border-gray-100 dark:border-slate-700"><i class="fas fa-list"></i></button>
    </div>

    {{-- 4. THE NICE CARDS (GRID VIEW) --}}
    <div id="grid-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($sales as $sale)
        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-gray-100 dark:border-slate-700 shadow-xl overflow-hidden hover:scale-[1.02] transition-transform duration-300 group">
            <div class="p-6 border-b border-gray-50 dark:border-slate-700 flex justify-between items-start bg-slate-50/50 dark:bg-slate-900/30">
                <div>
                    <span class="text-[9px] font-black text-logos-gold uppercase tracking-widest">Transaction #{{ $sale->id }}</span>
                    <h3 class="text-lg font-black text-logos-blue dark:text-white uppercase">{{ $sale->customer_name }}</h3>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase {{ $sale->status == 'CONFIRMED' ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600' }}">
                        {{ $sale->status }}
                    </span>
                    <p class="text-[10px] font-bold text-gray-400 mt-2 uppercase leading-none">{{ $sale->created_at->format('d M, Y') }}</p>
                </div>
            </div>

            <div class="p-6 space-y-3 max-h-48 overflow-y-auto">
                @foreach($sale->saleItems as $item)
                <div class="flex items-center justify-between bg-white dark:bg-slate-900 border border-gray-50 dark:border-slate-700 p-3 rounded-2xl shadow-sm">
                    <div class="flex flex-col">
                        <span class="text-xs font-black text-logos-blue dark:text-white uppercase">{{ $item->inventory->item_name }}</span>
                        <span class="text-[9px] font-bold text-gray-400">Size: {{ $item->inventory->size_label }}</span>
                    </div>
                    <span class="bg-logos-blue/5 text-logos-blue dark:text-logos-gold px-3 py-1 rounded-lg text-xs font-black">x{{ $item->quantity }}</span>
                </div>
                @endforeach
            </div>

            <div class="p-6 bg-logos-blue text-white flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-xs font-black border border-white/30">
                        {{ substr($sale->user->name ?? 'S', 0, 1) }}
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ $sale->user->name ?? 'System' }}</span>
                </div>
                <span class="text-xl font-black">KSh {{ number_format($sale->total_amount) }}</span>
            </div>
        </div>
        @empty
            <div class="col-span-full py-20 text-center bg-white dark:bg-slate-800 rounded-[3rem]">
                <i class="fas fa-search fa-3x text-gray-200 mb-4"></i>
                <p class="text-gray-400 font-bold">No deep-dive records match your filters.</p>
            </div>
        @endforelse
    </div>

    {{-- 5. THE ORIGINAL TABLE (TABLE VIEW - Hidden by default) --}}
    <div id="table-view" class="hidden bg-white dark:bg-slate-800 rounded-[2rem] shadow-xl border border-gray-100 dark:border-slate-700 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900 border-b border-gray-100 dark:border-slate-700">
                    <th class="p-5 text-[10px] font-black uppercase tracking-widest text-gray-400">ID</th>
                    <th class="p-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Customer</th>
                    <th class="p-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Cashier</th>
                    <th class="p-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Amount</th>
                    <th class="p-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                @foreach($sales as $sale)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition">
                    <td class="p-5 font-bold text-logos-blue dark:text-white">#{{ $sale->id }}</td>
                    <td class="p-5 font-black text-logos-blue dark:text-white uppercase text-sm">{{ $sale->customer_name }}</td>
                    <td class="p-5 text-xs font-bold text-gray-500">{{ $sale->user->name ?? 'System' }}</td>
                    <td class="p-5 font-black text-green-600">KSh {{ number_format($sale->total_amount) }}</td>
                    <td class="p-5 text-xs text-gray-400 font-bold">{{ $sale->created_at->format('M d, Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- 6. PAGINATION --}}
    <div class="mt-8">
        {{ $sales->appends(request()->query())->links() }}
    </div>
</div>

<script>
    function switchView(view) {
        const grid = document.getElementById('grid-view');
        const table = document.getElementById('table-view');
        const btnGrid = document.getElementById('btn-grid');
        const btnTable = document.getElementById('btn-table');

        if(view === 'grid') {
            grid.classList.remove('hidden');
            table.classList.add('hidden');
            btnGrid.classList.replace('bg-white', 'bg-logos-blue');
            btnGrid.classList.replace('text-gray-400', 'text-white');
            btnTable.classList.replace('bg-logos-blue', 'bg-white');
            btnTable.classList.replace('text-white', 'text-gray-400');
        } else {
            grid.classList.add('hidden');
            table.classList.remove('hidden');
            btnTable.classList.replace('bg-white', 'bg-logos-blue');
            btnTable.classList.replace('text-gray-400', 'text-white');
            btnGrid.classList.replace('bg-logos-blue', 'bg-white');
            btnGrid.classList.replace('text-white', 'text-gray-400');
        }
    }
</script>
@endsection