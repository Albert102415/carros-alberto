<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import { FileText, TrendingUp, Car, DollarSign, Search, X } from 'lucide-vue-next'

const props = defineProps<{ ventas: any[] }>()

const money = (v: number) =>
    new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN',
    }).format(v)

const formatFecha = (fecha: string) => {
    const [year, month, day] = fecha.split('T')[0].split('-')
    return `${day}/${month}/${year}`
}

/* =========================
   FILTROS
========================= */
const buscador = ref('')
const filtroMes = ref('')
const ordenVentas = ref('fecha_desc')

const limpiarFiltros = () => {
    buscador.value = ''
    filtroMes.value = ''
    ordenVentas.value = 'fecha_desc'
}

const hayFiltrosActivos = computed(() =>
    buscador.value || filtroMes.value || ordenVentas.value !== 'fecha_desc'
)

/* =========================
   MESES DISPONIBLES
========================= */
const mesesDisponibles = computed(() => {
    const set = new Set<string>()
    props.ventas.forEach(v => {
        if (!v.fecha_venta) return
        const fecha = new Date(v.fecha_venta)
        const key = `${fecha.getFullYear()}-${String(fecha.getMonth() + 1).padStart(2, '0')}`
        set.add(key)
    })
    return [...set].sort((a, b) => b.localeCompare(a)).map(key => {
        const [year, month] = key.split('-')
        const label = new Date(Number(year), Number(month) - 1).toLocaleString('es-MX', {
            month: 'long',
            year: 'numeric',
        })
        return { key, label }
    })
})

/* =========================
   VENTAS FILTRADAS
========================= */
const ventasFiltradas = computed(() => {
    let data = props.ventas.filter(v => {
        if (!v.fecha_venta) return false

        if (buscador.value) {
            const text = `${v.marca} ${v.linea} ${v.modelo}`.toLowerCase()
            if (!text.includes(buscador.value.toLowerCase())) return false
        }

        if (filtroMes.value) {
            const fecha = new Date(v.fecha_venta)
            const key = `${fecha.getFullYear()}-${String(fecha.getMonth() + 1).padStart(2, '0')}`
            if (key !== filtroMes.value) return false
        }

        return true
    })

    data = [...data].sort((a, b) => {
        if (ordenVentas.value === 'fecha_desc') return new Date(b.fecha_venta).getTime() - new Date(a.fecha_venta).getTime()
        if (ordenVentas.value === 'fecha_asc') return new Date(a.fecha_venta).getTime() - new Date(b.fecha_venta).getTime()
        if (ordenVentas.value === 'ganancia_desc') return (b.ganancia_real ?? 0) - (a.ganancia_real ?? 0)
        if (ordenVentas.value === 'ganancia_asc') return (a.ganancia_real ?? 0) - (b.ganancia_real ?? 0)
        return 0
    })

    return data
})

/* =========================
   AGRUPAR POR MES
========================= */
const ventasPorMes = computed(() => {
    const grupos: Record<string, { label: string; ventas: any[] }> = {}

    ventasFiltradas.value.forEach(v => {
        const fecha = new Date(v.fecha_venta)
        const key = `${fecha.getFullYear()}-${String(fecha.getMonth() + 1).padStart(2, '0')}`
        const label = fecha.toLocaleString('es-MX', { month: 'long', year: 'numeric' })

        if (!grupos[key]) grupos[key] = { label, ventas: [] }
        grupos[key].ventas.push(v)
    })

    return grupos
})

/* =========================
   KPIs
========================= */
const totalVendidos = computed(() => ventasFiltradas.value.length)

const totalGanancia = computed(() =>
    ventasFiltradas.value.reduce((sum, v) => sum + (v.ganancia_real ?? 0), 0)
)

const promedioGanancia = computed(() =>
    totalVendidos.value > 0 ? totalGanancia.value / totalVendidos.value : 0
)
</script>

<template>

    <Head title="Historial de Ventas" />

    <AppLayout>
        <div class="p-4 space-y-8">

            <h1 class="text-2xl font-bold">Historial de Ventas</h1>

            <!-- ================= KPIs ================= -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 p-5 rounded-xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-white opacity-80">Carros vendidos</p>
                            <p class="text-3xl font-bold text-white">{{ totalVendidos }}</p>
                        </div>
                        <Car class="w-10 h-10 text-white opacity-70" />
                    </div>
                </div>

                <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 p-5 rounded-xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-white opacity-80">Ganancia total</p>
                            <p class="text-2xl font-bold text-white">{{ money(totalGanancia) }}</p>
                        </div>
                        <DollarSign class="w-10 h-10 text-white opacity-70" />
                    </div>
                </div>

                <div class="bg-gradient-to-br from-amber-600 to-amber-800 p-5 rounded-xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-white opacity-80">Promedio por carro</p>
                            <p class="text-2xl font-bold text-white">{{ money(promedioGanancia) }}</p>
                        </div>
                        <TrendingUp class="w-10 h-10 text-white opacity-70" />
                    </div>
                </div>

            </div>

            <!-- ================= FILTROS ================= -->
            <div class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 flex flex-wrap gap-3 items-center">

                <!-- Buscador -->
                <div class="relative">
                    <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <input v-model="buscador" placeholder="Buscar por marca, línea, modelo..." class="border border-gray-300 dark:border-gray-600 rounded-lg pl-9 pr-3 py-2 text-sm
                               bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                               placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 w-64" />
                </div>

                <!-- Filtro mes -->
                <select v-model="filtroMes" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm
                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                           focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">📅 Todos los meses</option>
                    <option v-for="m in mesesDisponibles" :key="m.key" :value="m.key" class="capitalize">
                        {{ m.label }}
                    </option>
                </select>

                <!-- Ordenar -->
                <select v-model="ordenVentas" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm
                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                           focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="fecha_desc">↕ Más reciente primero</option>
                    <option value="fecha_asc">↕ Más antiguo primero</option>
                    <option value="ganancia_desc">↕ Mayor ganancia</option>
                    <option value="ganancia_asc">↕ Menor ganancia</option>
                </select>

                <!-- Limpiar -->
                <button v-if="hayFiltrosActivos" @click="limpiarFiltros" class="flex items-center gap-1 px-3 py-2 rounded-lg text-sm
                           bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900 dark:text-red-300
                           transition font-medium">
                    <X class="w-4 h-4" /> Limpiar
                </button>

                <!-- Contador -->
                <p class="text-xs text-gray-500 dark:text-gray-400 ml-auto">
                    {{ totalVendidos }} venta{{ totalVendidos !== 1 ? 's' : '' }} encontrada{{ totalVendidos !== 1 ? 's'
                        : '' }}
                </p>

            </div>

            <!-- ================= ESTADO VACÍO ================= -->
            <div v-if="totalVendidos === 0" class="text-center py-16 text-gray-400">
                <Car class="w-12 h-12 mx-auto mb-3 opacity-30" />
                <p class="text-lg">No se encontraron ventas</p>
                <p class="text-sm">Intenta cambiar los filtros</p>
            </div>

            <!-- ================= VENTAS POR MES ================= -->
            <div v-for="(grupo, key) in ventasPorMes" :key="key" class="space-y-3">

                <!-- TITULO MES -->
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-indigo-500 capitalize">
                        {{ grupo.label }}
                    </h2>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ grupo.ventas.length }} carro{{ grupo.ventas.length !== 1 ? 's' : '' }} vendido{{
                            grupo.ventas.length !== 1 ? 's' : '' }}
                    </span>
                </div>

                <!-- TABLA DEL MES -->
                <div class="overflow-auto rounded-xl border">
                    <table class="w-full text-sm border-collapse">
                        <thead class="bg-black text-white">
                            <tr>
                                <th class="p-2 text-left">Carro</th>
                                <th class="p-2 text-center">Fecha</th>
                                <th class="p-2 text-right">Costo real</th>
                                <th class="p-2 text-right">Venta</th>
                                <th class="p-2 text-right">Ganancia</th>
                                <th class="p-2 text-center">PDF</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="v in grupo.ventas" :key="v.id" class="border-b transition" :class="v.ganancia_real < 0
                                ? 'bg-red-50 dark:bg-red-950 hover:bg-red-100 dark:hover:bg-red-900'
                                : 'hover:bg-gray-50 dark:hover:bg-gray-800'">
                                <td class="p-2 font-medium">
                                    {{ v.marca }} {{ v.linea }} {{ v.modelo }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ formatFecha(v.fecha_venta) }}
                                </td>
                                <td class="p-2 text-right">{{ money(v.costo_real) }}</td>
                                <td class="p-2 text-right">{{ money(v.precio_venta) }}</td>
                                <td class="p-2 text-right font-semibold"
                                    :class="v.ganancia_real >= 0 ? 'text-green-600' : 'text-red-600'">
                                    {{ money(v.ganancia_real) }}
                                </td>
                                <td class="p-2 text-center">
                                    <a :href="`/ventas/${v.id}/pdf`" target="_blank"
                                        class="inline-flex items-center gap-1 text-indigo-600 hover:underline">
                                        <FileText class="w-4 h-4" />
                                        PDF
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- TOTAL POR MES -->
                <div class="flex justify-end gap-6 text-sm font-semibold pr-1">
                    <span class="text-gray-500">
                        {{ grupo.ventas.length }} vendido{{ grupo.ventas.length !== 1 ? 's' : '' }}
                    </span>
                    <span :class="grupo.ventas.reduce((s, v) => s + (v.ganancia_real ?? 0), 0) >= 0
                        ? 'text-emerald-600'
                        : 'text-red-600'">
                        Total: {{money(grupo.ventas.reduce((s, v) => s + (v.ganancia_real ?? 0), 0))}}
                    </span>
                </div>

            </div>

            <!-- ================= TOTAL GENERAL ================= -->
            <div v-if="totalVendidos > 0" class="text-right text-2xl font-bold text-emerald-700 pt-6 border-t"
                :class="totalGanancia < 0 ? 'text-red-600' : 'text-emerald-700'">
                Total ganado: {{ money(totalGanancia) }}
            </div>

        </div>
    </AppLayout>
</template>