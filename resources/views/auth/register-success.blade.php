@extends('welcome')

@section('content')
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

    <a href="{{ route('login') }}" class="block w-full py-3 text-white font-bold rounded-lg text-center hover:brightness-110 transition-all shadow-md" style="background-color: #003366;">
        Proceed to Login
    </a>
</div>
@endsection