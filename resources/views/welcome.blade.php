<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MAVAZI | Logos Christian School</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            .mac-glass {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(15px);
                border: 1px solid rgba(255, 255, 255, 0.3);
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            }
            .logos-blue { background-color: #003366; }
            .logos-gold { background-color: #FFD700; }
            .text-logos-blue { color: #003366; }
        </style>
    </head>
    <body class="bg-gradient-to-br from-blue-900 to-indigo-800 min-h-screen flex flex-col items-center justify-center p-6 text-white">

    <div class="text-center mb-10 max-w-2xl">
        <h1 class="text-5xl font-bold mb-4">MAVAZI</h1>
        <p class="text-lg opacity-90">Logos Christian School Uniform Management System.
        Real-time inventory, seamless transactions, and instant stock alerts.</p>
    </div>

        <div class="mac-glass w-full max-w-md p-8 text-gray-800">
            <div class="flex space-x-2 mb-6">
                <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                <div class="w-3 h-3 bg-green-400 rounded-full"></div>
            </div>

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-logos-blue">Staff Portal</h2>
            <p class="text-sm text-gray-500">Sign in with your Staff ID</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
        @csrf
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Staff ID</label>
                <input type="text" name="user_id_alias" placeholder="e.g. 000" class = "w-full p-3 bg-gray-100 rounded-lg border-none focus:ring-2 focus:ring-yellow-400">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Password</label>
                <input type="password" name="password" placeholder="••••••••" class="w-full p-3 bg-gray-100 rounded-lg border-none focus:ring-2 focus:ring-yellow-400">
            </div>

            <button type="submit" class="w-full py-3 logos-gold text-blue-900 font-bold rounded-lg hover:brightness-110 transition duration-200">
                Log In
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-gray-400">
            © 2026 Logos Christian School | MAVAZI v1.0
        </div>
    </div>

        </body>
</html>
