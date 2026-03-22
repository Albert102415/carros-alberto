<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'
import { computed, onMounted, onUnmounted, ref } from 'vue'
import { Users, Car, DollarSign, TrendingUp, ShoppingCart, Clock } from 'lucide-vue-next'

const props = defineProps<{
  employees?: any[]
  carros?: any[]
  rankingCarros?: any[]
  ventasPorMes?: { mes: string; ganancia: number; unidades: number }[]
  totalInventario?: number
  totalGanado?: number
  totalGastos?: number
  cantidadDisponibles?: number
  cantidadVendidos?: number
  enTaller?: number
  apartados?: number
  ventasEsteMes?: number
  gananciaEsteMes?: number
  promedioGanancia?: number
}>()

/* ======================
   FORMAT MONEY
   ====================== */
const money = (v: number) =>
  new Intl.NumberFormat('es-MX', {
    style: 'currency',
    currency: 'MXN',
  }).format(v)

/* ======================
   TIEMPO EN INVENTARIO (created_at)
   ====================== */
const carrosConTiempo = computed(() => {
  const hoy = new Date()
  return (props.carros ?? [])
    .filter(c => c.estado !== 'vendido' && c.created_at)
    .map(c => ({
      ...c,
      dias: Math.floor(
        (hoy.getTime() - new Date(c.created_at).getTime()) / (1000 * 60 * 60 * 24)
      ),
    }))
    .sort((a, b) => b.dias - a.dias)
    .slice(0, 6)
})

const diasColor = (dias: number) => {
  if (dias > 60) return '#E24B4A'
  if (dias > 30) return '#EF9F27'
  return '#639922'
}

const diasPct = (dias: number) => Math.min((dias / 100) * 100, 100)

/* ======================
   BADGE ESTADO
   ====================== */
const badgeClass = (estado: string) => ({
  'bg-blue-600 text-white': estado === 'disponible',
  'bg-yellow-500 text-black': estado === 'apartado',
  'bg-purple-600 text-white': estado === 'taller',
  'bg-green-600 text-white': estado === 'vendido',
})

/* ======================
   MEDAL HELPER
   ====================== */
const medal = (index: number) => {
  if (index === 0) return '🥇'
  if (index === 1) return '🥈'
  if (index === 2) return '🥉'
  return `${index + 1}.`
}

/* ======================
   CHART REFS
   ====================== */
const ventasMesChart = ref<HTMLCanvasElement | null>(null)
const marcasChart = ref<HTMLCanvasElement | null>(null)
const empleadosChart = ref<HTMLCanvasElement | null>(null)
const chartsReady = ref(false)

let chartVentas: any = null
let chartMarcas: any = null
let chartEmpleados: any = null

/* ======================
   CHARTS
   ====================== */
onMounted(async () => {
  const { Chart, registerables } = await import('chart.js')
  Chart.register(...registerables)

  const dark = window.matchMedia('(prefers-color-scheme: dark)').matches
  const gridColor = dark ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.06)'
  const textColor = dark ? '#c2c0b6' : '#5f5e5a'

  // VENTAS POR MES
  if (ventasMesChart.value && props.ventasPorMes?.length) {
    chartVentas = new Chart(ventasMesChart.value, {
      type: 'bar',
      data: {
        labels: props.ventasPorMes.map(v => v.mes),
        datasets: [
          {
            label: 'Ganancia',
            data: props.ventasPorMes.map(v => v.ganancia),
            backgroundColor: '#378ADD',
            borderRadius: 4,
            yAxisID: 'y',
          },
          {
            label: 'Unidades',
            data: props.ventasPorMes.map(v => v.unidades),
            type: 'line' as any,
            borderColor: '#639922',
            backgroundColor: 'rgba(99,153,34,0.12)',
            pointBackgroundColor: '#639922',
            tension: 0.4,
            fill: true,
            yAxisID: 'y1',
            pointRadius: 4,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: {
            ticks: { color: textColor, font: { size: 11 } },
            grid: { display: false },
          },
          y: {
            position: 'left',
            ticks: {
              color: textColor,
              font: { size: 11 },
              callback: (v: any) => '$' + (Number(v) / 1000).toFixed(0) + 'K',
            },
            grid: { color: gridColor },
          },
          y1: {
            position: 'right',
            min: 0,
            max: 10,
            ticks: { color: textColor, font: { size: 11 }, stepSize: 1 },
            grid: { display: false },
          },
        },
      },
    })
  }

  // MARCAS DONUT
  if (marcasChart.value && props.carros?.length) {
    const marcas = [...new Set(props.carros.map(c => c.marca))]
    const data = marcas.map(m => props.carros!.filter(c => c.marca === m).length)
    const colors = ['#378ADD', '#639922', '#EF9F27', '#D85A30', '#7F77DD', '#1D9E75', '#D4537E']

    chartMarcas = new Chart(marcasChart.value, {
      type: 'doughnut',
      data: {
        labels: marcas,
        datasets: [{
          data,
          backgroundColor: colors.slice(0, marcas.length),
          borderWidth: 0,
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
          legend: {
            display: true,
            position: 'right',
            labels: { color: textColor, font: { size: 11 }, boxWidth: 10, padding: 10 },
          },
        },
      },
    })
  }

  // EMPLEADOS HORIZONTAL
  if (empleadosChart.value && props.employees?.length) {
    const puestos = [...new Set(props.employees.map(e => e.position))]
    const data = puestos.map(p => props.employees!.filter(e => e.position === p).length)

    chartEmpleados = new Chart(empleadosChart.value, {
      type: 'bar',
      data: {
        labels: puestos,
        datasets: [{ data, backgroundColor: '#7F77DD', borderRadius: 4 }],
      },
      options: {
        indexAxis: 'y' as any,
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: {
            ticks: { color: textColor, font: { size: 11 }, stepSize: 1 },
            grid: { color: gridColor },
          },
          y: {
            ticks: { color: textColor, font: { size: 11 } },
            grid: { display: false },
          },
        },
      },
    })
  }

  chartsReady.value = true
})

/* ======================
   DESTROY CHARTS
   ====================== */
onUnmounted(() => {
  chartVentas?.destroy()
  chartMarcas?.destroy()
  chartEmpleados?.destroy()
})
</script>

<template>

  <Head title="Dashboard" />

  <AppLayout>
    <div class="p-6 text-white space-y-8">

      <h1 class="text-2xl font-bold">Dashboard</h1>

      <!-- ================= KPIs ================= -->
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

        <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 p-5 rounded-xl shadow-lg
                    transition-transform duration-300 hover:scale-[1.05] hover:shadow-2xl">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm opacity-80">Empleados</p>
              <p class="text-3xl font-bold">{{ employees?.length ?? 0 }}</p>
              <p class="text-xs opacity-60 mt-1">activos</p>
            </div>
            <Users class="w-10 h-10 opacity-70" />
          </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 p-5 rounded-xl shadow-lg
                    transition-transform duration-300 hover:scale-[1.05] hover:shadow-2xl">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm opacity-80">En Inventario</p>
              <p class="text-3xl font-bold">{{ cantidadDisponibles ?? 0 }}</p>
              <p class="text-xs opacity-60 mt-1"> {{ enTaller ?? 0 }} taller · {{ apartados ?? 0 }} apartados</p>
            </div>
            <Car class="w-10 h-10 opacity-70" />
          </div>
        </div>

        <div class="bg-gradient-to-br from-amber-600 to-amber-800 p-5 rounded-xl shadow-lg
                    transition-transform duration-300 hover:scale-[1.05] hover:shadow-2xl">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm opacity-80">Valor Inventario</p>
              <p class="text-xl font-bold">{{ money(totalInventario ?? 0) }}</p>
              <p class="text-xs opacity-60 mt-1">costo real total</p>
            </div>
            <DollarSign class="w-10 h-10 opacity-70" />
          </div>
        </div>

        <div class="bg-gradient-to-br from-sky-600 to-sky-800 p-5 rounded-xl shadow-lg
                    transition-transform duration-300 hover:scale-[1.05] hover:shadow-2xl">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm opacity-80">Vendidos este mes</p>
              <p class="text-3xl font-bold">{{ ventasEsteMes ?? 0 }}</p>
              <p class="text-xs opacity-60 mt-1">unidades</p>
            </div>
            <ShoppingCart class="w-10 h-10 opacity-70" />
          </div>
        </div>

        <div class="bg-gradient-to-br from-green-600 to-green-800 p-5 rounded-xl shadow-lg
                    transition-transform duration-300 hover:scale-[1.05] hover:shadow-2xl">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm opacity-80">Ganancia este mes</p>
              <p class="text-xl font-bold">{{ money(gananciaEsteMes ?? 0) }}</p>
              <p class="text-xs opacity-60 mt-1">acumulado</p>
            </div>
            <TrendingUp class="w-10 h-10 opacity-70" />
          </div>
        </div>

        <div class="bg-gradient-to-br from-rose-600 to-rose-800 p-5 rounded-xl shadow-lg
                    transition-transform duration-300 hover:scale-[1.05] hover:shadow-2xl">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm opacity-80">Promedio por carro</p>
              <p class="text-xl font-bold">{{ money(promedioGanancia ?? 0) }}</p>
              <p class="text-xs opacity-60 mt-1">ganancia promedio</p>
            </div>
            <DollarSign class="w-10 h-10 opacity-70" />
          </div>
        </div>

      </div>

      <!-- ================= GRAFICAS ================= -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="p-4 border-t border-gray-800">
          <p class="font-semibold mb-2">Ganancia por mes</p>
          <div class="flex gap-4 text-xs text-gray-400 mb-3">
            <span class="flex items-center gap-1">
              <span class="inline-block w-3 h-3 rounded-sm bg-blue-500"></span> Ganancia
            </span>
            <span class="flex items-center gap-1">
              <span class="inline-block w-3 h-3 rounded-sm bg-green-500"></span> Unidades
            </span>
          </div>
          <div v-show="!chartsReady" class="h-48 bg-gray-800 animate-pulse rounded-lg" />
          <div v-show="chartsReady" style="height:200px; position:relative">
            <canvas ref="ventasMesChart" />
          </div>
        </div>

        <div class="p-4 border-t border-gray-800">
          <p class="font-semibold mb-4">Carros por marca</p>
          <div v-show="!chartsReady" class="h-48 bg-gray-800 animate-pulse rounded-lg" />
          <div v-show="chartsReady" style="height:200px; position:relative">
            <canvas ref="marcasChart" />
          </div>
        </div>

      </div>

      <!-- ================= TABLAS ================= -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="p-4 border-t border-gray-800">
          <div class="flex items-center gap-2 mb-4">
            <Clock class="w-4 h-4 text-gray-400" />
            <p class="font-semibold">Tiempo en inventario</p>
          </div>
          <table class="w-full text-sm">
            <thead class="border-b border-gray-700 text-gray-400">
              <tr>
                <th class="text-left py-2">Carro</th>
                <th class="text-center py-2">Estado</th>
                <th class="text-right py-2">Días</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="!carrosConTiempo.length">
                <td colspan="3" class="text-center text-gray-500 py-6">Sin datos</td>
              </tr>
              <tr v-for="c in carrosConTiempo" :key="c.id"
                class="border-b border-gray-800 hover:bg-gray-800 transition">
                <td class="py-2 text-sm">{{ c.marca }} {{ c.linea }} {{ c.modelo }}</td>
                <td class="py-2 text-center">
                  <span class="px-2 py-0.5 rounded text-xs font-semibold" :class="badgeClass(c.estado)">
                    {{ c.estado }}
                  </span>
                </td>
                <td class="py-2 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <div class="w-16 h-1.5 bg-gray-700 rounded-full overflow-hidden">
                      <div class="h-full rounded-full"
                        :style="{ width: diasPct(c.dias) + '%', background: diasColor(c.dias) }" />
                    </div>
                    <span class="text-xs text-gray-400 w-8 text-right">{{ c.dias }}d</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="p-4 border-t border-gray-800">
          <p class="font-semibold mb-4">Carros más rentables</p>
          <table class="w-full text-sm">
            <thead class="border-b border-gray-700 text-gray-400">
              <tr>
                <th class="text-left py-2 w-8">#</th>
                <th class="text-left py-2">Carro</th>
                <th class="text-right py-2">Ganancia</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="!rankingCarros?.length">
                <td colspan="3" class="text-center text-gray-500 py-6">Sin datos</td>
              </tr>
              <tr v-for="(c, index) in rankingCarros" :key="c.id"
                class="border-b border-gray-800 hover:bg-gray-800 transition">
                <td class="py-2 text-lg">{{ medal(index) }}</td>
                <td class="py-2">{{ c.nombre }}</td>
                <td class="text-right font-semibold text-emerald-500 py-2">
                  {{ money(c.ganancia) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>

      <!-- ================= EMPLEADOS ================= -->
      <div class="p-4 border-t border-gray-800">
        <p class="font-semibold mb-4">Empleados por puesto</p>
        <div v-show="!chartsReady" class="h-32 bg-gray-800 animate-pulse rounded-lg" />
        <div v-show="chartsReady" style="height:160px; position:relative">
          <canvas ref="empleadosChart" />
        </div>
      </div>

    </div>
  </AppLayout>
</template>