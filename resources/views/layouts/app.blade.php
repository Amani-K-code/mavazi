<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAVAZI | Logos Christian School</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'logos-blue': '#003366',
                        'logos-gold': '#FFD700',
                    }
                }
            }
        }
    </script>

    <style>
        /* 1. THEME VARIABLES */
        :root {
            --bg-main: #f1f5f9;        
            --bg-card: #ffffff;        
            --logos-navy: #003366;
            --logos-gold: #FFD700;
            --border-color: #e2e8f0;
            --text-primary: #1e293b;
        }

        .dark {
            --bg-main: #020617;        
            --bg-card: #0f172a;        
            --border-color: #1e293b;   
            --text-primary: #f8fafc;   
        }

        /* 2. BASE STYLES */
        body {
            background-color: var(--bg-main) !important; 
            color: var(--text-primary);
            transition: background-color 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        /* 3. COMPONENT STYLING */
        .bg-white { background-color: var(--bg-card) !important; }

        .dark .bg-white, 
        .dark .bg-slate-100, 
        .dark main { 
            background-color: var(--bg-main) !important; 
        }

        .dark .rounded-3xl, 
        .dark .shadow-sm, 
        .dark .bg-card-custom {
            background-color: var(--bg-card) !important;
            color: var(--text-primary) !important;
        }

        aside { background-color: #003366 !important; }

        .dark .text-gray-500, .dark .text-gray-400 { color: #94a3b8 !important; }
        .dark .border-gray-100, .dark .border-gray-200 { border-color: var(--border-color) !important; }

        /* 4. CART ITEM SPECIFIC STYLING (THE STUBBORN WHITE BITS) */
        
        /* Forces the container card to be Dark Navy */
        .dark .bg-white.rounded-2xl, 
        .dark aside div.bg-white,
        .dark [class*="order-item"] {
            background-color: var(--bg-card) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        /* Forces the Quantity Pill (Selector) to be Pure White */
        .dark .bg-black.rounded-lg.flex.items-center,
        .dark .flex.items-center.bg-black.rounded-lg,
        .dark aside .bg-black {
            background-color: #ffffff !important;
            border: 1px solid #ffffff !important;
        }

        /* Forces the Numbers and Buttons inside the White Pill to be Dark Navy */
        .dark .bg-black.rounded-lg span,
        .dark .bg-black.rounded-lg button,
        .dark aside .bg-black i {
            color: #003366 !important;
            font-weight: 800 !important;
        }

        /* Force icons in the cart to have a white background square if they look odd */
        .dark aside .bg-blue-50, 
        .dark aside .bg-gray-100 {
            background-color: #ffffff !important;
            border-radius: 0.75rem;
        }

        /* 5. FORM INPUTS & SCROLLBARS */
        .dark input, .dark select, .dark textarea {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #ffffff !important;
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .dark ::-webkit-scrollbar-thumb { background: #334155; }
    </style>
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="bg-slate-100 min-h-screen flex">
    <aside class="w-64 bg-logos-blue text-white flex flex-col shadow-xl">
        <a href="/{{ strtolower(Auth::user()->role) }}/dashboard" class="p-6 text-center hover:opacity-80 transition border-b border-white/10">
            <div class="mb-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logos Christian School" class="h-16 w-auto mx-auto object-contain drop-shadow-md">
            </div>
            <h1 class="text-xl font-black tracking-widest">MAVAZI</h1>
        </a>

        <nav class="flex-1 px-4 space-y-2 mt-4">
            @if(Auth::user()->role == 'Storekeeper')
                {{-- STOREKEEPER TABS --}}
                <a href="{{ route('storekeeper.dashboard') }}" class="flex items-center p-3 rounded-xl {{ request()->routeIs('storekeeper.dashboard') ? 'bg-white/20 text-white font-bold' : 'hover:bg-white/10 text-white/70' }}">
                    <i class="fas fa-home w-5 mr-3"></i> Home
                </a>
                <a href="{{ route('storekeeper.flagged') }}" class="flex items-center p-3 rounded-xl {{ request()->routeIs('storekeeper.flagged') ? 'bg-white/20 text-white font-bold' : 'hover:bg-white/10 text-white/70' }}">
                    <i class="fas fa-flag w-5 mr-3 text-logos-gold"></i> Flagged Items
                </a>
                <a href="{{ route('storekeeper.history') }}" class="flex items-center p-3 rounded-xl {{ request()->routeIs('storekeeper.history') ? 'bg-white/20 text-white font-bold' : 'hover:bg-white/10 text-white/70' }}">
                    <i class="fas fa-history w-5 mr-3"></i> Restock History
                </a>
            @elseif(Auth::user()->role == 'Cashier')
                {{-- CASHIER TABS (Unchanged Logic) --}}
                <a href="{{ route('cashier.dashboard') }}" class="flex items-center p-3 rounded-xl {{ request()->routeIs('cashier.dashboard') ? 'bg-white/20 text-white font-bold' : 'hover:bg-white/10 text-white/70' }}">
                    <i class="fas fa-home w-5 mr-3"></i> Catalog
                </a>
                <a href="{{ route('cashier.inventory') }}" class="flex items-center p-3 rounded-xl {{ request()->is('cashier/inventory') ? 'bg-white/20 text-white font-bold' : 'hover:bg-white/10 text-white/70' }}">
                    <i class="fas fa-box w-5 mr-3"></i> Inventory
                </a>
                <a href="{{ route('cashier.history') }}" class="flex items-center p-3 rounded-xl {{ request()->routeIs('cashier.history') ? 'bg-white/20 text-white font-bold' : 'hover:bg-white/10 text-white/70' }}">
                    <i class="fas fa-history w-5 mr-3"></i> History
                </a>
            @endif

            {{-- COMMON TAB: NOTIFICATIONS (Same for all users) --}}
            <a href="{{ route('notifications.index') }}" class="flex items-center p-3 rounded-xl relative {{ request()->routeIs('notifications.index') ? 'bg-white/20 text-white font-bold' : 'hover:bg-white/10 text-white/70 transition' }}">
                <i class="fas fa-bell w-5 mr-3"></i> Notifications

                {{-- 1. LOW STOCK ALERT DOT (Ping) --}}
                @php 
                    $lowStockCount = \App\Models\Inventory::whereColumn('stock_quantity', '<=', 'low_stock_threshold')->count();
                @endphp
                @if($lowStockCount > 0)
                    {{-- This is the "Ghost" ping that attracts the eye --}}
                    <span class="absolute -top-1 -right-1 flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                    </span>
                @endif

                {{-- 2. UNREAD CHAT COUNT (Pulse) --}}
                @php $unread = auth()->user()->unread_count; @endphp 
                @if($unread > 0)
                    <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white animate-pulse">
                        {{ $unread }}
                    </span>
                @endif
            </a>

            {{-- LOGOUT --}}
            <div class="pt-6">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="w-full py-3 bg-red-500/10 text-red-400 rounded-xl hover:bg-red-500 hover:text-white transition flex items-center justify-center font-bold text-xs uppercase tracking-widest border border-red-500/20">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </nav>

    </aside>

    <main class="flex-1 flex flex-col">
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 border-b border-gray-100">
            <div class="flex items-center">
                <button onclick="window.history.back()" class="flex items-center text-gray-400 hover:text-logos-blue transition mr-6 group">
                    <i class="fas fa-chevron-left mr-2 group-hover:-translate-x-1 transition"></i>
                    <span class="text-sm font-semibold uppercase tracking-wider">Previous</span>
                </button>
                <span class="h-6 w-px bg-gray-200 mr-6"></span>
                <span class="text-sm font-medium text-gray-500 uppercase tracking-widest">
                    {{ Auth::user()->role }} Portal
                </span>
            </div>
            <div class="flex items-center space-x-4">
                <button onclick="toggleDarkMode()" class="p-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-logos-gold transition mr-2">
                    <i class="fas fa-moon" id="darkIcon"></i>
                </button>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm font-bold text-logos-blue">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Logos Christian School</p>
                    </div>
                    <div class="w-10 h-10 bg-logos-gold text-logos-blue rounded-full flex items-center justify-center font-black shadow-inner">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <div class="px-8 mt-4">
            @php 
                $alertItems = \App\Models\Inventory::whereColumn('stock_quantity', '<=', 'low_stock_threshold')->get();
            @endphp
            @if($alertItems->count() > 0)
                <div class="bg-red-600 text-white px-6 py-3 rounded-2xl flex items-center justify-between shadow-lg animate-pulse">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-3 text-yellow-300"></i>
                        <span class="text-sm font-bold uppercase tracking-wide">SYSTEM ALERT: {{ $alertItems->count() }} items are critically low on stock!</span>
                    </div>
                    <a href="{{ route('notifications.index') }}" class="text-[10px] bg-white/20 px-3 py-1 rounded-full hover:bg-white/40 transition">VIEW DETAILS</a>
                </div>
            @endif
        </div>

        <div class="px-8 mt-4">
            @php 
                // This fetches the actual items that are low
                $lowStockAlerts = \App\Models\Inventory::whereColumn('stock_quantity', '<=', 'low_stock_threshold')->get();
            @endphp

            @if($lowStockAlerts->count() > 0)
                @foreach($lowStockAlerts as $item)
                    <div class="mb-2 bg-red-600 text-white px-6 py-3 rounded-2xl flex items-center justify-between shadow-lg animate-pulse">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-3 text-yellow-300"></i>
                            <div>
                                <span class="text-xs font-black uppercase block leading-none">Low Stock Alert</span>
                                <span class="text-sm font-medium">{{ $item->item_name }} ({{ $item->size_label }}) has only {{ $item->stock_quantity }} left!</span>
                            </div>
                        </div>
                        <a href="{{ Auth::user()->role == 'Storekeeper' ? route('storekeeper.dashboard') : route('notifications.index') }}" 
                        class="text-[10px] font-bold bg-white/20 px-3 py-1 rounded-full hover:bg-white/40 transition border border-white/30">
                        ACTION REQUIRED
                        </a>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="p-8 overflow-y-auto">

            {{-- GLOBAL SUCCESS ALERT --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    class="fixed top-20 right-8 z-50 bg-green-600 text-white px-6 py-3 rounded-2xl shadow-2xl flex items-center border-2 border-green-400">
                    <i class="fas fa-check-circle mr-3 text-white"></i>
                    <span class="font-bold">{{ session('success') }}</span>
                    <button @click="show = false" class="ml-4 text-white/50 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateIcon(isDark);
        }

        function updateIcon(isDark) {
            const icon = document.getElementById('darkIcon');
            if (icon) icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
        }
        updateIcon(document.documentElement.classList.contains('dark'));
    </script>
</body>
</html>