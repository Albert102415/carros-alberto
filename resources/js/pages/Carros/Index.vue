<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Pencil, Trash, CirclePlus, DollarSign } from 'lucide-vue-next'

const props = defineProps<{ carros: any[] }>()

const deleteCarro = (id: number) => {
  if (!confirm('¿Seguro que deseas eliminar este carro?')) return

  router.delete(`/carros/${id}`, {
    preserveScroll: true,
  })
}

const venderCarro = (id: number) => {
  router.visit(`/carros/${id}/edit`)
}

const formatMoney = (value: number | null) => {
  if (!value) return '—'
  return new Intl.NumberFormat('es-MX', {
    style: 'currency',
    currency: 'MXN',
  }).format(value)
}

const calcularGanancia = (carro: any) => {
  if (
    carro.estado === 'vendido' &&
    carro.precio_venta !== null &&
    carro.precio_compra !== null
  ) {
    return carro.precio_venta - carro.precio_compra
  }
  return null
}

</script>

<template>

  <Head title="Carros" />

  <AppLayout>
    <div class="flex flex-1 flex-col gap-4 rounded-xl p-4">

      <!-- BOTÓN NUEVO -->
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Inventario de Carros</h1>

        <Button as-child size="sm" class="bg-indigo-600 hover:white-700">
          <a href="/carros/create">
            <CirclePlus class="mr-2" /> Nuevo
          </a>
        </Button>
      </div>

      <!-- TABLA -->
      <div class="overflow-auto rounded-xl border">
        <table class="w-full border-collapse text-sm">
          <thead class="bg-black text-white sticky top-0">
            <tr>
              <th class="p-2 text-left">Marca</th>
              <th class="p-2">Modelo</th>
              <th class="p-2">Fecha de Venta</th>
              <th class="p-2">Estado</th>
              <th class="p-2 text-right">Compra</th>
              <th class="p-2 text-right">Venta</th>
              <th class="p-2 text-right">Ganancia</th>
              <th class="p-2 text-center">Acciones</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="carro in props.carros" :key="carro.id" class="border-b hover:bg-gray-50 dark:hover:bg-gray-800">
              <td class="p-2 font-medium">
                {{ carro.marca }} {{ carro.linea }}
              </td>
              <td class="p-2">{{ carro.modelo }}</td>
              <td class="p-2">{{ carro.anio }}</td>

              <!-- ESTADO -->
              <td class="p-2 text-center">
                <span class="px-2 py-1 rounded-full text-xs font-semibold" :class="{
                  'bg-green-600 text-white': carro.estado === 'vendido',
                  'bg-yellow-500 text-black': carro.estado === 'apartado',
                  'bg-blue-600 text-white': carro.estado === 'disponible',
                }">
                  {{ carro.estado }}
                </span>
              </td>

              <!-- PRECIOS -->
              <td class="p-2 text-right">{{ formatMoney(carro.precio_compra) }}</td>
              <td class="p-2 text-right">{{ formatMoney(carro.precio_venta) }}</td>

              <!-- GANANCIA -->
              <td class="p-2 text-right font-semibold">
                <span v-if="calcularGanancia(carro) !== null"
                  :class="calcularGanancia(carro) >= 0 ? 'text-green-600' : 'text-red-600'">
                  {{ formatMoney(calcularGanancia(carro)) }}
                </span>
                <span v-else class="text-gray-400">—</span>
              </td>


              <!-- ACCIONES -->
              <td class="p-2 flex justify-center gap-2">
                <Button size="sm" variant="outline" as-child>
                  <a :href="`/carros/${carro.id}/edit`">
                    <Pencil />
                  </a>
                </Button>

                <Button size="sm" class="bg-emerald-600 hover:bg-emerald-700 text-white"
                  v-if="carro.estado !== 'vendido'" @click="venderCarro(carro.id)">
                  <DollarSign />
                </Button>

                <Button size="sm" class="bg-rose-600 hover:bg-rose-700 text-white" @click="deleteCarro(carro.id)">
                  <Trash />
                </Button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </AppLayout>
</template>
