@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-xl border border-gray-100 dark:border-slate-700 overflow-hidden">
    <div class="p-8 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-logos-blue dark:text-white uppercase tracking-tighter">Staff Pulse & Ratings</h2>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Live Customer Feedback Log</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900/50">
                    <th class="p-6 text-[10px] font-black uppercase text-gray-400">Customer</th>
                    <th class="p-6 text-[10px] font-black uppercase text-gray-400">Rating</th>
                    <th class="p-6 text-[10px] font-black uppercase text-gray-400">Comment</th>
                    <th class="p-6 text-[10px] font-black uppercase text-gray-400">Staff Responsible</th>
                    <th class="p-6 text-[10px] font-black uppercase text-gray-400">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                @foreach($feedbacks as $f)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/20 transition">
                    <td class="p-6 font-black text-logos-blue dark:text-white text-sm uppercase">{{ $f->sale->customer_name ?? 'Valued Customer' }}</td>
                    <td class="p-6">
                        <div class="flex text-logos-gold text-xs">
                            @for($i=1; $i<=5; $i++)
                                <i class="{{ $i <= $f->rating ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                    </td>
                    <td class="p-6 text-xs text-gray-500 italic">"{{ $f->comment ?? 'No comment' }}"</td>
                    <td class="p-6">
                        <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg text-[10px] font-black uppercase">
                            {{ $f->sale->user->name ?? 'System' }}
                        </span>
                    </td>
                    <td class="p-6 text-[10px] font-bold text-gray-400">{{ $f->created_at->format('d M, Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection