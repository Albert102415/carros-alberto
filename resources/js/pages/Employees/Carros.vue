<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { ref, computed } from 'vue'

const props = defineProps<{
    employee: {
        id: number
        nombre: string
        carros: any[]
    }
    carros: any[]
}>()

/* =========================
FILTROS
========================= */

const filtroMarca = ref('')
const filtroPago = ref('')

const marcas = computed(() => {
    return [...new Set(props.carros.map(c => c.marca))]
})

/* =========================
CONTADOR
========================= */

const totalAsignados = computed(() => {
    return props.employee.carros?.length ?? 0
})

/* =========================
FILTRADO + ORDEN
========================= */

const carrosFiltrados = computed(() => {

    let data = props.carros

    // filtro marca
    if (filtroMarca.value) {
        data = data.filter(c => c.marca === filtroMarca.value)
    }

    // filtro pago
    if (filtroPago.value) {

        data = data.filter(c => {

            const asignado = props.employee.carros?.find(e => e.id === c.id)

            if (!asignado) return false

            if (filtroPago.value === 'pagado') {
                return asignado.pivot?.pagado
            }

            if (filtroPago.value === 'nopagado') {
                return !asignado.pivot?.pagado
            }

            return true

        })

    }

    // orden descendente
    return [...data].sort((a, b) => b.id - a.id)

})

/* =========================
PAGINACION
========================= */

const paginaActual = ref(1)
const porPagina = 6

const totalPaginas = computed(() => {
    return Math.ceil(carrosFiltrados.value.length / porPagina)
})

const carrosPaginados = computed(() => {

    const inicio = (paginaActual.value - 1) * porPagina
    const fin = inicio + porPagina

    return carrosFiltrados.value.slice(inicio, fin)

})

const cambiarPagina = (p: number) => {
    paginaActual.value = p
}

/* =========================
FUNCIONES
========================= */

const isAssigned = (carroId: number) => {
    return props.employee.carros?.find(c => c.id === carroId)
}

const isPagado = (carroId: number) => {
    const carro = isAssigned(carroId)
    return carro?.pivot?.pagado ?? false
}

const togglePago = (carroId: number) => {
    router.post(
        `/employees/${props.employee.id}/carros/${carroId}/toggle`,
        {},
        { preserveScroll: true }
    )
}

const assignCar = (carroId: number) => {
    router.post(
        `/employees/${props.employee.id}/carros/${carroId}/attach`,
        {},
        { preserveScroll: true }
    )
}

</script>

<template>

    <Head title="Carros del Empleado" />

    <AppLayout>

        <div class="p-6 space-y-6">

            <!-- TITULO -->

            <div class="flex justify-between items-center">

                <h1 class="text-2xl font-bold">
                    Carros de {{ employee.nombre }}
                </h1>

                <div class="text-sm bg-gray-200 dark:bg-gray-700 px-3 py-1 rounded">
                    Asignados: <b>{{ totalAsignados }}</b>
                </div>

            </div>

            <!-- FILTROS -->

            <div class="flex gap-3">

                <select v-model="filtroMarca" class="border rounded px-3 py-1 text-sm">

                    <option value="">Todas las marcas</option>

                    <option v-for="m in marcas" :key="m" :value="m">
                        {{ m }}
                    </option>

                </select>

                <select v-model="filtroPago" class="border rounded px-3 py-1 text-sm">

                    <option value="">Estado de pago</option>
                    <option value="pagado">Pagado</option>
                    <option value="nopagado">No Pagado</option>

                </select>

            </div>

            <!-- TABLA -->

            <div class="rounded-lg border overflow-hidden">

                <table class="min-w-full text-sm">

                    <thead class="bg-muted">

                        <tr>
                            <th class="px-6 py-3 text-left">Carro</th>
                            <th class="px-6 py-3 text-left">Estado</th>
                            <th class="px-6 py-3 text-right">Acción</th>
                        </tr>

                    </thead>

                    <tbody>

                        <tr v-for="carro in carrosPaginados" :key="carro.id" class="border-t">

                            <td class="px-6 py-4">
                                {{ carro.marca }} {{ carro.linea }} {{ carro.modelo }} {{ carro.color }}
                            </td>

                            <td class="px-6 py-4">

                                <span v-if="isAssigned(carro.id)">

                                    <span :class="isPagado(carro.id)
                                        ? 'text-green-600 font-semibold'
                                        : 'text-red-600 font-semibold'
                                        ">

                                        {{ isPagado(carro.id) ? 'Pagado' : 'No Pagado' }}

                                    </span>

                                </span>

                                <span v-else class="text-gray-400">
                                    No asignado
                                </span>

                            </td>

                            <td class="px-6 py-4 text-right space-x-2">

                                <Button v-if="!isAssigned(carro.id)" size="sm" @click="assignCar(carro.id)">
                                    Asignar
                                </Button>

                                <Button v-else size="sm" class="bg-indigo-500 hover:bg-indigo-700 text-white"
                                    @click="togglePago(carro.id)">
                                    Cambiar Estado
                                </Button>

                            </td>

                        </tr>

                        <tr v-if="carrosFiltrados.length === 0">

                            <td colspan="3" class="text-center py-6 text-muted-foreground">
                                No hay carros registrados.
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

            <!-- PAGINACION -->

            <div class="flex justify-center gap-2">

                <button v-for="p in totalPaginas" :key="p" @click="cambiarPagina(p)" class="px-3 py-1 border rounded"
                    :class="p === paginaActual ? 'bg-indigo-600 text-white' : ''">
                    {{ p }}
                </button>

            </div>

        </div>

    </AppLayout>

</template>