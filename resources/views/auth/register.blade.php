<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Cashier | MAVAZI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .mac-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .logos-blue { color: #003366; }
        .bg-logos-blue { background-color: #003366; }
        .bg-logos-gold { background-color: #FFD700; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-900 to-indigo-800 min-h-screen flex items-center justify-center p-6 text-white">

    <div class="mac-glass w-full max-w-md p-8 text-gray-800">
        <div class="text-center mb-8">
            <div class="bg-logos-blue text-white w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fas fa-user-plus text-xl"></i>
            </div>
            <h2 class="text-2xl font-bold logos-blue">Cashier Registration</h2>
            <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold mt-1">Staff Account Setup</p>
        </div>

        @if($errors->any())
            <div class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-xs">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                       class="w-full p-3 bg-gray-50 rounded-xl border-none focus:ring-2 focus:ring-yellow-400 transition" 
                       placeholder="e.g. John Doe">
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1">Organization Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                       class="w-full p-3 bg-gray-50 rounded-xl border-none focus:ring-2 focus:ring-yellow-400 transition" 
                       placeholder="staff@logos.ac.ke">
            </div>

            <div class="relative">
                <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1">Create Password</label>
                <input type="password" name="password" id="regPass" required 
                       class="w-full p-3 bg-gray-50 rounded-xl border-none focus:ring-2 focus:ring-yellow-400 transition" 
                       placeholder="••••••••">
                <button type="button" onclick="togglePassword('regPass')" class="absolute right-3 top-9 text-gray-400 hover:logos-blue">
                    <i class="fas fa-eye" id="regPassIcon"></i>
                </button>
            </div>

            <div class="relative">
                <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" id="regConf" required 
                       class="w-full p-3 bg-gray-50 rounded-xl border-none focus:ring-2 focus:ring-yellow-400 transition" 
                       placeholder="••••••••">
                <button type="button" onclick="togglePassword('regConf')" class="absolute right-3 top-9 text-gray-400 hover:logos-blue">
                    <i class="fas fa-eye" id="regConfIcon"></i>
                </button>
            </div>

            <button type="submit" class="w-full py-4 bg-logos-blue text-white font-black rounded-xl hover:brightness-125 shadow-lg transition duration-200 uppercase tracking-widest text-xs">
                Register & Generate ID
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
            <p class="text-xs text-gray-500">Already have a Staff ID? 
                <a href="{{ route('login') }}" class="logos-blue font-bold hover:underline ml-1">Log In Here</a>
            </p>
        </div>
    </div>

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
    </script>
</body>
</html>