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

            <!-- Fondo partículas -->
            <div class="absolute inset-0 opacity-20 pointer-events-none">
                <div class="absolute w-2 h-2 bg-indigo-500 rounded-full top-1/4 left-1/3 animate-pulse"></div>
                <div class="absolute w-2 h-2 bg-emerald-500 rounded-full top-2/3 left-2/4 animate-pulse"></div>
                <div class="absolute w-2 h-2 bg-indigo-400 rounded-full top-1/2 left-3/4 animate-pulse"></div>
            </div>

            <!-- Glow -->
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl"></div>

            <!-- TÍTULO -->
            <div class="mt-10 relative animate-logo">
                <h2 class="text-5xl md:text-6xl font-extrabold leading-tight relative animate-fade-in">
                    Control total de tus
                    <span class="block bg-gradient-to-r from-indigo-400 to-emerald-400 bg-clip-text text-transparent">
                        carros y ganancias
                    </span>
                </h2>
            </div>

            <!-- LOGO GRANDE ANIMADO -->
            <div class="mt-10 relative animate-logo">
                <img src="/images/BASS.png" alt="BASS Motors"
                    class="h-32 md:h-40 w-auto mx-auto drop-shadow-2xl transition duration-500 hover:scale-105">
            </div>

            <!-- BOTONES -->
            <div class="mt-10 flex flex-col sm:flex-row gap-4 relative">

                <!-- ENTRAR -->
                <a href="/login" class="group flex items-center justify-center gap-2 rounded-2xl px-8 py-3 font-semibold 
                          bg-emerald-600/80 backdrop-blur-lg border border-emerald-400/30 
                          hover:bg-emerald-600 transition-all duration-300 shadow-lg">
                    Entrar
                    <ArrowRight class="w-4 h-4 group-hover:translate-x-1 transition" />
                </a>

                <!-- REGISTRARSE -->
                <a href="/register" class="flex items-center justify-center gap-2 rounded-2xl px-8 py-3 font-semibold 
                          bg-indigo-600/30 backdrop-blur-lg border border-indigo-400/40 
                          hover:bg-indigo-600 hover:text-white transition-all duration-300 shadow-lg">
                    Registrarse
                </a>

            </div>

        </main>

        <!-- FEATURES -->
        <section id="features" class="px-8 py-20 bg-gray-900/60">
            <div class="mt-10 relative animate-logo">
                <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">

                    <div
                        class="group bg-gray-950 p-7 rounded-2xl border border-gray-800 hover:border-indigo-500 hover:scale-[1.04] transition">
                        <Car class="w-12 h-12 text-indigo-500 mb-5 group-hover:scale-110 transition" />
                        <h3 class="text-xl font-bold mb-2">Inventario inteligente</h3>
                    </div>

                    <div
                        class="group bg-gray-950 p-7 rounded-2xl border border-gray-800 hover:border-emerald-500 hover:scale-[1.04] transition">
                        <DollarSign class="w-12 h-12 text-emerald-500 mb-5 group-hover:scale-110 transition" />
                        <h3 class="text-xl font-bold mb-2">Ganancias</h3>
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
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="text-center text-sm text-gray-500 py-6 border-t border-gray-800">
            © {{ new Date().getFullYear() }} Carros Alberto
        </footer>

    </div>

</template>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 1s ease forwards;
}

@keyframes logo-float {
    0% {
        transform: translateY(0px);
    }

    50% {
        transform: translateY(-8px);
    }

    100% {
        transform: translateY(0px);
    }
}

.animate-logo {
    animation: logo-float 2s ease-in-out infinite;
}
</style>