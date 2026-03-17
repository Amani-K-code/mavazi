@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 animate-in slide-in-from-bottom-6 duration-700">
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-black text-logos-blue dark:text-white italic tracking-tighter">Staff Command</h2>
        <button class="btn-gold"><i class="fas fa-plus mr-2"></i> Register New User</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($users as $user)
        <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-logos-blue/5 rounded-full group-hover:bg-logos-gold/10 transition duration-500"></div>
            
            <div class="flex items-center gap-5 mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-logos-blue to-slate-900 rounded-2xl flex items-center justify-center font-black text-2xl text-logos-gold shadow-2xl">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="font-black text-xl text-logos-blue dark:text-white">{{ $user->name }}</h3>
                    <p class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em]">{{ $user->role }}</p>
                </div>
            </div>

            <div class="space-y-3 mb-8">
                <div class="flex justify-between text-[11px] font-bold">
                    <span class="text-gray-400 uppercase">System Alias</span>
                    <span class="text-logos-blue dark:text-white font-black tracking-widest">{{ $user->alias }}</span>
                </div>
                <div class="flex justify-between text-[11px] font-bold">
                    <span class="text-gray-400 uppercase">Last Login</span>
                    <span class="text-gray-500">2 hours ago</span>
                </div>
            </div>

            <div class="pt-6 border-t border-white/5 flex items-center justify-between">
                <div>
                    <p class="text-[9px] font-black uppercase text-gray-400">Account Control</p>
                    <span class="text-xs font-black {{ $user->is_active ? 'text-green-500' : 'text-red-500' }} uppercase">
                        {{ $user->is_active ? '● AUTHORIZED' : '○ SUSPENDED' }}
                    </span>
                </div>
                
                <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-5 py-2 rounded-xl border border-white/10 font-black text-[10px] uppercase hover:bg-white/5 transition active:scale-95">
                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection