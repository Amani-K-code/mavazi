<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach($sales as $sale)
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h4 class="font-black text-logos-blue uppercase">{{ $sale->customer_name }}</h4>
                <p class="text-[10px] text-gray-400 font-bold">CASHIER: {{ $sale->user->name }}</p>
            </div>
            <span class="bg-green-100 text-green-600 px-3 py-1 rounded-lg text-[10px] font-black">KSh {{ number_format($sale->total_amount) }}</span>
        </div>
        <div class="space-y-2">
            @foreach($sale->saleItems as $item)
            <div class="flex justify-between text-xs bg-slate-50 p-2 rounded-lg">
                <span class="font-bold">{{ $item->inventory->item_name }} ({{ $item->inventory->size_label }})</span>
                <span class="text-logos-blue font-black">x{{ $item->quantity }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>