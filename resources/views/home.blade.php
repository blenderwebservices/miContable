<x-frontend-layout>
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-white pt-16 pb-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-6xl">
                    Tu contabilidad, <span class="text-blue-600">así de fácil.</span>
                </h1>
                <p class="mt-6 text-xl text-slate-500 max-w-2xl mx-auto">
                    Olvídate de los trámites confusos. miContable te ayuda a optimizar el pago de tus impuestos y a
                    mantenerte al corriente con tecnología y expertos.
                </p>
                <div class="mt-10 flex justify-center gap-4">
                    <a href="{{ route('register') }}"
                        class="px-8 py-3 bg-blue-600 text-white rounded-xl font-semibold shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all">Empezar
                        ahora</a>
                    <a href="#servicios"
                        class="px-8 py-3 bg-white text-slate-700 border border-slate-200 rounded-xl font-semibold hover:bg-slate-50 transition-all">Ver
                        servicios</a>
                </div>
            </div>
        </div>

        <!-- Decoration bits -->
        <div class="absolute top-0 right-0 -translate-y-12 translate-x-12 blur-3xl opacity-20">
            <div class="aspect-square w-[400px] bg-blue-400 rounded-full"></div>
        </div>
        <div class="absolute bottom-0 left-0 translate-y-24 -translate-x-24 blur-3xl opacity-20">
            <div class="aspect-square w-[400px] bg-cyan-400 rounded-full"></div>
        </div>
    </div>

    <!-- Features Section (Inspired by fixat steps) -->
    <div id="como-funciona" class="py-24 bg-slate-50 border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900">Paga tus impuestos sin complicaciones</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="text-center">
                    <div
                        class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">1. Regístrate</h3>
                    <p class="text-slate-500">Ingresa tus datos y conecta con nuestra plataforma en minutos.</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-16 h-16 bg-cyan-100 text-cyan-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">2. Automatizamos</h3>
                    <p class="text-slate-500">Conectamos tus ingresos y facturas automáticamente para el cálculo justo.
                    </p>
                </div>
                <div class="text-center">
                    <div
                        class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">3. Declaración Lista</h3>
                    <p class="text-slate-500">Recibe tu declaración revisada por expertos y paga lo justo.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <div id="servicios" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Servicios Especializados</h2>
                <p class="text-slate-500 text-lg">Ofrecemos una gama completa de servicios para tu tranquilidad fiscal y
                    patrimonial.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Infonavit -->
                <div
                    class="p-8 border border-slate-100 rounded-3xl hover:border-blue-200 hover:shadow-xl hover:shadow-blue-50 transition-all">
                    <div class="w-12 h-12 bg-red-100 text-red-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Trámites de Infonavit</h3>
                    <ul class="text-slate-500 space-y-2 mb-6">
                        <li>• Liberación de escrituras</li>
                        <li>• Devolución de subcuenta</li>
                        <li>• Regularización de pagos</li>
                    </ul>
                    <a href="#" class="text-blue-600 font-semibold hover:underline">Saber más →</a>
                </div>

                <!-- Multas -->
                <div
                    class="p-8 border border-slate-100 rounded-3xl hover:border-blue-200 hover:shadow-xl hover:shadow-blue-50 transition-all">
                    <div
                        class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Gestión de Multas</h3>
                    <ul class="text-slate-500 space-y-2 mb-6">
                        <li>• Análisis de notificaciones</li>
                        <li>• Recursos de revocación</li>
                        <li>• Condonación de multas</li>
                    </ul>
                    <a href="#" class="text-blue-600 font-semibold hover:underline">Saber más →</a>
                </div>

                <!-- Pensiones -->
                <div
                    class="p-8 border border-slate-100 rounded-3xl hover:border-blue-200 hover:shadow-xl hover:shadow-blue-50 transition-all">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Pensiones</h3>
                    <ul class="text-slate-500 space-y-2 mb-6">
                        <li>• Cálculo de pensión IMSS</li>
                        <li>• Modalidad 40</li>
                        <li>• Proyecciones a futuro</li>
                    </ul>
                    <a href="#" class="text-blue-600 font-semibold hover:underline">Saber más →</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to action -->
    <div class="py-24 bg-blue-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <h2 class="text-3xl font-bold mb-8 italic">"Adiós al estrés y los trámites confusos"</h2>
            <p class="text-xl mb-12 text-blue-100">
                Únete a los cientos de contribuyentes que ya duermen tranquilos con miContable.
            </p>
            <a href="{{ route('register') }}"
                class="px-10 py-4 bg-white text-blue-600 rounded-xl font-bold text-lg hover:bg-slate-50 transition-all shadow-xl">Crear
                mi cuenta gratuita</a>
        </div>
    </div>
</x-frontend-layout>