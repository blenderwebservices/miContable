<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'miContable') }} - Soluciones Contables Digitales</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="antialiased bg-slate-50 text-slate-900">
    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <span
                            class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-cyan-500">miContable</span>
                    </a>
                    <div class="hidden sm:ml-10 sm:flex sm:space-x-8">
                        <a href="#servicios"
                            class="border-transparent text-slate-500 hover:text-blue-600 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">Servicios</a>
                        <a href="#como-funciona"
                            class="border-transparent text-slate-500 hover:text-blue-600 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">Cómo
                            funciona</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="text-slate-500 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors">Dashboard</a>
                        <a href="{{ url('/admin') }}"
                            class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-medium transition-all shadow-sm">Panel
                            Admin</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-slate-500 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors">Iniciar
                            Sesión</a>
                        <a href="{{ route('register') }}"
                            class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-medium transition-all shadow-sm">Regístrate</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <footer class="bg-white border-t border-slate-200 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <span
                        class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-cyan-500">miContable</span>
                    <p class="mt-4 text-slate-500 max-w-sm">
                        Optimizamos tus impuestos y mantenemos tu situación fiscal al día con tecnología y expertos
                        contables.
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-900 tracking-wider uppercase">Servicios</h3>
                    <ul class="mt-4 space-y-2">
                        <li><a href="#" class="text-slate-500 hover:text-blue-600 text-sm">Declaraciones SAT</a></li>
                        <li><a href="#" class="text-slate-500 hover:text-blue-600 text-sm">Trámites Infonavit</a></li>
                        <li><a href="#" class="text-slate-500 hover:text-blue-600 text-sm">Gestión de Multas</a></li>
                        <li><a href="#" class="text-slate-500 hover:text-blue-600 text-sm">Pensiones</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-900 tracking-wider uppercase">Legal</h3>
                    <ul class="mt-4 space-y-2">
                        <li><a href="#" class="text-slate-500 hover:text-blue-600 text-sm">Privacidad</a></li>
                        <li><a href="#" class="text-slate-500 hover:text-blue-600 text-sm">Términos</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-slate-100 text-center">
                <p class="text-sm text-slate-400">&copy; {{ date('Y') }} miContable. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>

</html>