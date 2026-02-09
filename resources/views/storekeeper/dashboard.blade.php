@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <h2 class="text-2xl font-black text-logos-blue uppercase">Inventory Overview</h2>
        <p class="text-gray-500">Managing stock for Logos Christian School.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-2xl border-l-4 border-logos-blue shadow-sm">
            <p class="text-xs font-bold text-gray-400 uppercase">Total Items</p>
            <p class="text-2xl font-black text-logos-blue">74</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border-l-4 border-red-500 shadow-sm">
            <p class="text-xs font-bold text-gray-400 uppercase">Low Stock Alerts</p>
            <p class="text-2xl font-black text-red-500">12</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-sm font-bold text-gray-400 uppercase mb-4">Stock Management Quick Links</h3>
        <div class="grid grid-cols-2 gap-4">
            <button class="flex items-center p-4 bg-slate-50 rounded-2xl hover:bg-logos-gold transition group">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mr-4 shadow-sm">
                    <i class="fas fa-plus text-logos-blue"></i>
                </div>
                <span class="font-bold text-logos-blue">Restock Item</span>
            </button>
            <button class="flex items-center p-4 bg-slate-50 rounded-2xl hover:bg-logos-gold transition group">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mr-4 shadow-sm">
                    <i class="fas fa-search text-logos-blue"></i>
                </div>
                <span class="font-bold text-logos-blue">Physical Count Audit</span>
            </button>
        </div>
    </div>
</div>
@endsection