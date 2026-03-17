@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-logos-blue">Transaction Oversight</h1>
            <p class="text-sm text-gray-500 font-medium">Review and audit all system sales</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.report.download') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl shadow-sm hover:bg-gray-50 transition font-bold text-xs uppercase tracking-widest">
                <i class="fas fa-file-pdf mr-2 text-red-500"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-gray-100">
                    <th class="p-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Transaction ID</th>
                    <th class="p-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Cashier</th>
                    <th class="p-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Amount</th>
                    <th class="p-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Status</th>
                    <th class="p-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($transactions as $tx)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="p-4 font-bold text-logos-blue">#{{ $tx->id }}</td>
                    <td class="p-4 text-sm font-medium text-gray-600">{{ $tx->user->name ?? 'System' }}</td>
                    <td class="p-4 font-black text-logos-blue">KSh {{ number_format($tx->total_amount, 2) }}</td>
                    <td class="p-4">
                        <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase {{ $tx->status == 'CONFIRMED' ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600' }}">
                            {{ $tx->status }}
                        </span>
                    </td>
                    <td class="p-4 text-xs text-gray-400 font-bold">{{ $tx->created_at->format('M d, Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-12 text-center text-gray-400 font-medium italic">No transactions found today.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection