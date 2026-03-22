<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Pencil, Trash, CirclePlus, Search, X, Car } from 'lucide-vue-next'
import { ref, computed } from 'vue'

const props = defineProps<{ carros: any[] }>()

/* =========================
   DELETE
========================= */
const deleteCarro = (id: number) => {
  if (!confirm('¿Seguro que deseas eliminar este carro?')) return
  router.delete(`/carros/${id}`, { preserveScroll: true })
}

/* =========================
   MONEY FORMAT
========================= */
const formatMoney = (value: number | null) => {
  if (value === null || value === undefined) return '—'
  return new Intl.NumberFormat('es-MX', {
    style: 'currency',
    currency: 'MXN',
  }).format(value)
}

/* =========================
   FILTROS
========================= */
const filtroMarca = ref('')
const filtroLinea = ref('')
const filtroAnio = ref('')
const filtroEstado = ref('')
const buscador = ref('')

const limpiarFiltros = () => {
  filtroMarca.value = ''
  filtroLinea.value = ''
  filtroAnio.value = ''
  filtroEstado.value = ''
  buscador.value = ''
  paginaActual.value = 1
}

const hayFiltrosActivos = computed(() =>
  filtroMarca.value || filtroLinea.value || filtroAnio.value || filtroEstado.value || buscador.value
)

/* =========================
   ORDENAMIENTO
========================= */
const ordenarPor = ref('id')
const direccion = ref('asc')

/* =========================
   PAGINACION
========================= */
const paginaActual = ref(1)
const porPagina = 8

/* =========================
   OPCIONES SELECT
========================= */
const marcas = computed(() => [...new Set(props.carros.map(c => c.marca))].sort())
const lineas = computed(() => [...new Set(props.carros.map(c => c.linea))].sort())
const anios = computed(() => [...new Set(props.carros.map(c => c.anio))].sort((a, b) => b - a))
const estados = ['disponible', 'vendido', 'apartado', 'taller']

/* =========================
   FILTRADO
========================= */
const carrosFiltrados = computed(() => {
  let data = props.carros.filter(c => {
    if (buscador.value) {
      const text = `${c.marca} ${c.linea} ${c.modelo}`.toLowerCase()
      if (!text.includes(buscador.value.toLowerCase())) return false
    }
    if (filtroMarca.value && c.marca !== filtroMarca.value) return false
    if (filtroLinea.value && c.linea !== filtroLinea.value) return false
    if (filtroAnio.value && c.anio != filtroAnio.value) return false
    if (filtroEstado.value && c.estado !== filtroEstado.value) return false
    return true
  })

  data.sort((a, b) => {
    let v1 = a[ordenarPor.value] ?? 0
    let v2 = b[ordenarPor.value] ?? 0
    if (ordenarPor.value === 'fecha_venta') {
      v1 = new Date(v1)
      v2 = new Date(v2)
    }
    return direccion.value === 'desc' ? (v1 > v2 ? 1 : -1) : (v1 < v2 ? 1 : -1)
  })

  return data
})

/* =========================
   PAGINACION
========================= */
const totalPaginas = computed(() =>
  Math.ceil(carrosFiltrados.value.length / porPagina)
)

const carrosPaginados = computed(() => {
  const inicio = (paginaActual.value - 1) * porPagina
  return carrosFiltrados.value.slice(inicio, inicio + porPagina)
})

const cambiarPagina = (p: number) => {
  paginaActual.value = p
}
</script>

<template>

  <Head title="Carros" />
  <AppLayout>
    <div class="flex flex-col gap-4 p-4">

      <!-- HEADER -->
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Inventario de Carros</h1>
        <Button as-child size="sm" class="bg-indigo-600 hover:bg-indigo-700 text-white">
          <a href="/carros/create">
            <CirclePlus class="mr-1" /> Agregar
          </a>
        </Button>
      </div>

      <!-- BUSCADOR + FILTROS -->
      <div class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 flex flex-col gap-3">

        <div class="relative max-w-sm">
          <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
          <input v-model="buscador" placeholder="Buscar por marca, línea, modelo..." class="border border-gray-300 dark:border-gray-600 rounded-lg pl-9 pr-3 py-2 w-full
                   bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                   placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" />
        </div>

        <div class="flex flex-wrap gap-2 items-center">

          <select v-model="filtroMarca" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm
                   bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">🏷 Marca</option>
            <option v-for="m in marcas" :key="m" :value="m">{{ m }}</option>
          </select>

          <select v-model="filtroLinea" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm
                   bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">🚘 Línea</option>
            <option v-for="l in lineas" :key="l" :value="l">{{ l }}</option>
          </select>

          <select v-model="filtroAnio" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm
                   bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">📅 Año</option>
            <option v-for="a in anios" :key="a" :value="a">{{ a }}</option>
          </select>

          <select v-model="filtroEstado" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm
                   bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">⚡ Estado</option>
            <option v-for="e in estados" :key="e" :value="e">{{ e }}</option>
          </select>

          <select v-model="ordenarPor" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm
                   bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="id">↕ Orden registro</option>
            <option value="ganancia_real">↕ Ganancia</option>
            <option value="fecha_venta">↕ Fecha venta</option>
          </select>

          <select v-model="direccion" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm
                   bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="asc">Ascendente</option>
            <option value="desc">Descendente</option>
          </select>

          <button v-if="hayFiltrosActivos" @click="limpiarFiltros" class="flex items-center gap-1 px-3 py-2 rounded-lg text-sm
                   bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900 dark:text-red-300
                   transition font-medium">
            <X class="w-4 h-4" /> Limpiar
          </button>

        </div>

        <p class="text-xs text-gray-500 dark:text-gray-400">
          {{ carrosFiltrados.length }} resultado{{ carrosFiltrados.length !== 1 ? 's' : '' }} encontrado{{
            carrosFiltrados.length !== 1 ? 's' : '' }}
        </p>

      </div>

      <!-- TARJETAS -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">

        <div v-if="!carrosPaginados.length" class="col-span-full text-center text-gray-400 py-12">
          No se encontraron carros con esos filtros
        </div>

        <div v-for="carro in carrosPaginados" :key="carro.id" class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden
                 hover:shadow-lg transition-shadow duration-200 bg-white dark:bg-gray-900">
          <!-- IMAGEN -->
          <div class="relative h-40 bg-gray-100 dark:bg-gray-800">
            <img v-if="carro.imagen" :src="`/storage/${carro.imagen}`" :alt="`${carro.marca} ${carro.modelo}`"
              class="w-full h-full object-cover" />
            <div v-else class="w-full h-full flex items-center justify-center">
              <Car class="w-12 h-12 text-gray-400" />
            </div>

            <!-- Badge estado -->
            <span class="absolute top-2 right-2 px-2 py-0.5 rounded text-xs font-semibold" :class="{
              'bg-blue-600 text-white': carro.estado === 'disponible',
              'bg-yellow-500 text-black': carro.estado === 'apartado',
              'bg-purple-600 text-white': carro.estado === 'taller',
              'bg-green-600 text-white': carro.estado === 'vendido',
            }">
              {{ carro.estado }}
            </span>
          </div>

          <!-- INFO -->
          <div class="p-3 space-y-2">

            <!-- Nombre del carro -->
            <p class="font-semibold text-sm leading-tight">
              {{ carro.marca }} {{ carro.linea }} {{ carro.modelo }}
            </p>
            <p class="text-xs text-gray-400">{{ carro.color }}</p>

            <hr class="border-gray-200 dark:border-gray-700" />

            <!-- Proveedor y fecha -->
            <div class="flex justify-between items-center text-xs">
              <span class="text-gray-400">Proveedor</span>
              <span class="font-medium">{{ carro.proveedor ?? '—' }}</span>
            </div>

            <div class="flex justify-between items-center text-xs">
              <span class="text-gray-400">Fecha venta</span>
              <span class="font-medium">{{ carro.fecha_venta ?? '—' }}</span>
            </div>

            <hr class="border-gray-200 dark:border-gray-700" />

            <!-- Costo real -->
            <div class="flex justify-between items-center">
              <span class="text-xs text-gray-400">Costo real</span>
              <span class="text-sm font-bold text-emerald-600">
                {{ formatMoney(carro.costo_real) }}
              </span>
            </div>

            <!-- Ganancia (solo si vendido) -->
            <div v-if="carro.estado === 'vendido'" class="flex justify-between items-center">
              <span class="text-xs text-gray-400">Ganancia</span>
              <span class="text-sm font-bold" :class="carro.ganancia_real >= 0 ? 'text-green-600' : 'text-red-500'">
                {{ formatMoney(carro.ganancia_real) }}
              </span>
            </div>

          </div>
          <!-- ACCIONES -->
          <div class="px-3 pb-3 flex gap-2">
            <Button size="sm" variant="outline" as-child class="flex-1">
              <a :href="`/carros/${carro.id}/edit`" class="flex items-center justify-center gap-1">
                <Pencil class="w-3 h-3" /> Editar
              </a>
            </Button>
            <Button size="sm" class="bg-red-600 text-white hover:bg-red-700" @click="deleteCarro(carro.id)">
              <Trash class="w-3 h-3" />
            </Button>
          </div>
        </div>

      </div>

      <!-- PAGINACION -->
      <div class="flex gap-2 justify-center mt-2">
        <button v-for="p in totalPaginas" :key="p" @click="cambiarPagina(p)" class="px-3 py-1 border rounded transition"
          :class="p === paginaActual
            ? 'bg-indigo-600 text-white border-indigo-600'
            : 'hover:bg-gray-100 dark:hover:bg-gray-700'">
          {{ p }}
        </button>
      </div>

    </div>
  </AppLayout>
</template>