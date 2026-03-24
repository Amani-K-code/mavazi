@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-[1600px] mx-auto p-4 animate-in fade-in">
    {{-- 1. HEADER --}}
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black text-logos-blue dark:text-white tracking-tighter">Executive Intelligence</h2>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest">Mavazi Analytics Engine</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.report.download') }}" class="px-6 py-3 bg-logos-gold text-logos-blue rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg hover:scale-105 transition">
                <i class="fas fa-file-pdf mr-2"></i> Monthly Audit PDF
            </a>
        </div>
    </div>

    {{-- 2. QUICK ANALYTICS EXPORT (Moved to Top) --}}
    <div class="bg-logos-blue p-6 rounded-[2rem] flex items-center justify-between border-4 border-logos-gold/20 shadow-xl">
        <div>
            <h3 class="text-white font-black text-lg uppercase leading-none">Quick Analytics Export</h3>
            <p class="text-blue-200 text-xs mt-1 italic">Generate real-time system snapshots.</p>
        </div>
        <div class="flex gap-3">
            <a href="#" class="px-5 py-2.5 bg-white/10 text-white rounded-xl text-[10px] font-black uppercase border border-white/10 hover:bg-white/20 transition">Daily PDF</a>
            <a href="#" class="px-5 py-2.5 bg-white/10 text-white rounded-xl text-[10px] font-black uppercase border border-white/10 hover:bg-white/20 transition">Weekly PDF</a>
            <button class="px-5 py-2.5 bg-logos-gold text-logos-blue rounded-xl text-[10px] font-black uppercase shadow-lg">Stats Center</button>
        </div>
    </div>

    {{-- 3. KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 p-5 rounded-3xl border-b-4 border-green-500 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase">Daily Revenue</p>
            <h4 class="text-2xl font-black text-logos-blue dark:text-white">KSh {{ number_format($dailyTotal) }}</h4>
        </div>
        <div class="bg-white dark:bg-slate-800 p-5 rounded-3xl border-b-4 border-blue-500 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase">Weekly Performance</p>
            <h4 class="text-2xl font-black text-logos-blue dark:text-white">KSh {{ number_format($weeklyTotal) }}</h4>
        </div>
        <div class="bg-white dark:bg-slate-800 p-5 rounded-3xl border-b-4 border-red-500 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase">Critical Stock</p>
            <h4 class="text-2xl font-black text-red-500">{{ $lowStockCount }} Items</h4>
        </div>
        <div class="bg-white dark:bg-slate-800 p-5 rounded-3xl border-b-4 border-amber-500 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase">Active Bookings</p>
            <h4 class="text-2xl font-black text-logos-blue dark:text-white">{{ $activeBookings }}</h4>
        </div>
    </div>

    {{-- 4. THE 3 TOP SELLING ITEM CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($topItems as $item)
        <div class="bg-white dark:bg-slate-800 p-6 rounded-[2rem] border border-gray-100 dark:border-slate-700 shadow-xl relative overflow-hidden group hover:border-logos-gold transition">
            <div class="absolute -right-4 -top-4 w-20 h-20 bg-logos-gold/10 rounded-full flex items-center justify-center group-hover:scale-110 transition">
                <i class="fas fa-crown text-logos-gold text-2xl"></i>
            </div>
            <p class="text-[10px] font-black text-logos-gold uppercase tracking-tighter mb-1">Top Seller</p>
            <h3 class="text-xl font-black text-logos-blue dark:text-white uppercase truncate">{{ $item->item_name }}</h3>
            <div class="mt-4 flex justify-between items-end">
                <div>
                    <span class="text-3xl font-black text-logos-blue dark:text-white">{{ $item->total_sold }}</span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase ml-1">Units Sold</span>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-bold text-gray-400 uppercase">Revenue Contribution</p>
                    <p class="text-sm font-black text-green-500">KSh {{ number_format($item->revenue) }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- 5. ANALYTICS GRIDS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Historical Sales Bar Chart --}}
        <div class="bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] shadow-sm">
            <h3 class="font-black text-logos-blue dark:text-white uppercase text-xs mb-4">All-Time Revenue Velocity</h3>
            <div id="historicalSalesChart"></div>
        </div>

        {{-- Inventory Distribution Pie --}}
        <div class="bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] shadow-sm">
            <h3 class="font-black text-logos-blue dark:text-white uppercase text-xs mb-4">Stock Category Distribution</h3>
            <div id="inventoryPieChart"></div>
        </div>

        {{-- Ratings Breakdown Pie --}}
        <div class="bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] shadow-sm">
            <h3 class="font-black text-logos-blue dark:text-white uppercase text-xs mb-4">Customer Satisfaction (1-5 Stars)</h3>
            <div id="ratingsPieChart"></div>
        </div>

        {{-- Order Status Donut --}}
        <div class="bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] shadow-sm">
            <h3 class="font-black text-logos-blue dark:text-white uppercase text-xs mb-4">Order & Pipeline Status</h3>
            <div id="orderStatusDonut"></div>
        </div>

        {{-- Low Stock Horizontal Bar --}}
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 p-6 rounded-[2.5rem] shadow-sm">
            <h3 class="font-black text-red-500 uppercase text-xs mb-4">Critical Stock Levels (Remaining Units)</h3>
            <div id="lowStockBar"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    const dark = document.documentElement.classList.contains('dark');
    const labelColor = dark ? '#94a3b8' : '#64748b';

    // 1. Historical Sales
    new ApexCharts(document.querySelector("#historicalSalesChart"), {
        series: [{ name: 'Revenue', data: @json($historicalSales->pluck('total')) }],
        chart: { type: 'bar', height: 300, toolbar: {show:false} },
        colors: ['#003366'],
        plotOptions: { bar: { borderRadius: 10, columnWidth: '50%' } },
        xaxis: { categories: @json($historicalSales->pluck('date')), labels: {style: {colors: labelColor}} }
    }).render();

    // 2. Stock Category Line/Area Chart
    new ApexCharts(document.querySelector("#inventoryPieChart"), {
        series: [{
            name: 'Stock Quantity',
            data: @json($categoryDist->pluck('total'))
        }],
        chart: { 
            type: 'area', // 'area' looks very professional
            height: 300, 
            toolbar: {show:false},
            sparkline: {enabled: false}
        },
        colors: ['#FFD700'], // Gold color to match your theme
        stroke: { curve: 'smooth', width: 3 },
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1 }
        },
        xaxis: { 
            categories: @json($categoryDist->pluck('category')),
            labels: { style: { colors: labelColor } } 
        },
        yaxis: { labels: { style: { colors: labelColor } } },
        dataLabels: { enabled: false }
    }).render();

    // 3. Ratings Pie
    new ApexCharts(document.querySelector("#ratingsPieChart"), {
        series: @json($ratingData),
        labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
        chart: { type: 'donut', height: 300 },
        colors: ['#ef4444', '#f97316', '#eab308', '#84cc16', '#22c55e'],
        legend: { position: 'bottom', labels: {colors: labelColor} }
    }).render();

    // 4. Order Donut
    new ApexCharts(document.querySelector("#orderStatusDonut"), {
        series: @json(array_values($orderStatus)),
        labels: @json(array_keys($orderStatus)),
        chart: { type: 'donut', height: 300 },
        colors: ['#64748b', '#22c55e', '#f59e0b'],
        legend: { position: 'bottom', labels: {colors: labelColor} }
    }).render();

    // 5. Low Stock Horizontal
    new ApexCharts(document.querySelector("#lowStockBar"), {
        series: [{ name: 'Stock', data: @json($lowStockItems->pluck('stock_quantity')) }],
        chart: { type: 'bar', height: 300 },
        plotOptions: { bar: { horizontal: true, borderRadius: 5 } },
        colors: ['#ef4444'],
        xaxis: { categories: @json($lowStockItems->pluck('item_name')), labels: {style: {colors: labelColor}} }
    }).render();
</script>
@endsection