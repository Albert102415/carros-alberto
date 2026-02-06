<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'
import { FileText } from 'lucide-vue-next'


const props = defineProps<{ ventas: any[] }>()

const money = (v: number) =>
    new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(v)

const totalGanancia = () =>
    props.ventas.reduce(
        (sum, v) => sum + (v.precio_venta - v.precio_compra),
        0
    )
</script>

<template>

    <Head title="Historial de Ventas" />

    <AppLayout>
        <div class="p-4 space-y-6">

            <h1 class="text-2xl font-bold">Historial de Ventas</h1>

            <div class="overflow-auto rounded-xl border">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-black text-white">
                        <tr>
                            <th class="p-2 text-left">Carro</th>
                            <th class="p-2">Fecha</th>
                            <th class="p-2 text-right">Compra</th>
                            <th class="p-2 text-right">Venta</th>
                            <th class="p-2 text-right">Ganancia</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="v in props.ventas" :key="v.id"
                            class="border-b hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="p-2 font-medium">
                                {{ v.marca }} {{ v.linea }} {{ v.modelo }}
                            </td>
                            <td class="p-2 text-center">
                                {{ new Date(v.fecha_venta).toLocaleDateString() }}
                            </td>
                            <td class="p-2 text-right">{{ money(v.precio_compra) }}</td>
                            <td class="p-2 text-right">{{ money(v.precio_venta) }}</td>
                            <td class="p-2 text-right font-semibold text-green-600">
                                {{ money(v.precio_venta - v.precio_compra) }}
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

            <!-- TOTAL -->
            <div class="text-right text-xl font-bold text-emerald-600">
                Total ganado: {{ money(totalGanancia()) }}
            </div>

        </div>
    </AppLayout>
</template>
