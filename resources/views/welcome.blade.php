<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | LCS MAVAZI</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .bg-logos-blue { background-color: #003366; }
        .text-logos-blue { color: #003366; }
        .bg-logos-gold { background-color: #FFD700; }
        
        .hero-gradient {
            background: linear-gradient(135deg, #003366 0%, #001a33 100%);
        }
        
        .section-gradient {
            background: linear-gradient(to bottom right, #1e3a8a, #3730a3);
        }

        .mac-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .mac-glass-card:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-10px);
            border-color: #FFD700;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        
        /* Animations - Toned down for clarity */
        @keyframes pulse-red {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        .animate-stock-alert { animation: pulse-red 2s infinite; }

        @keyframes float-gentle {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(1deg); }
        }
        .animate-float { animation: float-gentle 6s ease-in-out infinite; }

        @keyframes float-subtle {
            0%, 100% { transform: translateY(0) rotate(-2deg); }
            50% { transform: translateY(-8px) rotate(0deg); }
        }
        .animate-float-delayed { animation: float-subtle 8s ease-in-out infinite; }

        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="antialiased font-sans">

    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="LCS Logo" class="h-12 w-auto">
                <span class="text-logos-blue font-black text-xl tracking-tighter">MAVAZI</span>
            </div>
            
            <div class="flex items-center gap-8">
                <a href="#core-features" class="text-sm font-bold text-logos-blue hover:text-blue-700 transition uppercase tracking-widest">
                    Features
                </a>
                
                <a href="#login-section" class="px-6 py-2.5 bg-logos-blue text-white rounded-xl font-bold text-sm transition hover:bg-blue-900 shadow-lg shadow-blue-900/20 uppercase tracking-widest">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </a>
            </div>
        </div>
    </nav>

    <section class="relative min-h-screen flex items-center pt-20 overflow-hidden hero-gradient text-white">
        <div class="absolute top-0 right-0 w-1/2 h-full bg-logos-gold/5 skew-x-12 translate-x-1/4"></div>
        
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center relative z-10">
            <div class="space-y-8">
                <div class="inline-block px-4 py-2 bg-logos-gold/20 rounded-full border border-logos-gold/30">
                    <span class="text-logos-gold font-bold text-xs uppercase tracking-[0.2em]">Logos Christian School</span>
                </div>
                <h1 class="text-6xl md:text-7xl font-black leading-tight">
                    Smart Uniform <br><span class="text-logos-gold">Management.</span>
                </h1>
                <p class="text-blue-100 text-lg leading-relaxed max-w-lg">
                    A dedicated platform for LCS staff to streamline uniform sales, track inventory in real-time, and manage school apparel with precision.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#login-section" class="px-8 py-4 bg-logos-gold text-logos-blue font-black rounded-2xl shadow-xl hover:scale-105 transition uppercase tracking-widest text-sm">
                        Get Started <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <button onclick="alert('Feature Coming Soon: Sizing guides and digital catalogs are on the way!')" class="px-8 py-4 bg-white/10 text-white font-bold rounded-2xl border border-white/20 hover:bg-white/20 transition uppercase tracking-widest text-sm backdrop-blur-md">
                        Learn More
                    </button>
                </div>
            </div>

            <div class="hidden lg:block relative">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-logos-gold/20 rounded-full blur-[120px] -z-10"></div>

                <div class="relative z-20 animate-float">
                    <div class="bg-white/5 p-4 rounded-[3rem] border border-white/10 backdrop-blur-sm shadow-2xl">
                        <img src="{{ asset('images/uniforms/Tracksuit.png') }}" alt="LCS Tracksuit" class="rounded-[2.5rem] w-full h-auto drop-shadow-2xl">
                    </div>
                    
                    <div class="absolute -top-6 -right-6 bg-logos-gold text-logos-blue py-3 px-6 rounded-2xl font-black shadow-2xl rotate-12 flex flex-col items-center border-4 border-logos-blue/10">
                        <span class="text-[10px] uppercase opacity-60 leading-none">Price From</span>
                        <span class="text-xl">KES XXXX</span>
                    </div>
                </div>

                <div class="absolute -bottom-6 -right-12 z-10 w-1/2 animate-float-delayed opacity-60">
                    <div class="bg-white/10 p-2 rounded-[2rem] border border-white/10 backdrop-blur-md shadow-xl">
                        <img src="{{ asset('images/uniforms/fleece-hoodie.png') }}" alt="LCS Hoodie" class="rounded-[1.5rem] grayscale-[10%]">
                    </div>
                </div>

                <div class="absolute -bottom-10 -left-10 z-30 mac-glass p-4 rounded-3xl shadow-2xl border border-white/20 flex items-center gap-4">
                    <div class="w-10 h-10 bg-green-500/20 rounded-2xl flex items-center justify-center">
                        <div class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse"></div>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase text-white/40 tracking-widest leading-none mb-1">Stock Status</p>
                        <p class="text-xs font-bold">42 Tracksuits Available</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="core-features" class="py-24 section-gradient text-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-64 h-64 bg-logos-gold opacity-5 blur-3xl rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-20">
                <h2 class="text-xs font-black text-logos-gold uppercase tracking-[0.3em] mb-4">Core Capabilities</h2>
                <p class="text-4xl font-black">Unified Workflow for Staff</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="mac-glass mac-glass-card p-8 rounded-[2.5rem]">
                    <div class="w-16 h-16 bg-logos-gold text-logos-blue rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-cash-register text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-black mb-3 text-logos-gold">Cashier Point-of-Sale</h3>
                    <p class="text-blue-100/70 text-sm leading-relaxed mb-6">Process sales instantly with an intuitive interface. Handle bulk orders and generate branded PDF receipts for every transaction.</p>
                    <div class="flex items-center gap-2 text-[10px] font-bold uppercase text-logos-gold">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span> Terminal Active
                    </div>
                </div>

                <div class="mac-glass mac-glass-card p-8 rounded-[2.5rem] border-2 border-white/5">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-boxes text-2xl text-logos-gold"></i>
                    </div>
                    <h3 class="text-xl font-black mb-3">Storekeeper Insights</h3>
                    <p class="text-blue-100/70 text-sm leading-relaxed mb-6">Real-time inventory management. Track stock levels and receive automated alerts before items run out.</p>
                    
                    <div class="bg-black/20 rounded-xl p-3 border border-red-500/30 flex items-center justify-between animate-stock-alert">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-exclamation-triangle text-red-500 text-xs"></i>
                            <span class="text-[10px] font-bold uppercase tracking-tighter">Boys Trouser (Size 32)</span>
                        </div>
                        <span class="text-[10px] font-black text-red-500">LOW: 2 Left</span>
                    </div>
                </div>

                <div class="mac-glass mac-glass-card p-8 rounded-[2.5rem]">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-chart-pie text-2xl text-logos-gold"></i>
                    </div>
                    <h3 class="text-xl font-black mb-3">Admin Intelligence</h3>
                    <p class="text-blue-100/70 text-sm leading-relaxed mb-6">Comprehensive sales analysis and staff oversight. Monitor daily revenue and manage user permissions across the board.</p>
                    <div class="flex gap-1">
                        <div class="h-1 w-4 bg-logos-gold rounded-full"></div>
                        <div class="h-1 w-8 bg-logos-gold rounded-full"></div>
                        <div class="h-1 w-2 bg-logos-gold rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="login-section" class="py-24 bg-gradient-to-br from-blue-900 to-indigo-800 flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md px-6">
            @if(View::hasSection('content'))
                @yield('content')
            @else
            <div class="bg-white/70 backdrop-blur-xl p-10 rounded-[2.5rem] text-gray-800 border border-white/30 shadow-2xl">
                <div class="text-center mb-10">
                    <div class="bg-logos-blue text-white w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <i class="fas fa-lock text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-black text-logos-blue uppercase tracking-tight">Staff Portal</h2>
                    <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.2em] mt-1">Sign in with your Staff ID</p>
                </div>

                @if($errors->any() || session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-600 text-xs flex items-center gap-3 animate-pulse">
                        <i class="fas fa-exclamation-circle text-lg"></i>
                        <div>
                            <p class="font-black uppercase tracking-tight">Access Denied</p>
                            <p class="opacity-80">
                                {{ $errors->first('user_id_alias') ?: $errors->first('password') ?: session('error') }}
                            </p>
                        </div>
                    </div>
                @endif

                <div id="login-error-container" class="hidden"></div>

                <form id="loginForm" action="{{ route('login.post') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2 ml-1">Staff ID</label>
                        <input type="text" name="user_id_alias" id="user_id_alias" placeholder="e.g. 000" required 
                            class="w-full p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-logos-gold transition outline-none text-gray-700 font-medium">
                    </div>

                    <div class="relative">
                        <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2 ml-1">Password</label>
                        <input type="password" name="password" id="loginPassword" placeholder="••••••••" required 
                            class="w-full p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-logos-gold transition outline-none text-gray-700 font-medium">
                        <button type="button" onclick="togglePassword('loginPassword')" class="absolute right-4 top-[3.25rem] -translate-y-1/2 text-gray-400 hover:text-logos-blue transition">
                            <i class="fas fa-eye" id="loginPasswordIcon"></i>
                        </button>
                    </div>

                    <button type="submit" id="submitBtn" class="w-full py-4 bg-logos-blue text-white font-black rounded-2xl shadow-xl hover:brightness-125 transition-all uppercase tracking-widest text-xs">
                        <span id="btnText">Enter Portal</span>
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-500">New Cashier? 
                        <a href="{{ route('register') }}" class="text-logos-blue font-bold hover:underline ml-1">Register Account</a>
                    </p>
                </div>
            </div>
            @endif
            
            <p class="mt-8 text-center text-[10px] text-white/40 font-bold uppercase tracking-[0.3em]">
                &copy; {{ date('Y') }} Logos Christian School | MAVAZI v1.0
            </p>
        </div>
    </section>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = document.getElementById(id + 'Icon');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }


        document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Stop the page from reloading

    const form = this;
    const formData = new FormData(form);
    const errorContainer = document.getElementById('login-error-container');
    const btnText = document.getElementById('btnText');

    // Show loading state
    btnText.innerText = "Verifying...";
    errorContainer.classList.add('hidden');

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        // We need to parse the JSON data first to get the dashboard URL
        return response.json().then(data => {
            if (response.ok) {
                // SUCCESS: Redirect to the URL sent from the Controller (data.url)
                window.location.href = data.url; 
            } else {
                // FAILURE: Show error message
                errorContainer.innerHTML = `
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-600 text-xs flex items-center gap-3">
                        <i class="fas fa-exclamation-circle text-lg"></i>
                        <div>
                            <p class="font-black uppercase tracking-tight">Access Denied</p>
                            <p class="opacity-80">${data.errors ? Object.values(data.errors)[0] : 'Invalid credentials'}</p>
                        </div>
                    </div>
                `;
                errorContainer.classList.remove('hidden');
                btnText.innerText = "Enter Portal";
                document.getElementById('loginPassword').value = '';
            }
        });
    })
    .catch(error => {
        console.error('Error:', error);
        btnText.innerText = "Enter Portal";
    });
});
    </script>
</body>
</html>