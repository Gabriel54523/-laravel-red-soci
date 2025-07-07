<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplicación de Mensajería')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-gradient-navbar {
            background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 100%);
        }
        .bg-gradient-main {
            background: linear-gradient(135deg, #f3f4f6 0%, #e0e7ff 100%);
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.3s ease;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gradient-main min-h-screen">
    @auth
    <nav class="bg-gradient-navbar shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-extrabold text-white flex items-center gap-2">
                        <i class="fas fa-comments"></i>
                        <span>RedSoci</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-white/80 hover:text-white font-semibold transition flex items-center gap-1">
                        <i class="fas fa-home"></i>Dashboard
                    </a>
                    <a href="{{ route('conversations.index') }}" class="text-white/80 hover:text-white font-semibold transition flex items-center gap-1">
                        <i class="fas fa-comments"></i>Conversaciones
                    </a>
                    <a href="{{ route('contacts.index') }}" class="text-white/80 hover:text-white font-semibold transition flex items-center gap-1">
                        <i class="fas fa-address-book"></i>Contactos
                    </a>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-white font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-400 px-3 py-2 rounded-full transition duration-150 bg-white/10 hover:bg-white/20">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="avatar" class="w-9 h-9 rounded-full object-cover border-2 border-white shadow mr-2">
                            @else
                                <span class="w-9 h-9 rounded-full bg-indigo-200 flex items-center justify-center mr-2">
                                <i class="fas fa-user-circle text-indigo-500 text-xl"></i>
                            </span>
                            @endif
                            <span class="capitalize">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down ml-2 text-sm"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-2xl py-2 z-50 border border-gray-200 animate-fade-in-up" style="display: none;" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                            <a href="{{ route('perfil.editar') }}" class="block px-5 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition rounded-t-xl flex items-center gap-2">
                                <i class="fas fa-user"></i>Mi Perfil
                            </a>
                            <a href="{{ route('perfil.configuracion') }}" class="block px-5 py-3 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition flex items-center gap-2">
                                <i class="fas fa-cog"></i>Configuración
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-5 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition rounded-b-xl flex items-center gap-2">
                                    <i class="fas fa-sign-out-alt"></i>Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    @endauth
    <main class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 shadow">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 shadow">
                {{ session('error') }}
            </div>
        @endif
        @yield('content')
    </main>
    @stack('scripts')
    <!-- Alpine.js para el menú desplegable -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html> 