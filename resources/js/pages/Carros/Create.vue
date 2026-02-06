<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

type BreadcrumbItem = { title: string; href: string }
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Carros', href: '/carros' },
  { title: 'Create', href: '#' },
]

const form = ref({
  marca: '',
  linea: '',
  modelo: '',
  anio: undefined as number | undefined,
  color: '',
  precio_compra: undefined as number | undefined,
  estado: 'disponible',
})

const resetForm = () => {
  form.value = {
    marca: '',
    linea: '',
    modelo: '',
    anio: undefined,
    color: '',
    precio_compra: undefined,
    estado: 'disponible',
  }
}

const submit = () => {
  router.post('/carros', form.value, {
    onSuccess: resetForm,
  })
}
</script>

<template>

  <Head title="Create Carro" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-1 flex-col gap-4 rounded-xl p-4">
      <h1 class="text-2xl font-bold">Crear Carro</h1>

      <form @submit.prevent="submit" class="space-y-6 max-w-lg">

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
          <Input type="number" step="0.01" v-model="form.precio_compra" required />
        </div>

        <div>
          <Label>Estado</Label>
          <select v-model="form.estado"
            class="w-full rounded-md border border-gray-700 bg-background px-3 py-2 text-sm">
            <option value="disponible">Disponible</option>
            <option value="apartado">Apartado</option>
            <option value="vendido">Vendido</option>
          </select>
        </div>

        <div class="flex gap-4">
          <Button type="submit" class="bg-indigo-500 hover:bg-indigo-600">
            Guardar
          </Button>
          <Button as="a" href="/carros" variant="outline">
            Cancelar
          </Button>
        </div>

      </form>
    </div>
  </AppLayout>
</template>
