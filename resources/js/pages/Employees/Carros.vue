<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'

const props = defineProps<{
    employee: {
        id: number
        nombre: string
        carros: any[]
    }
    carros: any[]
}>()

/**
 * Verifica si el carro está asignado al empleado
 */
const isAssigned = (carroId: number) => {
    return props.employee.carros?.find(
        (carro: any) => carro.id === carroId
    )
}

/**
 * Verifica si el carro está pagado
 */
const isPagado = (carroId: number) => {
    const carro = isAssigned(carroId)
    return carro?.pivot?.pagado ?? false
}

/**
 * Cambiar estado pagado / no pagado
 */
const togglePago = (carroId: number) => {
    router.post(
        `/employees/${props.employee.id}/carros/${carroId}/toggle`,
        {},
        { preserveScroll: true }
    )
}

/**
 * Asignar carro al empleado
 */
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

            <!-- Título -->
            <h1 class="text-2xl font-bold">
                Carros de {{ employee.nombre }}
            </h1>

            <!-- Tabla -->
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
                        <tr v-for="carro in carros" :key="carro.id" class="border-t">
                            <!-- Nombre del carro -->
                            <td class="px-6 py-4">
                                {{ carro.linea }}
                            </td>

                            <!-- Estado -->
                            <td class="px-6 py-4">
                                <span v-if="isAssigned(carro.id)">
                                    <span :class="isPagado(carro.id)
                                        ? 'text-green-600 font-semibold'
                                        : 'text-red-600 font-semibold'">
                                        {{
                                            isPagado(carro.id)
                                                ? 'Pagado'
                                                : 'No Pagado'
                                        }}
                                    </span>
                                </span>

                                <span v-else class="text-gray-400">
                                    No asignado
                                </span>
                            </td>

                            <!-- Botones -->
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

                        <!-- Si no hay carros -->
                        <tr v-if="carros.length === 0">
                            <td colspan="3" class="text-center py-6 text-muted-foreground">
                                No hay carros registrados.
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </AppLayout>
</template>