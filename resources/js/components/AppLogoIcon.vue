<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <div class="flex items-center space-x-3">
        <img src="/images/BASS.png" alt="B A S S" class="h-10 w-auto" />

        <div class="ml-1 grid text-left text-sm">
            <span class="mb-0.5 truncate font-semibold leading-none">
                B A S S
            </span>
        </div>
    </div>
</template>