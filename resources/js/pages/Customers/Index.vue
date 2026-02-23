<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Link } from '@inertiajs/vue3'
import { Pencil, Trash, CirclePlus, Car } from 'lucide-vue-next';

type BreadcrumbItem = { title: string; href: string }

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Customers', href: '/customers' },
]

const props = defineProps<{
    customers: Array<{
        id: number
        nombre: string
        deuda_total: number
        abonos: Array<{
            id: number
            monto: number
            created_at: string
        }>
    }>
}>()

const formatMoney = (value: number) => {
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN'
    }).format(value)
}

const destroy = (id: number) => {
    if (confirm('¿Seguro que quieres eliminar este customer?')) {
        router.delete(`/customers/${id}`)
    }
}
</script>

<template>

    <Head title="Customers" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-6 rounded-xl p-6">

            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Customers</h1>

                <Button as="a" href="/customers/create" class="bg-indigo-500 hover:bg-indigo-700 text-white">
                    <CirclePlus /> Create
                </Button>
            </div>

            <!-- Tabla -->
            <div class="overflow-x-auto rounded-lg border">
                <table class="min-w-full text-sm">
                    <thead class="bg-muted">
                        <tr class="text-left">
                            <th class="px-6 py-3 font-semibold">Nombre</th>
                            <th class="px-6 py-3 font-semibold">Deuda Total</th>
                            <th class="px-6 py-3 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <template v-for="customer in customers" :key="customer.id">

                            <!-- FILA PRINCIPAL DEL CLIENTE -->
                            <tr class="border-t hover:bg-muted/50 transition">
                                <!-- Nombre -->
                                <td class="px-6 py-4 font-medium">
                                    {{ customer.nombre }}
                                </td>

                                <!-- Deuda -->
                                <td class="px-6 py-4">
                                    <span :class="[
                                        'px-3 py-1 rounded-full text-xs font-semibold',
                                        customer.deuda_total > 0
                                            ? 'bg-red-100 text-red-600'
                                            : 'bg-green-100 text-green-600'
                                    ]">
                                        {{ formatMoney(customer.deuda_total) }}
                                    </span>
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-4 text-right space-x-2">

                                    <Link :href="`/customers/${customer.id}/edit`">
                                        <Button size="sm" variant="outline">
                                            Editar
                                        </Button>
                                    </Link>

                                    <Button size="sm" class="bg-red-600 hover:bg-red-700 text-white"
                                        @click="destroy(customer.id)">
                                        Eliminar
                                    </Button>
                                </td>
                            </tr>

                            <!-- ABONOS DEL CLIENTE -->
                            <tr v-for="abono in customer.abonos" :key="abono.id" class="bg-muted/10 text-sm">
                                <td class="px-6 py-2 pl-12 text-gray-500">
                                    Abono
                                </td>

                                <td class="px-6 py-2 text-green-600">
                                    {{ formatMoney(abono.monto) }}
                                </td>

                                <td class="px-6 py-2 text-gray-400 text-right">
                                    {{ new Date(abono.created_at).toLocaleDateString() }}
                                </td>
                            </tr>

                        </template>

                        <!-- Si no hay customers -->
                        <tr v-if="customers.length === 0">
                            <td colspan="3" class="text-center py-6 text-muted-foreground">
                                No hay customers registrados.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </AppLayout>
</template>