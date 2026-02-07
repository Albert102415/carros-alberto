<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'
import { FileText } from 'lucide-vue-next'

const props = defineProps<{ ventas: any[] }>()

const money = (v: number) =>
    new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN',
    }).format(v)

// 🔹 Agrupar ventas por mes
const ventasPorMes = computed(() => {
    const grupos: Record<string, any[]> = {}

    props.ventas.forEach(v => {
        if (!v.fecha_venta) return

        const fecha = new Date(v.fecha_venta)
        const mes = fecha.toLocaleString('es-MX', {
            month: 'long',
            year: 'numeric',
        })

        if (!grupos[mes]) grupos[mes] = []
        grupos[mes].push(v)
    })

    return grupos
})

// 🔹 Total general
const totalGanancia = computed(() =>
    props.ventas.reduce((sum, v) => sum + (v.ganancia_real ?? 0), 0)
)
</script>

<template>

    <Head title="Historial de Ventas" />

    <AppLayout>
        <div class="p-4 space-y-10">

            <h1 class="text-2xl font-bold">Historial de Ventas</h1>

            <!-- VENTAS POR MES -->
            <div v-for="(ventas, mes) in ventasPorMes" :key="mes" class="space-y-4">

                <!-- TITULO MES -->
                <h2 class="text-xl font-bold text-indigo-500 capitalize">
                    {{ mes }}
                </h2>

                <!-- TABLA DEL MES -->
                <div class="overflow-auto rounded-xl border">
                    <table class="w-full text-sm border-collapse">
                        <thead class="bg-black text-white">
                            <tr>
                                <th class="p-2 text-left">Carro</th>
                                <th class="p-2">Fecha</th>
                                <th class="p-2 text-right">Costo real</th>
                                <th class="p-2 text-right">Venta</th>
                                <th class="p-2 text-right">Ganancia</th>
                                <th class="p-2 text-center">PDF</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="v in ventas" :key="v.id"
                                class="border-b hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="p-2 font-medium">
                                    {{ v.marca }} {{ v.linea }} {{ v.modelo }}
                                </td>

                                <td class="p-2 text-center">
                                    {{ new Date(v.fecha_venta).toLocaleDateString() }}
                                </td>

                                <td class="p-2 text-right">
                                    {{ money(v.costo_real) }}
                                </td>

                                <td class="p-2 text-right">
                                    {{ money(v.precio_venta) }}
                                </td>

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
                <div class="text-right font-bold text-emerald-600">
                    Total del mes:
                    {{
                        money(
                            ventas.reduce((sum, v) => sum + (v.ganancia_real ?? 0), 0)
                        )
                    }}
                </div>
            </div>

            <!-- TOTAL GENERAL -->
            <div class="text-right text-2xl font-bold text-emerald-700 pt-6 border-t">
                Total ganado: {{ money(totalGanancia) }}
            </div>

        </div>
    </AppLayout>
</template>
