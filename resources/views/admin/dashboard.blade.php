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

@endsection