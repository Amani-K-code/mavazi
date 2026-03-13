@extends ('layouts.app')

@section('content')
    <div class="mac-card p-8 shadow-lg">
    <h2 class="text-3xl font-bold text-logos-blue mb-4">Welcome back, Admin!</h2>
    <p class="text-gray-600 mb-6">Here is an overview of Logos Christian School Uniform Store activity.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 bg-blue-50 rounded-xl border border-blue-100">
            <h3 class="text-blue-800 font-semibold">Total Stock</h3>
            <p class="text-4xl font-black text-blue-900">74 Items</p>
        </div>
        <div class="p-6 bg-yellow-50 rounded-xl border border-yellow-100">
            <h3 class="text-yellow-800 font-semibold">Today's Sales</h3>
            <p class="text-4xl font-black text-yellow-900">KES 0.00</p>
        </div>
        <div class="p-6 bg-green-50 rounded-xl border border-green-100">
            <h3 class="text-green-800 font-semibold">Staff Online</h3>
            <p class="text-4xl font-black text-green-900">1</p>
        </div>
    </div>
</div>


{{-- Pending Deliveries Section for Admin --}}
<div class="mt-12 bg-white dark:bg-slate-800 rounded-[2rem] shadow-xl overflow-hidden border border-gray-100 dark:border-slate-700">
    <div class="bg-logos-blue p-6 flex justify-between items-center">
        <h3 class="text-white font-black uppercase tracking-widest text-lg">Pending Stock Deliveries</h3>
        <span class="bg-logos-gold text-logos-blue px-3 py-1 rounded-full text-xs font-black">
            Action Required
        </span>
    </div>

    <div class="p-0">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50">
                        <th class="p-4 text-[10px] font-black uppercase text-gray-400">Date</th>
                        <th class="p-4 text-[10px] font-black uppercase text-gray-400">Storekeeper</th>
                        <th class="p-4 text-[10px] font-black uppercase text-gray-400">Items</th>
                        <th class="p-4 text-[10px] font-black uppercase text-gray-400">Amount</th>
                        <th class="p-4 text-[10px] font-black uppercase text-gray-400 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\Deliveries::where('status', 'PENDING')->with('items', 'user')->get() as $delivery)
                    <tr class="border-b border-gray-50 dark:border-slate-700 hover:bg-slate-50/50 transition-colors">
                        <td class="p-4 font-bold text-sm text-logos-blue">
                            {{ \Carbon\Carbon::parse($delivery->delivery_date)->format('d M, Y') }}
                        </td>
                        <td class="p-4 text-sm font-medium">{{ $delivery->user->name }}</td>
                        <td class="p-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($delivery->items as $item)
                                    <span class="bg-blue-50 text-blue-700 text-[10px] px-2 py-0.5 rounded-md font-bold border border-blue-100">
                                        {{ $item->item_name }} ({{ $item->quantity }})
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="p-4 font-black text-sm text-gray-700">Ksh {{ number_format($delivery->total_invoice_amount, 2) }}</td>
                        <td class="p-4 text-right">
                            <form action="{{ route('admin.deliveries.confirm', $delivery->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl text-xs font-black uppercase shadow-lg shadow-green-200 transition-all active:scale-95">
                                    <i class="fas fa-check-circle mr-1"></i> Approve & Sync Stock
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-gray-400 font-bold italic">
                            No pending deliveries found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection