<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Trash, Download } from 'lucide-vue-next'

const props = defineProps<{ carro: any }>()

const page = usePage()
const mensaje = computed(() => (page.props.flash as any)?.success)

const tiposArchivo = ['Contrato', 'Factura', 'Tenencia', 'Verificación', 'Carta no adeudo', 'Otro']

const form = ref({
    cliente: props.carro.expediente?.cliente ?? '',
    telefono: props.carro.expediente?.telefono ?? '',
})

const nuevoArchivo = ref({
    nombre: '',
    tipo: 'Contrato',
    archivo: null as File | null,
})

const loadingGuardar = ref(false)
const loadingArchivo = ref(false)

const guardar = () => {
    loadingGuardar.value = true
    router.post(`/carros/${props.carro.id}/expediente`, form.value, {
        preserveScroll: true,
        onFinish: () => { loadingGuardar.value = false },
    })
}

const handleArchivo = (e: Event) => {
    const target = e.target as HTMLInputElement
    if (target.files?.[0]) {
        nuevoArchivo.value.archivo = target.files[0]
        if (!nuevoArchivo.value.nombre) {
            nuevoArchivo.value.nombre = target.files[0].name
        }
    }
}

const subirArchivo = () => {
    if (!nuevoArchivo.value.archivo) return
    loadingArchivo.value = true

    const data = new FormData()
    data.append('archivo', nuevoArchivo.value.archivo)
    data.append('nombre', nuevoArchivo.value.nombre)
    data.append('tipo', nuevoArchivo.value.tipo)

    router.post(`/carros/${props.carro.id}/expediente/archivo`, data, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            nuevoArchivo.value = { nombre: '', tipo: 'Contrato', archivo: null }
        },
        onFinish: () => { loadingArchivo.value = false },
    })
}

const eliminarArchivo = (id: number) => {
    if (!confirm('¿Eliminar este archivo?')) return
    router.delete(`/expediente/archivo/${id}`, { preserveScroll: true })
}

const iconoTipo = (tipo: string) => {
    const iconos: Record<string, string> = {
        'Contrato': '📄',
        'Factura': '🧾',
        'Tenencia': '🏛',
        'Verificación': '✅',
        'Carta no adeudo': '📋',
        'Otro': '📁',
    }
    return iconos[tipo] ?? '📁'
}
</script>

<template>

    <Head title="Expediente" />

    <AppLayout>
        <div class="p-6 max-w-3xl mx-auto space-y-8">

            <!-- HEADER -->
            <div class="flex items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold">Expediente</h1>
                    <p class="text-sm text-gray-400">
                        {{ carro.marca }} {{ carro.linea }} {{ carro.modelo }} · {{ carro.color }}
                    </p>
                </div>
            </div>

            <!-- MENSAJE ÉXITO -->
            <div v-if="mensaje" class="bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300
                       px-4 py-3 rounded-lg text-sm font-medium">
                ✅ {{ mensaje }}
            </div>

            <!-- DATOS DEL CLIENTE -->
            <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-5 space-y-4">
                <h2 class="font-semibold text-lg">Datos del cliente</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <Label>Nombre</Label>
                        <Input v-model="form.cliente" placeholder="Nombre del comprador (opcional)" />
                    </div>
                    <div>
                        <Label>Teléfono</Label>
                        <Input v-model="form.telefono" placeholder="Teléfono (opcional)" />
                    </div>
                </div>

                <Button @click="guardar" :disabled="loadingGuardar"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white">
                    {{ loadingGuardar ? 'Guardando...' : 'Guardar datos' }}
                </Button>
            </div>

            <!-- SUBIR ARCHIVO -->
            <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-5 space-y-4">
                <h2 class="font-semibold text-lg">Subir documento</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <Label>Nombre del documento</Label>
                        <Input v-model="nuevoArchivo.nombre" placeholder="Ej. Contrato firmado" />
                    </div>
                    <div>
                        <Label>Tipo</Label>
                        <select v-model="nuevoArchivo.tipo" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm
                                   bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option v-for="t in tiposArchivo" :key="t" :value="t">{{ t }}</option>
                        </select>
                    </div>
                    <div>
                        <Label>Archivo</Label>
                        <Input type="file" @change="handleArchivo" />
                    </div>
                </div>

                <Button @click="subirArchivo" :disabled="loadingArchivo || !nuevoArchivo.archivo"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white">
                    {{ loadingArchivo ? 'Subiendo...' : 'Subir archivo' }}
                </Button>
            </div>

            <!-- ARCHIVOS GUARDADOS -->
            <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-5 space-y-3">
                <h2 class="font-semibold text-lg">Documentos guardados</h2>

                <div v-if="!carro.expediente?.archivos?.length" class="text-gray-400 text-sm py-4 text-center">
                    No hay documentos subidos aún
                </div>

                <div v-for="archivo in carro.expediente?.archivos" :key="archivo.id"
                    class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 py-2">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">{{ iconoTipo(archivo.tipo) }}</span>
                        <div>
                            <p class="font-medium text-sm">{{ archivo.nombre }}</p>
                            <p class="text-xs text-gray-400">{{ archivo.tipo }}</p>
                        </div>
                    </div>

                    <div class="flex gap-2 items-center">
                        <a :href="`/storage/${archivo.archivo}`" target="_blank"
                            class="inline-flex items-center gap-1 text-xs text-indigo-500 hover:underline">Ver</a>
                        <Button size="sm" class="bg-red-600 hover:bg-red-700 text-white"
                            @click="eliminarArchivo(archivo.id)">
                            <Trash class="w-3 h-3" />
                        </Button>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>