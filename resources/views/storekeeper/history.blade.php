@extends('layouts.app')

@section('content')

<h2 class="text-2xl font-black text-logos-blue dark:text-white uppercase mb-6">Restock History</h2>
<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-slate-50 dark:bg-slate-900/50">
            <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                <th class="px-6 py-4">Date</th>
                <th class="px-6 py-4">Item</th>
                <th class="px-6 py-4">Added</th>
                <th class="px-6 py-4">Final Stock</th>
                <th class="px-6 py-4">Storekeeper</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
            @foreach($history as $log)
            <tr class="text-sm dark:text-gray-300">
                <td class="px-6 py-4">{{ $log->created_at->format('d M, H:i') }}</td>
                <td class="px-6 py-4 font-bold">{{ $log->inventory->item_name }}</td>
                <td class="px-6 py-4 text-green-500 font-bold">+{{ $log->quantity_change }}</td>
                <td class="px-6 py-4">{{ $log->quantity_after }}</td>
                <td class="px-6 py-4">{{ $log->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
