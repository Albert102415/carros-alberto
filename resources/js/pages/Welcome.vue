<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { Car, DollarSign, BarChart3, LogIn, ArrowRight } from 'lucide-vue-next'
</script>

<template>

    <Head title="Carros Alberto" />

    <div class="min-h-screen bg-gradient-to-br from-black via-gray-900 to-black text-gray-100 flex flex-col">

        <!-- HEADER -->
        <header class="flex items-center justify-between px-8 py-6 border-b border-gray-800 backdrop-blur">
            <h1
                class="text-2xl font-extrabold tracking-wide bg-gradient-to-r from-indigo-400 to-emerald-400 bg-clip-text text-transparent">
                Carros Alberto
            </h1>

            <a href="/login"
                class="flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold hover:bg-indigo-700 transition">
                <LogIn class="w-4 h-4" />
                Entrar
            </a>
        </header>

        <!-- HERO -->
        <main class="flex-1 flex flex-col items-center justify-center text-center px-6 relative overflow-hidden">

            <!-- glow -->
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl"></div>

            <h2 class="text-5xl md:text-6xl font-extrabold leading-tight relative">
                Control total de tus
                <span class="block bg-gradient-to-r from-indigo-400 to-emerald-400 bg-clip-text text-transparent">
                    carros y ganancias
                </span>
            </h2>

            <p class="mt-6 max-w-2xl text-gray-400 text-lg relative">

            </p>

            <div class="mt-10 flex flex-col sm:flex-row gap-4 relative">
                <a href="/login"
                    class="group flex items-center gap-2 rounded-xl bg-emerald-600 px-7 py-3 font-semibold hover:bg-emerald-700 transition">
                    Entrar al sistema
                    <ArrowRight class="w-4 h-4 group-hover:translate-x-1 transition" />
                </a>

                <a href="#features"
                    class="rounded-xl border border-gray-700 px-7 py-3 font-semibold hover:bg-gray-800 transition">
                    Ver funciones
                </a>
            </div>
        </main>

        <!-- FEATURES -->
        <section id="features" class="px-8 py-20 bg-gray-900/60">
            <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">

                <div
                    class="group bg-gray-950 p-7 rounded-2xl border border-gray-800 hover:border-indigo-500 hover:scale-[1.04] transition">
                    <Car class="w-12 h-12 text-indigo-500 mb-5 group-hover:scale-110 transition" />
                    <h3 class="text-xl font-bold mb-2">Inventario inteligente</h3>
                    <p class="text-gray-400 text-sm">
                        Visualiza carros disponibles, apartados y vendidos en un solo lugar.
                    </p>
                </div>

                <div
                    class="group bg-gray-950 p-7 rounded-2xl border border-gray-800 hover:border-emerald-500 hover:scale-[1.04] transition">
                    <DollarSign class="w-12 h-12 text-emerald-500 mb-5 group-hover:scale-110 transition" />
                    <h3 class="text-xl font-bold mb-2">Ganancias reales</h3>
                    <p class="text-gray-400 text-sm">
                        Calcula automáticamente cuánto ganas por cada venta.
                    </p>
                </div>

                <div
                    class="group bg-gray-950 p-7 rounded-2xl border border-gray-800 hover:border-amber-500 hover:scale-[1.04] transition">
                    <BarChart3 class="w-12 h-12 text-amber-500 mb-5 group-hover:scale-110 transition" />
                    <h3 class="text-xl font-bold mb-2">Dashboard visual</h3>
                    <p class="text-gray-400 text-sm">
                        Gráficas claras para tomar mejores decisiones.
                    </p>
                </div>

            </div>
        </section>

        <!-- FOOTER -->
        <footer class="text-center text-sm text-gray-500 py-6 border-t border-gray-800">
            © {{ new Date().getFullYear() }} Carros Alberto
        </footer>

    </div>
</template>