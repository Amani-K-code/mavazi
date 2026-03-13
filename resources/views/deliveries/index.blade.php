@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f8fafc] dark:bg-[#020617]">
    {{-- High-End Mac Style Header Section --}}
    <div class="bg-[#0b1224] text-white px-10 py-6 flex justify-between items-center shadow-2xl border-b border-white/5">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-logos-gold rounded-xl flex items-center justify-center shadow-lg shadow-logos-gold/20">
                <i class="fas fa-box-open text-[#0b1224] text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl font-black tracking-tight leading-none">Logos Christian School</h1>
                <p class="text-logos-gold/60 text-[10px] font-bold uppercase tracking-[0.2em] mt-1">Delivery Records</p>
            </div>
        </div>
        <a href="{{ route('storekeeper.deliveries.create') }}" class="bg-logos-gold hover:bg-yellow-400 text-[#0b1224] px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest transition-all transform hover:scale-105 flex items-center shadow-xl shadow-logos-gold/10">
            <i class="fas fa-plus mr-2"></i> New Delivery
        </a>
    </div>

    <div class="p-10 max-w-7xl mx-auto">
        {{-- Centered Hero Section from Screenshot --}}
        <div class="text-center mb-16 space-y-4 animate-fade-in-up">
            <h2 class="text-5xl font-black text-[#0b1224] dark:text-white tracking-tighter">
                Streamline Your <span class="text-logos-gold italic">Delivery Records</span>
            </h2>
            <p class="text-slate-500 dark:text-slate-400 font-medium max-w-2xl mx-auto text-lg leading-relaxed">
                Track every package, manage deliveries efficiently, and maintain accurate records — all in one place.
            </p>
        </div>

        {{-- Feature Cards (The 3 Cards from your Screenshot) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-white/5 text-center group hover:-translate-y-2 transition-all duration-500">
                <div class="w-16 h-16 bg-logos-gold/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-clipboard-list text-logos-gold text-2xl"></i>
                </div>
                <h4 class="text-xl font-black text-[#0b1224] dark:text-white uppercase mb-3">Create Records</h4>
                <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed font-medium">Log new deliveries with recipient, item details, and status tracking.</p>
            </div>

            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-white/5 text-center group hover:-translate-y-2 transition-all duration-500">
                <div class="w-16 h-16 bg-blue-500/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-eye text-blue-500 text-2xl"></i>
                </div>
                <h4 class="text-xl font-black text-[#0b1224] dark:text-white uppercase mb-3">View & Monitor</h4>
                <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed font-medium">See real-time status updates and delivery history at a glance.</p>
            </div>

            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-white/5 text-center group hover:-translate-y-2 transition-all duration-500">
                <div class="w-16 h-16 bg-green-500/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-shield-alt text-green-500 text-2xl"></i>
                </div>
                <h4 class="text-xl font-black text-[#0b1224] dark:text-white uppercase mb-3">Accurate Records</h4>
                <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed font-medium">Maintain proper documentation for accountability and auditing.</p>
            </div>
        </div>

        {{-- Log Table Section --}}
        <div class="bg-white dark:bg-slate-900 rounded-[3rem] shadow-2xl shadow-slate-200/60 dark:shadow-none border border-slate-100 dark:border-white/5 overflow-hidden">
            <div class="px-10 py-8 border-b border-slate-50 dark:border-white/5 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/20">
                <div>
                    <h3 class="text-2xl font-black text-[#0b1224] dark:text-white tracking-tight">Recent Delivery Logs</h3>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Audit Trail & History</p>
                </div>
                <div class="flex space-x-2">
                    <div class="bg-yellow-500/10 text-yellow-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase">Pending: {{ $deliveries->where('status', 'PENDING')->count() }}</div>
                    <div class="bg-green-500/10 text-green-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase">Confirmed: {{ $deliveries->where('status', 'CONFIRMED')->count() }}</div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            <th class="px-10 py-6 text-left">Reference</th>
                            <th class="px-10 py-6 text-left">Delivery Date</th>
                            <th class="px-10 py-6 text-left">Total Value</th>
                            <th class="px-10 py-6 text-left">Status</th>
                            <th class="px-10 py-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                        @forelse($deliveries as $delivery)
                        <tr class="group hover:bg-slate-50/80 dark:hover:bg-white/5 transition-all">
                            <td class="px-10 py-6">
                                <span class="font-black text-[#0b1224] dark:text-white">#DEL-{{ str_pad($delivery->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-10 py-6 text-slate-500 dark:text-slate-400 font-bold text-sm">
                                {{ \Carbon\Carbon::parse($delivery->delivery_date)->format('M d, Y') }}
                            </td>
                            <td class="px-10 py-6">
                                <span class="text-logos-blue dark:text-logos-gold font-black">Ksh {{ number_format($delivery->total_invoice_amount, 2) }}</span>
                            </td>
                            <td class="px-10 py-6">
                                @if($delivery->status == 'CONFIRMED')
                                    <span class="flex items-center text-green-600 text-[10px] font-black uppercase tracking-widest">
                                        <span class="w-2 h-2 bg-green-600 rounded-full mr-2 animate-pulse"></span> Confirmed
                                    </span>
                                @else
                                    <span class="flex items-center text-yellow-600 text-[10px] font-black uppercase tracking-widest">
                                        <span class="w-2 h-2 bg-yellow-600 rounded-full mr-2"></span> Awaiting Review
                                    </span>
                                @endif
                            </td>
                            <td class="px-10 py-6 text-right">
                                <a href="{{ route('storekeeper.deliveries.pdf', $delivery->id) }}" class="bg-slate-100 dark:bg-white/10 p-3 rounded-xl text-slate-600 dark:text-white hover:bg-logos-gold hover:text-white transition-all inline-flex items-center">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-10 py-20 text-center">
                                <div class="opacity-20 mb-4">
                                    <i class="fas fa-inbox text-6xl"></i>
                                </div>
                                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">No delivery logs found in the registry</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
</style>
@endsection