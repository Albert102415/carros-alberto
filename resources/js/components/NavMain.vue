<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
    items: NavItem[];
}>();

const page = usePage<SharedData>();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>
<SidebarMenu>
  <SidebarMenuItem
    v-for="item in items"
    :key="item.title"
  >
    <SidebarMenuButton
      as-child
      :class="[
        'transition-all duration-200',
        route().current(item.href.replace('/', ''))
          ? 'bg-indigo-600 text-white shadow'
          : 'text-gray-300 hover:bg-gray-800 hover:text-white'
      ]"
    >
      <Link :href="item.href" class="flex items-center gap-3">
        <component :is="item.icon" class="w-5 h-5" />
        <span>{{ item.title }}</span>
      </Link>
    </SidebarMenuButton>
  </SidebarMenuItem>
</SidebarMenu>
    </SidebarGroup>
</template>
