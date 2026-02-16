@extends('layouts.app')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-black text-logos-blue uppercase tracking-tight">Uniform Inventory</h2>
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Live Stock Levels & Health Status</p>
        </div>

        <form id="inventorySearchForm" action="{{ route('cashier.inventory') }}" method="GET" class="relative w-full md:w-96">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" name="search" id="inventorySearchInput" 
                placeholder="Search item or size..." 
                value="{{ request('search') }}"
                class="w-full pl-12 pr-4 py-3 bg-white border-none rounded-2xl shadow-sm focus:ring-2 focus:ring-logos-gold text-sm font-medium"
                oninput="debounceInventorySearch()">
        </form>
    </div>

    {{-- Styled Table Container --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Item Detail</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Category</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Size</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Price</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Stock Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($items as $item)
                @php
                    // --- YOUR EXACT ICON LOGIC ---
                    $name = strtolower($item->item_name);
                    $image = null;
                    $icon = 'fa-shirt'; 
                    $customSvg = null;

                    if (str_contains($name, 'peter pan')) { $image = 'peter_pan.png'; }
                    elseif (str_contains($name, 'sweater')) { $image = 'sweater.png'; }
                    elseif (str_contains($name, 'fleece') || str_contains($name, 'jacket')) { $image = 'fleece-hoodie.png'; }
                    elseif (str_contains($name, 'track suit')) { $image = 'Tracksuit.png'; }
                    elseif (str_contains($name, 'trunks')) { $image = 'swimming_trunk_boys.png'; }
                    elseif (str_contains($name, 'swim suit') || str_contains($name, 'costume')) { $image = 'ladies_swimsuit.png'; }
                    elseif (str_contains($name, 'short')) { $image = 'Games_shorts.png'; }
                    elseif (str_contains($name, 'yellow pin stripped') || str_contains($name, 'sky blue')) { $image = 'Tie_collar.png'; }
                    elseif (str_contains($name, 'trouser')) { $customSvg = '<path d="M6 2h12l3 20h-7v-8h-4v8H3L6 2z" />'; }
                    elseif (str_contains($name, 'skort') || str_contains($name, 'skirt')) { $customSvg = '<path d="M8 2h8l5 20H3L8 2z" />'; }
                    elseif (str_contains($name, 'tie')) { $customSvg = '<path d="M6 2l4 4-3 11 5 5 5-5-3-11 4-4H6z" />'; }
                    elseif (str_contains($name, 'blazer')) { $icon = 'fa-user-tie'; }
                    elseif (str_contains($name, 'socks') || str_contains($name, 'stocking')) { $icon = 'fa-socks'; }

                    // --- HEALTH STATUS LOGIC ---
                    $qty = $item->stock_quantity;
                    if ($qty <= 0) {
                        $statusColor = 'text-gray-400';
                        $statusIcon = '<i class="fas fa-times-circle text-red-500"></i>';
                        $statusText = 'OUT OF STOCK';
                        $bg = 'bg-gray-50';
                    } elseif ($qty <= 5) {
                        $statusColor = 'text-red-600';
                        $statusIcon = '<span class="flex h-2 w-2 rounded-full bg-red-600 mr-2"></span>';
                        $statusText = 'CRITICAL';
                        $bg = 'bg-red-50';
                    } elseif ($qty <= 9) {
                        $statusColor = 'text-orange-500';
                        $statusIcon = '<span class="flex h-2 w-2 rounded-full bg-orange-500 mr-2"></span>';
                        $statusText = 'WARNING';
                        $bg = 'bg-orange-50';
                    } else {
                        $statusColor = 'text-green-600';
                        $statusIcon = '<span class="flex h-2 w-2 rounded-full bg-green-500 mr-2"></span>';
                        $statusText = 'HEALTHY';
                        $bg = 'bg-green-50';
                    }
                @endphp
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-logos-blue overflow-hidden flex-shrink-0 border border-gray-100">
                                @if($image)
                                    <img src="{{ asset('images/uniforms/' . $image) }}" alt="" class="w-7 h-7 object-contain">
                                @elseif($customSvg)
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">{!! $customSvg !!}</svg>
                                @else
                                    <i class="fas {{ $icon }} text-lg"></i>
                                @endif
                            </div>
                            <div class="text-xs font-bold text-logos-blue uppercase">{{ $item->item_name }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        {{ $item->category }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-xs font-medium text-gray-600 bg-gray-100 px-2 py-1 rounded">{{ $item->size_label }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs font-black text-logos-blue">KES {{ number_format($item->price) }}</div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end">
                            <div class="flex flex-col items-end">
                                <span class="text-sm font-black text-logos-blue">{{ $qty }} Items</span>
                                <div class="flex items-center mt-1">
                                    {!! $statusIcon !!}
                                    <span class="text-[9px] font-black {{ $statusColor }} tracking-tighter uppercase">{{ $statusText }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic text-sm">No inventory records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    let inventoryTimeout = null;
    function debounceInventorySearch() {
        clearTimeout(inventoryTimeout);
        inventoryTimeout = setTimeout(function () {
            document.getElementById('inventorySearchForm').submit();
        }, 500);
    }

    window.onload = function() {
        const input = document.getElementById('inventorySearchInput');
        if (input && input.value !== "") {
            input.focus();
            const val = input.value;
            input.value = "";
            input.value = val;
        }
    }
</script>
@endsection