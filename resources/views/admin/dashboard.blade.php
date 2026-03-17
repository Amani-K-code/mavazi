@extends('layouts.app')

@section('content')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.3s ease;
    }
    .dark .glass-card {
        background: rgba(15, 23, 42, 0.4);
    }
    .glass-card:hover {
        transform: translateY(-5px);
        border-color: #FFD700;
        box-shadow: 0 10px 30px -10px rgba(255, 215, 0, 0.2);
    }
    .stat-value {
        font-family: 'Inter', sans-serif;
        letter-spacing: -0.02em;
    }
</style>

<div class="space-y-8 animate-in fade-in duration-500">
    {{-- 1. HEADER SECTION --}}
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black text-logos-blue dark:text-white tracking-tight">Executive Overview</h2>
            <p class="text-gray-500 dark:text-slate-400 font-medium">Real-time financial and inventory intelligence</p>
        </div>
        <div class="flex space-x-3">
            <button class="px-4 py-2 glass-card rounded-xl text-xs font-bold uppercase tracking-widest text-logos-blue dark:text-white">
                <i class="fas fa-calendar-alt mr-2 text-logos-gold"></i> This Month
            </button>
            <a href="{{ route('admin.report.download') }}" class="px-4 py-2 bg-logos-gold text-logos-blue rounded-xl text-xs font-black uppercase tracking-widest shadow-lg hover:scale-105 transition">
                <i class="fas fa-file-pdf mr-2"></i> Full Report
            </a>
        </div>
    </div>

    {{-- 2. PRIMARY KPI METRICS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass-card p-6 rounded-3xl relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-logos-gold/10 rounded-full blur-2xl group-hover:bg-logos-gold/20 transition"></div>
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Daily Revenue</p>
            <div class="flex items-baseline space-x-1">
                <span class="text-lg font-bold text-logos-gold">KSh</span>
                <span class="text-3xl font-black stat-value text-logos-blue dark:text-white">{{ number_format($dailyTotal ?? 0) }}</span>
            </div>
            <div class="mt-4 flex items-center text-[10px] font-bold text-green-500 bg-green-500/10 w-fit px-2 py-1 rounded-lg">
                <i class="fas fa-arrow-up mr-1"></i> Live Stats
            </div>
        </div>

        <div class="glass-card p-6 rounded-3xl group">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Weekly Performance</p>
            <div class="flex items-baseline space-x-1">
                <span class="text-lg font-bold text-logos-gold">KSh</span>
                <span class="text-3xl font-black stat-value text-logos-blue dark:text-white">{{ number_format($weeklyTotal ?? 0) }}</span>
            </div>
            <div class="mt-4 flex items-center text-[10px] font-bold text-amber-500 bg-amber-500/10 w-fit px-2 py-1 rounded-lg">
                <i class="fas fa-clock mr-1"></i> Current Week
            </div>
        </div>

        <div class="glass-card p-6 rounded-3xl">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Critical Stock</p>
            <div class="flex items-baseline space-x-2">
                <span class="text-3xl font-black stat-value text-logos-blue dark:text-white">{{ $lowStockCount ?? 0 }}</span>
                <span class="text-xs font-bold text-red-500 uppercase tracking-widest">Items Low</span>
            </div>
            <a href="{{ route('notifications.index') }}" class="mt-4 block text-[10px] font-black text-logos-gold hover:underline uppercase tracking-widest">
                Resolve Now <i class="fas fa-chevron-right ml-1"></i>
            </a>
        </div>

        <div class="glass-card p-6 rounded-3xl">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Booked items</p>
            <div class="flex items-baseline space-x-2">
                <span class="text-3xl font-black stat-value text-logos-blue dark:text-white">{{ $activeBookings ?? 0 }}</span>
                <span class="text-xs font-bold text-blue-500 uppercase tracking-widest">Awaiting Pickup</span>
            </div>
            <div class="mt-4 h-1.5 w-full bg-gray-100 dark:bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-logos-gold" style="width: 45%"></div>
            </div>
        </div>
    </div>

    {{-- 3. BEST SELLERS SECTION (Moved up for visual impact) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($topItems->take(4) as $item)
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-5 rounded-xl shadow-lg text-white relative overflow-hidden">
            <div class="relative z-10">
                <span class="text-xs font-bold bg-white/20 px-2 py-1 rounded uppercase">Best Seller</span>
                <h4 class="text-xl font-bold mt-2 truncate">{{ $item->item_name }}</h4>
                <p class="opacity-80 text-sm">{{ $item->category }}</p>
                <div class="mt-4 flex justify-between items-end">
                    <div>
                        <span class="text-2xl font-black">{{ $item->total_sold }}</span>
                        <span class="text-xs ml-1 opacity-80">Units Sold</span>
                    </div>
                    <div class="text-right">
                        <p class="text-xs opacity-80">Revenue</p>
                        <p class="font-bold text-yellow-300 text-sm">KSh {{ number_format($item->revenue) }}</p>
                    </div>
                </div>
            </div>
            <i class="fas fa-tshirt absolute -bottom-4 -right-4 text-8xl opacity-10"></i>
        </div>
        @endforeach
    </div>

    {{-- 4. ANALYTICS & RECENT TRANSACTIONS --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 glass-card p-8 rounded-[2.5rem]">
            <div class="flex justify-between items-center mb-8">
                <h3 class="font-black text-logos-blue dark:text-white uppercase tracking-widest text-sm">Revenue Growth</h3>
                <div class="flex items-center text-[10px] font-bold text-gray-400">
                    <span class="w-2 h-2 rounded-full bg-logos-gold mr-2"></span> Last 7 Days
                </div>
            </div>
            <div class="h-80 w-full">
                <canvas id="mainSalesChart"></canvas>
            </div>
        </div>

        <div class="glass-card p-8 rounded-[2.5rem]">
            <h3 class="font-black text-logos-blue dark:text-white uppercase tracking-widest text-sm mb-6">Recent Sales</h3>
            <div class="space-y-6">
                @forelse($recentActivities ?? [] as $sale)
                <div class="flex items-center justify-between group">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-logos-blue dark:text-logos-gold font-bold transition group-hover:scale-110">
                            <i class="fas fa-shopping-cart text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black text-logos-blue dark:text-white">{{ $sale->receipt_no }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $sale->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <p class="text-sm font-black text-logos-blue dark:text-white">KSh {{ number_format($sale->total_amount) }}</p>
                </div>
                @empty
                <p class="text-gray-400 text-xs italic">No transactions today.</p>
                @endforelse
            </div>
            <a href="{{ route('admin.transactions.index') }}" class="mt-8 block w-full py-3 text-center border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-logos-gold hover:border-logos-gold transition">
                View All Transactions
            </a>
        </div>
    </div>

    {{-- 5. CASHIER RANKING & FEEDBACK --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-800/50 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700">
            <h3 class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider mb-4">Top 3 Cashiers</h3>
            <div class="space-y-4">
                @foreach($topCashiers as $index => $cashier)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800 rounded-lg border-l-4 {{ $index == 0 ? 'border-yellow-400' : 'border-blue-400' }}">
                    <div class="flex items-center gap-3">
                        <span class="font-bold text-lg text-gray-400">#{{ $index + 1 }}</span>
                        <div>
                            <p class="font-semibold text-xs text-gray-800 dark:text-white">{{ $cashier->name }}</p>
                            <p class="text-[10px] text-gray-500">{{ $cashier->sales_count }} Sales Today</p>
                        </div>
                    </div>
                    <span class="font-bold text-xs text-blue-600 dark:text-logos-gold">KSh {{ number_format($cashier->total_revenue) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="lg:col-span-2 bg-white dark:bg-slate-800/50 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700">
            <h3 class="text-gray-500 dark:text-gray-400 text-xs font-bold mb-4 uppercase tracking-wider">Recent Customer Feedback</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($ratings->take(2) as $rating)
                <div class="p-4 border border-gray-50 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 rounded-xl">
                    <div class="flex text-yellow-400 mb-2">
                        @for($i=0; $i<5; $i++)
                            <i class="{{ $i < $rating->stars ? 'fas' : 'far' }} fa-star text-[10px]"></i>
                        @endfor
                    </div>
                    <p class="text-xs italic text-gray-600 dark:text-gray-300">"{{ $rating->comment }}"</p>
                    <p class="text-[10px] font-bold text-gray-400 mt-2">— {{ $rating->customer_name }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 6. SECONDARY CHARTS & ACTIVITY PULSE --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 glass-card p-8 rounded-[2.5rem]">
            <div class="flex justify-between items-center mb-6">
                <h4 class="font-black text-xs uppercase tracking-widest text-logos-gold">Intraday Sales Velocity</h4>
                <span class="px-3 py-1 bg-green-500/10 text-green-500 rounded-full text-[9px] font-black tracking-tighter animate-pulse">LIVE UPDATING</span>
            </div>
            <div class="h-64">
                <canvas id="hourlySalesChart"></canvas>
            </div>
        </div>

        <div class="glass-card p-8 rounded-[2.5rem]">
            <h4 class="font-black text-xs uppercase tracking-widest mb-6">Staff Activity Pulse</h4>
            <div class="relative">
                <div class="space-y-8 relative">
                    @foreach($recentActivities->take(3) as $activity)
                    <div class="flex items-start gap-4">
                        <div class="w-3 h-3 rounded-full bg-logos-gold border-2 border-slate-900 z-10 mt-1"></div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <p class="text-[11px] font-black text-logos-blue dark:text-white uppercase">{{ $activity->user->name ?? 'Staff' }}</p>
                                <span class="text-[9px] font-bold text-gray-500">{{ $activity->created_at->format('H:i') }}</span>
                            </div>
                            <p class="text-xs text-gray-400 italic">Processed Receipt #{{ $activity->receipt_no }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- 7. REPORT GENERATION CENTER (Moved to bottom as an utility) --}}
    <div class="bg-blue-50 dark:bg-slate-800/50 border border-blue-100 dark:border-slate-700 p-6 rounded-3xl flex flex-wrap items-center justify-between gap-4">
        <div>
            <h3 class="text-blue-900 dark:text-logos-gold font-bold">Report Generation Center</h3>
            <p class="text-blue-700 dark:text-gray-400 text-sm">Download comprehensive analytics in PDF format.</p>
        </div>
        <div class="flex gap-2">
            <a href="/admin/reports/daily" class="px-4 py-2 bg-white dark:bg-slate-900 text-blue-600 rounded-lg text-sm font-bold shadow-sm hover:bg-blue-600 hover:text-white transition">Daily</a>
            <a href="/admin/reports/weekly" class="px-4 py-2 bg-white dark:bg-slate-900 text-blue-600 rounded-lg text-sm font-bold shadow-sm hover:bg-blue-600 hover:text-white transition">Weekly</a>
            <a href="/admin/reports/monthly" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold shadow-sm hover:bg-blue-700 transition">Monthly</a>
            <button onclick="downloadChartPDF()" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-bold shadow-sm hover:bg-black transition">
                <i class="fas fa-file-pdf mr-2"></i>Stats PDF
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('mainSalesChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(255, 215, 0, 0.3)');
        gradient.addColorStop(1, 'rgba(255, 215, 0, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesLabels ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) !!},
                datasets: [{
                    label: 'Revenue',
                    data: {!! json_encode($salesData ?? [0,0,0,0,0,0,0]) !!},
                    borderColor: '#FFD700',
                    borderWidth: 4,
                    tension: 0.4,
                    fill: true,
                    backgroundColor: gradient
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        const hourlyCtx = document.getElementById('hourlySalesChart').getContext('2d');
        new Chart(hourlyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($hourlySales->pluck('hour')->map(fn($h) => $h.':00')) !!},
                datasets: [{
                    label: 'Revenue',
                    data: {!! json_encode($hourlySales->pluck('total')) !!},
                    backgroundColor: '#FFD700',
                    borderRadius: 8
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });
    });
</script>
@endsection