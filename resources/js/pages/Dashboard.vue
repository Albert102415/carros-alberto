<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'
import { computed, onMounted, ref } from 'vue'
import { Users, Car, DollarSign } from 'lucide-vue-next'

const props = defineProps<{
  employees?: any[]
  carros?: any[]
  rankingCarros?: any[]
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
   KPIs
   ====================== */
const totalEmployees = computed(() => props.employees?.length ?? 0)

const totalCarros = computed(() =>
  props.carros?.filter(c => c.estado !== 'vendido').length ?? 0
)

const totalInventario = computed(() =>
  props.carros
    ?.filter(c => c.estado !== 'vendido')
    .reduce((sum, c) => sum + Number(c.costo_real ?? 0), 0)
)

/* ======================
   CHART REFS
   ====================== */
const carrosChart = ref<HTMLCanvasElement | null>(null)
const empleadosChart = ref<HTMLCanvasElement | null>(null)

/* ======================
   CHARTS
   ====================== */
onMounted(async () => {
  const { Chart, registerables } = await import('chart.js')
  Chart.register(...registerables)

  // EMPLEADOS
  if (empleadosChart.value && props.employees?.length) {
    const puestos = [...new Set(props.employees.map(e => e.position))]
    const data = puestos.map(
      p => props.employees!.filter(e => e.position === p).length
    )

    new Chart(empleadosChart.value, {
      type: 'bar',
      data: {
        labels: puestos,
        datasets: [{ data, backgroundColor: '#22C55E' }],
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          x: { ticks: { color: '#E5E7EB' } },
          y: { ticks: { color: '#E5E7EB' }, beginAtZero: true },
        },
      },
    })
  }

  // CARROS
  if (carrosChart.value && props.carros?.length) {
    const marcas = [...new Set(props.carros.map(c => c.marca))]
    const data = marcas.map(m => props.carros!.filter(c => c.marca === m).length)

    new Chart(carrosChart.value, {
      type: 'doughnut',
      data: {
        labels: marcas,
        datasets: [{ data }],
      },
      options: {
        responsive: true,
        plugins: {
          legend: { labels: { color: '#E5E7EB' } },
        },
      },
    })
  }
})
</script>

<template>

  <Head title="Dashboard" />

  <AppLayout>
    <div class="p-6 text-white space-y-10">

      <h1 class="text-2xl font-bold">Dashboard</h1>

      <!-- ================= KPIs ================= -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 p-5 rounded-xl shadow-lg
                 transition-transform duration-300 hover:scale-[1.05] hover:shadow-2xl">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm opacity-80">Empleados</p>
              <p class="text-3xl font-bold">{{ totalEmployees }}</p>
            </div>
            <Users class="w-10 h-10 opacity-80" />
          </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 p-5 rounded-xl shadow-lg
                 transition-transform duration-300 hover:scale-[1.05] hover:shadow-2xl">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm opacity-80">Carros en Inventario</p>
              <p class="text-3xl font-bold">{{ totalCarros }}</p>
            </div>
            <Car class="w-10 h-10 opacity-80" />
          </div>
        </div>

        <div class="bg-gradient-to-br from-amber-600 to-amber-800 p-5 rounded-xl shadow-lg
                 transition-transform duration-300 hover:scale-[1.05] hover:shadow-2xl">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm opacity-80">Valor Inventario</p>
              <p class="text-2xl font-bold">
                {{ money(totalInventario) }}
              </p>
            </div>
            <DollarSign class="w-10 h-10 opacity-80" />
          </div>
        </div>

      </div>

      <!-- ================= GRAFICAS ================= -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="p-4 border-t border-gray-800">
          <p class="font-semibold mb-4">Carros por Marca</p>
          <canvas ref="carrosChart" />
        </div>

        <div class="p-4 border-t border-gray-800">
          <p class="font-semibold mb-4">Empleados por Puesto</p>
          <canvas ref="empleadosChart" />
        </div>

      </div>

      <!-- ================= ULTIMOS CARROS ================= -->
      <div class="p-4 border-t border-gray-800">
        <p class="font-semibold mb-4">Últimos Carros en Inventario</p>

        <table class="w-full text-sm">
          <thead class="border-b border-gray-700 text-gray-400">
            <tr>
              <th class="text-left">Marca</th>
              <th class="text-center">Modelo</th>
              <th class="text-center">Año</th>
              <th class="text-right">Costo Real</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="carro in props.carros
              ?.filter(c => c.estado !== 'vendido')
              .slice(-5)" :key="carro.id" class="border-b border-gray-800 hover:bg-gray-800 transition">
              <td>{{ carro.marca }}</td>
              <td class="text-center">{{ carro.modelo }}</td>
              <td class="text-center">{{ carro.anio }}</td>
              <td class="text-right font-semibold">
                {{ money(carro.costo_real) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- ================= RANKING ================= -->
      <div class="p-4 border-t border-gray-800">
        <p class="font-semibold mb-4">Carros más rentables</p>

        <table class="w-full text-sm">
          <thead class="border-b border-gray-700 text-gray-400">
            <tr>
              <th class="text-left">Carro</th>
              <th class="text-right">Ganancia</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="c in props.rankingCarros" :key="c.id"
              class="border-b border-gray-800 hover:bg-gray-800 transition">
              <td>{{ c.nombre }}</td>
              <td class="text-right font-semibold text-emerald-500">
                {{ money(c.ganancia) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </AppLayout>
</template>