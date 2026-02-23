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
  color: '',
  proveedor: '',
  precio_compra: undefined as number | undefined,
  estado: 'disponible',
  imagen: null as File | null,
})

const resetForm = () => {
  form.value = {
    marca: '',
    linea: '',
    modelo: '',
    color: '',
    proveedor: '',
    precio_compra: undefined,
    estado: 'disponible',
    imagen: null as File | null,

  }
}

const submit = () => {
  const data = new FormData()

  Object.keys(form.value).forEach((key) => {
    const value = form.value[key as keyof typeof form.value]
    if (value !== null && value !== undefined) {
      data.append(key, value as any)
    }
  })

  router.post('/carros', data, {
    forceFormData: true,
    onSuccess: resetForm,
  })
}

const imagenPreview = ref<string | null>(null)

const handleImageChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files && target.files[0]) {
    form.value.imagen = target.files[0]

    const reader = new FileReader()
    reader.onload = () => {
      imagenPreview.value = reader.result as string
    }
    reader.readAsDataURL(target.files[0])
  }
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
          <Label>Proveedor</Label>
          <Input v-model="form.proveedor" required />
        </div>

        <div>
          <Label>Color</Label>
          <Input v-model="form.color" />
        </div>

        <div>
          <Label>Foto del vehículo</Label>
          <Input type="file" accept="image/*" @change="handleImageChange" />
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
      <div class="w-1/2 flex items-center justify-center">
        <div v-if="imagenPreview" class="w-full">
          <img :src="imagenPreview" class="rounded-xl shadow-xl w-full h-[400px] object-cover" />
        </div>
        <div v-else class="text-gray-400 text-center">
          La imagen se verá aquí
        </div>
      </div>
    </div>
  </AppLayout>
</template>
