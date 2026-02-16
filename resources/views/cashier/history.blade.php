@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-logos-blue uppercase tracking-tight">Transaction History</h2>
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Recent Sales & Receipts</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Receipt / Date</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Parent & Student</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Items Purchased</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Total</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($sales as $sale)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4">
                        <div class="text-xs font-black text-logos-blue">{{ $sale->receipt_no }}</div>
                        <div class="text-[10px] text-gray-400">{{ $sale->created_at->format('d M, Y H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs font-bold text-gray-700">{{ $sale->customer_name }}</div>
                        <div class="text-[10px] text-gray-400">Stud: {{ $sale->child_name }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-[10px] text-gray-600 leading-relaxed">
                            @foreach($sale->items as $item)
                                {{ $item->quantity }}x {{ $item->inventory->item_name }} ({{ $item->inventory->size_label }})<br>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 font-black text-logos-blue text-xs">
                        KES {{ number_format($sale->total_amount) }}
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('sales.download', $sale->id) }}" class="inline-flex items-center px-3 py-1 bg-red-50 text-red-600 rounded-lg text-[10px] font-black uppercase hover:bg-red-600 hover:text-white transition">
                            <i class="fas fa-file-pdf mr-1"></i> Receipt
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic text-sm">No transactions recorded yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection