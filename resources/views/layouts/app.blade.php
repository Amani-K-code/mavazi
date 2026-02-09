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
    <style>
        .mac-glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
        .logos-blue { color: #003366; }
        .bg-logos-blue { background-color: #003366; }
        .bg-logos-gold { background-color: #FFD700; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex">
  <aside class="w-64 bg-logos-blue text-white flex flex-col shadow-xl">
    <a href="/{{ strtolower(Auth::user()->role) }}/dashboard" class="p-6 text-center hover:opacity-80 transition border-b border-white/10">
        <div class="mb-2">
            {{-- LOGO --}}
            <img src="{{ asset('images/logo.png') }}" alt="Logos Christian School" class="h-16 w-auto mx-auto object-contain drop-shadow-md">
        </div>
        <h1 class="text-xl font-black tracking-widest">MAVAZI</h1>
    </a>

    <nav class="flex-1 px-4 space-y-2 mt-4">
        
        <a href="/{{ strtolower(Auth::user()->role) }}/dashboard" class="flex items-center p-3 rounded-xl bg-white/10 text-white">
            <i class="fas fa-home mr-3"></i> Dashboard
        </a>
        @if(Auth::user()->role == 'Admin')
            <a href="#" class="block p-3 rounded-lg hover:bg-white/20 transition">
                <i class="fas fa-users-cog mr-3"></i> Staff Management
            </a>
        @endif
        <a href="#" class="block p-3 rounded-lg hover:bg-white/20 transition">
                <i class="fas fa-box mr-3"></i> Inventory
            </a>
            <a href="#" class="block p-3 rounded-lg hover:bg-white/20 transition">
                <i class="fas fa-history mr-3"></i> History
            </a>
        </nav>

        <div class="p-6">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full py-3 bg-red-500/20 text-red-300 rounded-xl hover:bg-red-500 hover:text-white transition">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </button>
            </form>
        </div>
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
                    <div class="text-right">
                        <p class="text-sm font-bold text-logos-blue">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Logos Christian School</p>
                    </div>
                    <div class="w-10 h-10 bg-logos-gold text-logos-blue rounded-full flex items-center justify-center font-black shadow-inner">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>


            <div class="p-8 overflow-y-auto">
                @yield('content')
            </div>
        </main>

</body>
</html>
