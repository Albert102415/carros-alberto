<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Trash, UserPlus } from 'lucide-vue-next'

const props = defineProps<{ usuarios: any[] }>()

const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const loading = ref(false)
const errors = ref<any>({})

const submit = () => {
    loading.value = true
    errors.value = {}

    router.post('/usuarios', form.value, {
        onSuccess: () => {
            form.value = { name: '', email: '', password: '', password_confirmation: '' }
        },
        onError: (e) => { errors.value = e },
        onFinish: () => { loading.value = false },
    })
}

const eliminar = (id: number) => {
    if (!confirm('¿Eliminar este usuario?')) return
    router.delete(`/usuarios/${id}`, { preserveScroll: true })
}
</script>

<template>

    <Head title="Usuarios" />

    <AppLayout>
        <div class="p-6 max-w-3xl mx-auto space-y-8">

            <h1 class="text-2xl font-bold">Gestión de usuarios</h1>

            <!-- FORM NUEVO USUARIO -->
            <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-5 space-y-4">
                <h2 class="font-semibold text-lg flex items-center gap-2">
                    <UserPlus class="w-5 h-5" /> Crear nuevo usuario
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <Label>Nombre</Label>
                        <Input v-model="form.name" placeholder="Nombre completo" />
                        <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name }}</p>
                    </div>
                    <div>
                        <Label>Correo</Label>
                        <Input v-model="form.email" type="email" placeholder="correo@ejemplo.com" />
                        <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
                    </div>
                    <div>
                        <Label>Contraseña</Label>
                        <Input v-model="form.password" type="password" placeholder="Mínimo 8 caracteres" />
                        <p v-if="errors.password" class="text-red-500 text-xs mt-1">{{ errors.password }}</p>
                    </div>
                    <div>
                        <Label>Confirmar contraseña</Label>
                        <Input v-model="form.password_confirmation" type="password"
                            placeholder="Repite la contraseña" />
                    </div>
                </div>

                <Button @click="submit" :disabled="loading" class="bg-indigo-600 hover:bg-indigo-700 text-white">
                    {{ loading ? 'Creando...' : 'Crear usuario' }}
                </Button>
            </div>

            <!-- LISTA DE USUARIOS -->
            <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-5 space-y-3">
                <h2 class="font-semibold text-lg">Usuarios registrados</h2>

                <div v-if="!usuarios.length" class="text-gray-400 text-sm text-center py-4">
                    No hay usuarios registrados
                </div>

                <div v-for="u in usuarios" :key="u.id"
                    class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 py-2">
                    <div>
                        <p class="font-medium text-sm">{{ u.name }}</p>
                        <p class="text-xs text-gray-400">{{ u.email }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Badge si es el usuario actual -->
                        <span v-if="u.id === $page.props.auth.user.id"
                            class="text-xs bg-indigo-100 text-indigo-600 dark:bg-indigo-900 dark:text-indigo-300 px-2 py-0.5 rounded-full">
                            Tú
                        </span>
                        <Button v-else size="sm" class="bg-red-600 hover:bg-red-700 text-white" @click="eliminar(u.id)">
                            <Trash class="w-3 h-3" />
                        </Button>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>