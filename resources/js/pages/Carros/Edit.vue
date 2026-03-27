<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

const props = defineProps<{ carro: any }>()

const page = usePage()
const mensaje = computed(() => (page.props.flash as any)?.success)

/* =============================
   FORMAT MONEY
============================= */
const money = (v: number) =>
  new Intl.NumberFormat('es-MX', {
    style: 'currency',
    currency: 'MXN',
  }).format(v)

/* =============================
   IMAGEN
============================= */
const imagenPreview = ref<string | null>(
  props.carro.imagen
    ? `/storage/${props.carro.imagen}`
    : null
)

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

const quitarImagen = () => {
  imagenPreview.value = null
  form.value.imagen = null
}

/* =============================
   FORM CARRO
============================= */
const form = ref({
  id: props.carro.id,
  marca: props.carro.marca ?? '',
  linea: props.carro.linea ?? '',
  modelo: props.carro.modelo ?? '',
  color: props.carro.color ?? '',
  proveedor: props.carro.proveedor ?? '',
  precio_compra: props.carro.precio_compra ?? 0,
  precio_venta: props.carro.precio_venta ?? 0,
  fecha_venta: props.carro.fecha_venta ?? '',
  estado: props.carro.estado ?? 'disponible',
  imagen: null as File | null,
})

const loading = ref(false)

const submit = () => {
  loading.value = true
  const data = new FormData()

  Object.keys(form.value).forEach((key) => {
    const value = form.value[key as keyof typeof form.value]
    if (value !== null && value !== undefined) {
      data.append(key, value as any)
    }
  })

  data.append('_method', 'PUT')

  router.post(`/carros/${form.value.id}`, data, {
    forceFormData: true,
    onFinish: () => { loading.value = false },
  })
}

/* =============================
   GASTOS
============================= */
const gasto = ref({
  concepto: '',
  monto: '',
})

const addGasto = () => {
  if (!gasto.value.concepto || !gasto.value.monto) return
  router.post(`/carros/${form.value.id}/gastos`, gasto.value, {
    preserveScroll: true,
    onSuccess: () => {
      gasto.value = { concepto: '', monto: '' }
    },
  })
}

const eliminarGasto = (id: number) => {
  if (!confirm('¿Eliminar este gasto?')) return
  router.delete(`/gastos/${id}`, { preserveScroll: true })
}

/* =============================
   CALCULOS
============================= */
const totalGastos = computed(() =>
  props.carro.gastos.reduce((sum: number, g: any) => sum + Number(g.monto), 0)
)

const costoReal = computed(() =>
  Number(form.value.precio_compra) + totalGastos.value
)

/* =============================
   BADGE ESTADO
============================= */
const badgeClass = computed(() => ({
  'bg-blue-600 text-white': form.value.estado === 'disponible',
  'bg-yellow-500 text-black': form.value.estado === 'apartado',
  'bg-purple-600 text-white': form.value.estado === 'taller',
  'bg-green-500 text-white': form.value.estado === 'vendido',
}))
</script>

<template>

  <Head title="Editar Carro" />

  <AppLayout>
    <div class="p-4 flex gap-8">

      <div class="max-w-xl w-1/2 space-y-6">

        <div class="flex items-center gap-4">
          <h1 class="text-2xl font-bold">Editar / Gastos del Carro</h1>
          <span :class="badgeClass" class="px-3 py-1 rounded-full text-white text-xs font-bold uppercase">
            {{ form.estado }}
          </span>
        </div>

        <!-- MENSAJE ÉXITO -->
        <div v-if="mensaje" class="bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300
                 px-4 py-3 rounded-lg text-sm font-medium">
          ✅ {{ mensaje }}
        </div>

        <!-- FORM -->
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
            <Label>Color</Label>
            <Input v-model="form.color" />
          </div>

          <div>
            <Label>Proveedor</Label>
            <Input v-model="form.proveedor" />
          </div>

          <div>
            <Label>Precio de compra</Label>
            <Input type="number" step="0.01" v-model="form.precio_compra" />
          </div>

          <div>
            <Label>Cambiar imagen</Label>
            <Input type="file" accept="image/*" @change="handleImageChange" />
            <Button v-if="imagenPreview" type="button" variant="outline"
              class="mt-2 text-red-500 border-red-400 hover:bg-red-50" @click="quitarImagen">
              Quitar imagen
            </Button>
          </div>

          <div v-if="form.estado === 'vendido'">
            <Label>Precio de venta</Label>
            <Input type="number" step="0.01" v-model="form.precio_venta" required />
          </div>

          <div v-if="form.estado === 'vendido'">
            <Label>Fecha de venta</Label>
            <Input type="date" v-model="form.fecha_venta" required />
          </div>

          <div>
            <Label>Estado</Label>
            <select v-model="form.estado"
              class="w-full rounded-md border border-gray-300 bg-background px-3 py-2 text-sm">
              <option value="disponible">Disponible</option>
              <option value="taller">Taller</option>
              <option value="apartado">Apartado</option>
              <option value="vendido">Vendido</option>
            </select>
          </div>

          <div class="flex gap-4 pt-4">
            <Button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white" :disabled="loading">
              {{ loading ? 'Guardando...' : 'Guardar cambios' }}
            </Button>
            <Button as="a" href="/carros" variant="outline">
              Cancelar
            </Button>
          </div>

        </form>

        <!-- GASTOS -->
        <h2 class="text-xl font-bold mt-8">Gastos del traslado</h2>

        <form @submit.prevent="addGasto" class="flex gap-2">
          <Input v-model="gasto.concepto" placeholder="Gasolina / Casetas / Placas" />
          <Input type="number" step="0.01" v-model="gasto.monto" placeholder="Monto" />
          <Button type="submit">Agregar</Button>
        </form>

        <ul class="mt-4 space-y-2">
          <li v-for="g in props.carro.gastos" :key="g.id" class="flex justify-between items-center border-b py-1">
            <div>
              <p class="font-medium">{{ g.concepto }}</p>
              <p class="text-sm text-gray-500">{{ money(Number(g.monto)) }}</p>
            </div>
            <Button size="sm" class="bg-red-600 hover:bg-red-700 text-white" @click="eliminarGasto(g.id)">
              Eliminar
            </Button>
          </li>
        </ul>

        <!-- RESUMEN -->
        <div class="mt-4 p-4 rounded-lg bg-gray-100 dark:bg-gray-800 space-y-1">
          <p><strong>Compra base:</strong> {{ money(Number(form.precio_compra)) }}</p>
          <p><strong>Total gastos:</strong> {{ money(totalGastos) }}</p>
          <p class="text-lg font-bold text-emerald-600">
            Costo real: {{ money(costoReal) }}
          </p>
        </div>

      </div>

      <!-- DERECHA (IMAGEN) -->
      <div class="w-1/2 flex items-stretch justify-center">
        <div v-if="imagenPreview" class="w-full space-y-2">
          <img :src="imagenPreview" class="rounded-xl shadow-xl w-full h-[350px] object-cover" />
        </div>
        <div v-else class="text-gray-400 text-center self-center">
          La imagen del vehículo se verá aquí
        </div>
      </div>

    </div>
  </AppLayout>
</template>