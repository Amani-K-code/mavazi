@extends('welcome')

@section('content')

@php 
    // This preserves the session data so it's still there when the PDF button is clicked
    session()->keep(['raw_password', 'new_alias', 'registered_user_id']); 
@endphp

<div class="mac-glass w-full max-w-md p-8 text-gray-800 text-center">
    <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
        <i class="fas fa-check-circle text-4xl"></i>
    </div>
    <h2 class="text-2xl font-bold text-logos-blue mb-2">Registration Successful!</h2>
    <p class="text-gray-500 text-sm mb-6">Please note your login credentials below:</p>

    <div class="bg-slate-50 rounded-2xl p-6 mb-6 border border-dashed border-gray-300">
        <div class="mb-4">
            <p class="text-[10px] uppercase font-bold text-gray-400">Your Staff ID</p>
            <p class="text-3xl font-black text-logos-blue tracking-widest">{{ session('new_alias') }}</p>
        </div>
        <div>
            <p class="text-[10px] uppercase font-bold text-gray-400">Password</p>
            <p class="text-lg font-mono font-bold">{{ session('raw_password') }}</p>
        </div>
    </div>

    <div class="mt-8 space-y-3">
        {{-- Safety Check: Only show the link if the session ID exists --}}
        @if(session('registered_user_id'))
            <a href="{{ route('admin.cashier.download-credentials', ['id' => session('registered_user_id')]) }}" 
                class="flex items-center justify-center gap-2 w-full bg-red-600 text-white font-bold py-3 rounded-xl hover:bg-red-700 transition">
                    <i class="fas fa-file-pdf"></i>
                    Download Credentials PDF
            </a>
        @else
            <div class="p-3 bg-amber-50 text-amber-700 rounded-xl text-xs border border-amber-200">
                <i class="fas fa-exclamation-triangle mr-1"></i> 
                Session expired. Please log in or contact Admin if you missed your ID.
            </div>
        @endif

        <a href="{{ route('login') }}" class="block text-sm text-gray-500 hover:underline">
            Skip to Login
        </a>
    </div>

</div>
@endsection