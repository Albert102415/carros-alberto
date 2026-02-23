<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

type BreadcrumbItem = { title: string; href: string }

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Customers', href: '/customers' },
    { title: 'Create', href: '#' },
]

const form = ref({
    nombre: '',
    deuda_total: 0,
})

const resetForm = () => {
    form.value = {
        nombre: '',
        deuda_total: 0,
    }
}

const submit = () => {
    router.post('/customers', form.value, {
        onSuccess: resetForm,
    })
}
</script>

<template>

    <Head title="Create Customer" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">Crear Cliente</h1>

            <form @submit.prevent="submit" class="space-y-6 max-w-lg">

                <div>
                    <Label>Nombre</Label>
                    <Input v-model="form.nombre" required />
                </div>

                <div>
                    <Label>Deuda Inicial</Label>
                    <Input type="number" step="0.01" v-model="form.deuda_total" required />
                </div>

                <div class="flex gap-4">
                    <Button type="submit" class="bg-indigo-500 hover:bg-indigo-600">
                        Guardar
                    </Button>

                    <Button as="a" href="/customers" variant="outline">
                        Cancelar
                    </Button>
                </div>

            </form>
        </div>
    </AppLayout>
</template>