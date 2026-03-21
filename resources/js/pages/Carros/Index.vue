<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Pencil, Trash, CirclePlus } from 'lucide-vue-next'
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

const marcas = computed(() => [...new Set(props.carros.map(c => c.marca))])
const lineas = computed(() => [...new Set(props.carros.map(c => c.linea))])
const anios = computed(() => [...new Set(props.carros.map(c => c.anio))])
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

  /* =========================
     ORDENAMIENTO
  ========================= */

  data.sort((a, b) => {

    let v1 = a[ordenarPor.value] ?? 0
    let v2 = b[ordenarPor.value] ?? 0

    if (ordenarPor.value === 'fecha_venta') {
      v1 = new Date(v1)
      v2 = new Date(v2)
    }

    if (direccion.value === 'desc') {
      return v1 > v2 ? 1 : -1
    } else {
      return v1 < v2 ? 1 : -1
    }

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
  const fin = inicio + porPagina

  return carrosFiltrados.value.slice(inicio, fin)

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
            <CirclePlus /> Add
          </a>
        </Button>

      </div>

      <!-- BUSCADOR -->

      <input v-model="buscador" placeholder="Buscar carro..." class="border rounded px-3 py-2 w-full max-w-sm" />

      <!-- FILTROS -->

      <div class="flex flex-wrap gap-2">

        <select v-model="filtroMarca" class="border rounded px-2 py-1">
          <option value="">Marca</option>
          <option v-for="m in marcas" :key="m">{{ m }}</option>
        </select>

        <select v-model="filtroLinea" class="border rounded px-2 py-1">
          <option value="">Linea</option>
          <option v-for="l in lineas" :key="l">{{ l }}</option>
        </select>

        <select v-model="filtroAnio" class="border rounded px-2 py-1">
          <option value="">Año</option>
          <option v-for="a in anios" :key="a">{{ a }}</option>
        </select>

        <select v-model="filtroEstado" class="border rounded px-2 py-1">
          <option value="">Estado</option>
          <option v-for="e in estados" :key="e">{{ e }}</option>
        </select>

      </div>

      <!-- ORDEN -->

      <div class="flex gap-2">

        <select v-model="ordenarPor" class="border rounded px-2 py-1">
          <option value="id">Orden registro</option>
          <option value="ganancia_real">Ganancia</option>
          <option value="fecha_venta">Fecha venta</option>
        </select>

        <select v-model="direccion" class="border rounded px-2 py-1">
          <option value="asc">Ascendente</option>
          <option value="desc">Descendente</option>
        </select>

      </div>

      <!-- TABLA -->

      <div class="overflow-auto border rounded-xl">

        <table class="w-full text-sm">

          <thead class="bg-black text-white">
            <tr>
              <th class="p-2 text-left">Carro</th>
              <th class="p-2 text-center">Proveedor</th>
              <th class="p-2 text-center">Fecha venta</th>
              <th class="p-2 text-center">Estado</th>
              <th class="p-2 text-right">Compra</th>
              <th class="p-2 text-right">Venta</th>
              <th class="p-2 text-right">Ganancia</th>
              <th class="p-2 text-center">Acciones</th>
            </tr>
          </thead>

          <tbody>

            <tr v-for="carro in carrosPaginados" :key="carro.id"
              class="border-b hover:bg-gray-100 dark:hover:bg-gray-800">

              <td class="p-2 font-medium">
                {{ carro.marca }} {{ carro.linea }} {{ carro.modelo }} {{ carro.color }}
              </td>

              <td class="text-center">{{ carro.proveedor }}</td>

              <td class="text-center">{{ carro.fecha_venta }}</td>

              <td class="text-center">

                <span class="px-2 py-1 rounded text-xs" :class="{
                  'bg-green-600 text-white': carro.estado === 'vendido',
                  'bg-purple-600 text-white': carro.estado === 'taller',
                  'bg-yellow-500 text-black': carro.estado === 'apartado',
                  'bg-blue-600 text-white': carro.estado === 'disponible'
                }">
                  {{ carro.estado }}
                </span>

              </td>

              <td class="text-right">{{ formatMoney(carro.costo_real) }}</td>
              <td class="text-right">{{ formatMoney(carro.precio_venta) }}</td>

              <td class="text-right font-bold">

                <span v-if="carro.estado === 'vendido'"
                  :class="carro.ganancia_real >= 0 ? 'text-green-600' : 'text-red-600'">
                  {{ formatMoney(carro.ganancia_real) }}
                </span>

                <span v-else class="text-gray-400">—</span>

              </td>

              <td class="p-2 flex justify-center gap-2">

                <Button size="sm" variant="outline" as-child>
                  <a :href="`/carros/${carro.id}/edit`">
                    <Pencil />
                  </a>
                </Button>

                <Button size="sm" class="bg-red-600 text-white" @click="deleteCarro(carro.id)">
                  <Trash />
                </Button>

              </td>

            </tr>

          </tbody>

        </table>

      </div>

      <!-- PAGINACION -->

      <div class="flex gap-2 justify-center mt-4">

        <button v-for="p in totalPaginas" :key="p" @click="cambiarPagina(p)" class="px-3 py-1 border rounded"
          :class="p === paginaActual ? 'bg-indigo-600 text-white' : ''">
          {{ p }}
        </button>

      </div>

    </div>

  </AppLayout>
</template>