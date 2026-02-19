@extends('layouts.app')

@section('content')
<div class="flex h-[85vh] antialiased text-gray-800 rounded-lg overflow-hidden shadow-2xl border border-gray-200 dark:border-slate-700">
    
    <div class="flex flex-col w-80 bg-white dark:bg-slate-900 border-r dark:border-slate-800">
        <div class="p-6 border-b dark:border-slate-800 bg-[#0f172a] text-white">
            <h2 class="text-xl font-bold tracking-tight">Messages</h2>
            <p class="text-xs text-yellow-400 font-semibold uppercase tracking-wider">Logos Christian School</p>
        </div>
        
        <div class="flex-grow overflow-y-auto">
            <a href="{{ route('notifications.index') }}" 
               class="flex items-center px-6 py-4 border-b dark:border-slate-800 transition-colors {{ !$selectedUser ? 'bg-yellow-50 dark:bg-slate-800 border-l-4 border-yellow-500' : 'hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                <div class="flex items-center justify-center h-10 w-10 rounded-full bg-yellow-500 text-white font-bold">L</div>
                <div class="ml-4">
                    <div class="font-bold text-sm dark:text-white">Main Communication Hub</div>
                    <div class="text-xs text-gray-500">Global Staff Chat</div>
                </div>
            </a>

            @foreach($users as $user)
                <a href="{{ route('notifications.index', ['user_id' => $user->id]) }}" 
                class="flex items-center px-6 py-4 border-b dark:border-slate-800 transition-colors relative {{ $selectedUser?->id == $user->id ? 'bg-yellow-50 dark:bg-slate-800 border-l-4 border-yellow-500' : 'hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                    
                    <div class="relative flex-shrink-0">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-[#0f172a] text-yellow-400 border border-yellow-400/30 text-xs font-bold uppercase">
                            {{ substr($user->name, 0, 1) }}{{ substr($user->user_id_alias ?? '0', -1) }}
                        </div>
                        
                        @if($user->unread_count > 0)
                        <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white border-2 border-white dark:border-slate-900 animate-pulse">
                            {{ $user->unread_count }}
                        </span>
                        @endif
                    </div>

                    <div class="ml-4 flex-1 overflow-hidden">
                        <div class="flex justify-between items-center">
                            <div class="font-bold text-sm dark:text-white truncate">{{ $user->name }}</div>
                        </div>
                        <div class="text-[11px] text-gray-500 truncate">
                            {{ $user->sentNotifications->first()?->message ?? 'No messages yet' }}
                        </div>
                    </div>
                </a>
            @endforeach
            
        </div>
    </div>

    <div class="flex flex-col flex-auto bg-[#f1f5f9] dark:bg-[#020617]">
        <div class="flex flex-row items-center py-4 px-6 bg-white dark:bg-slate-900 shadow-sm border-b dark:border-slate-800">
            <div class="flex flex-col">
                <span class="font-bold text-lg dark:text-white">
                    {{ $selectedUser ? $selectedUser->name : '📢 Main Communication Hub' }}
                </span>
                <span class="text-xs text-green-500 flex items-center">
                    <span class="h-2 w-2 bg-green-500 rounded-full mr-1"></span> Online
                </span>
            </div>
        </div>

        <div class="flex flex-col h-full overflow-x-auto mb-4 p-6 space-y-4 custom-scrollbar">
            @foreach($notifications as $msg)
                @if($msg->type == 'SYSTEM_NOTE')
                    {{-- SYSTEM ALERT STYLE (Red/Green Bar) --}}
                    <div class="flex justify-center my-6">
                        <div class="relative w-full max-w-2xl {{ $msg->is_read ? 'bg-green-600 border-green-400' : 'bg-red-600 border-red-400 animate-pulse' }} text-white px-8 py-5 rounded-3xl shadow-2xl border-2 flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0 md:space-x-6 transition-colors duration-500">
                            
                            <div class="flex items-center space-x-4">
                                <div class="bg-white/20 p-3 rounded-2xl">
                                    <i class="fas {{ $msg->is_read ? 'fa-check-double text-white' : 'fa-exclamation-triangle text-yellow-300' }} fa-lg"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest opacity-80">
                                        {{ $msg->is_read ? 'Issue Resolved' : 'Critical Stock Alert' }}
                                    </p>
                                    <p class="text-base font-bold leading-tight">{{ $msg->message }}</p>
                                    <p class="text-[10px] mt-1 font-medium opacity-70 italic">
                                        Reported by {{ $msg->user->name }} • {{ $msg->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            @if(auth()->user()->role == 'Admin' && !$msg->is_read)
                                <form action="{{ route('notifications.resolve', $msg->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-white text-red-600 px-5 py-2 rounded-xl font-black text-xs uppercase hover:bg-green-100 hover:text-green-700 transition-all shadow-lg active:scale-95 whitespace-nowrap">
                                        Mark Resolved
                                    </button>
                                </form>
                            @elseif($msg->is_read)
                                <div class="bg-white/20 px-4 py-2 rounded-xl border border-white/30">
                                    <span class="text-[10px] font-black uppercase">Cleared</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    {{-- WHATSAPP STYLE CHAT BUBBLES --}}
                    <div class="flex {{ $msg->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }} mb-2">
                        <div class="flex flex-col {{ $msg->sender_id == auth()->id() ? 'items-end' : 'items-start' }} max-w-[80%]">
                            
                            {{-- The Bubble --}}
                            <div class="px-4 py-2.5 shadow-sm relative 
                                {{ $msg->sender_id == auth()->id() 
                                    ? 'bg-[#0f172a] text-white rounded-2xl rounded-tr-none' 
                                    : 'bg-white dark:bg-slate-800 dark:text-gray-100 rounded-2xl rounded-tl-none border border-gray-100 dark:border-slate-700' 
                                }}">
                                
                                <p class="text-[15px] leading-relaxed font-medium">
                                    {{ $msg->message }}
                                </p>

                                {{-- Time Stamp inside bubble for cleaner look --}}
                                <div class="flex items-center justify-end mt-1 space-x-1 opacity-60">
                                    <span class="text-[9px] uppercase font-bold">
                                        {{ $msg->created_at->format('H:i') }}
                                    </span>
                                    @if($msg->sender_id == auth()->id())
                                        <i class="fas fa-check-double text-[9px]"></i>
                                    @endif
                                </div>
                            </div>

                            {{-- Sender Name outside --}}
                            <span class="text-[10px] text-gray-400 mt-1 px-1 font-bold uppercase tracking-tight">
                                {{ $msg->sender_id == auth()->id() ? 'You' : $msg->user->name }}
                            </span>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="p-4 bg-white dark:bg-slate-900 border-t dark:border-slate-800">
            <form action="{{ route('notifications.store') }}" method="POST" class="flex flex-col space-y-2">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $selectedUser?->id }}">
                
                <div class="flex items-center gap-2">
                    <select name="type" class="text-xs rounded-full border-gray-300 dark:bg-slate-800 dark:text-white dark:border-slate-700">
                        <option value="CHAT">💬 Standard</option>
                        <option value="SYSTEM_NOTE">⚠️ Alert</option>
                    </select>
                    
                    <div class="flex-grow flex items-center bg-gray-100 dark:bg-slate-800 rounded-full px-4 py-2">
                        <input name="message" required autocomplete="off"
                               class="bg-transparent border-none focus:ring-0 text-sm w-full dark:text-white"
                               placeholder="Type your message here...">
                        <button type="submit" class="ml-2 bg-yellow-500 hover:bg-yellow-600 text-[#0f172a] p-2 rounded-full transition-transform active:scale-95">
                            <i class="fas fa-paper-plane px-1"></i>
                        </button>
                        
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" type="button" class="text-xl">😊</button>
                            <div x-show="open" @click.away="open = false" class="absolute bottom-full mb-2 bg-white p-2 shadow rounded grid grid-cols-4 gap-2">
                                <button type="button" @click="$dispatch('add-emoji', '✅')">✅</button>
                                <button type="button" @click="$dispatch('add-emoji', '🏷️')">🏷️</button>
                                <button type="button" @click="$dispatch('add-emoji', '❌')">❌</button>
                                <button type="button" @click="$dispatch('add-emoji', '‼️')">‼️</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>
@endsection