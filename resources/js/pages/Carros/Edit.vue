<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

const props = defineProps<{ carro: any }>()

const form = ref({
  id: props.carro.id,
  marca: props.carro.marca ?? '',
  linea: props.carro.linea ?? '',
  modelo: props.carro.modelo ?? '',
  anio: props.carro.anio ?? '',
  color: props.carro.color ?? '',
  precio_compra: props.carro.precio_compra ?? '',
  precio_venta: props.carro.precio_venta ?? '',
  estado: props.carro.estado ?? 'disponible',
})

const submit = () => {
  router.put(`/carros/${form.value.id}`, form.value)
}
</script>

<template>

  <Head title="Edit Carro" />

  <AppLayout>
    <div class="p-4 max-w-lg space-y-6">
      <h1 class="text-2xl font-bold">Editar / Vender Carro</h1>

      <form @submit.prevent="submit" class="space-y-4">

        <div>
          <Label>Marca</Label>
          <Input v-model="form.marca" required />
        </div>

        <div>
          <Label>Línea</Label>
          <Input v-model="form.linea" required />
        </div>

        <div>
          <Label>Modelo</Label>
          <Input v-model="form.modelo" required />
        </div>

        <div>
          <Label>Fecha de Venta</Label>
          <Input type="number" v-model="form.anio" required />
        </div>

        <div>
          <Label>Color</Label>
          <Input v-model="form.color" />
        </div>

        <div>
          <Label>Precio de compra</Label>
          <Input type="number" step="0.01" v-model="form.precio_compra" />
        </div>

        <!-- SOLO SI SE VENDE -->
        <div v-if="form.estado === 'vendido'">
          <Label>Precio de venta</Label>
          <Input type="number" step="0.01" v-model="form.precio_venta" required />
        </div>

        <div>
          <Label>Estado</Label>
          <select v-model="form.estado"
            class="w-full rounded-md border border-gray-300 bg-background px-3 py-2 text-sm">
            <option value="disponible">Disponible</option>
            <option value="apartado">Apartado</option>
            <option value="vendido">Vendido</option>
          </select>
        </div>

        <div class="flex gap-4 pt-4">
          <Button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white">
            Guardar cambios
          </Button>

          <Button as="a" href="/carros" variant="outline">
            Cancelar
          </Button>
        </div>

      </form>
    </div>
  </AppLayout>
</template>
