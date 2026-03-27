<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import {
    LayoutGrid,
    UsersRound,
    CarFront,
    DollarSign,
    PersonStanding,
    ShieldCheck
} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { computed } from 'vue';

const page = usePage();

// 🔐 Validación admin
const isAdmin = computed(() => !!page.props.auth?.user?.is_admin);

// 📌 Menú principal
const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        routeName: 'dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Employees',
        href: '/employees',
        routeName: 'employees.index',
        icon: UsersRound,
    },
    {
        title: 'Carros',
        href: '/carros',
        routeName: 'carros.index',
        icon: CarFront,
    },
    {
        title: 'Ventas',
        href: '/ventas',
        routeName: 'ventas.index',
        icon: DollarSign,
    },
    {
        title: 'Customers',
        href: '/customers',
        routeName: 'customers.index',
        icon: PersonStanding,
    },
];
</script>

<template>
    <div class="flex h-screen">

        <!-- 🔥 SIDEBAR -->
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" as-child>
                            <Link :href="route('dashboard')">
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain :items="mainNavItems" />
            </SidebarContent>

            <SidebarFooter>
                <!-- 🔐 Solo admin -->
                <Link v-if="isAdmin" href="/usuarios" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                           hover:bg-gray-100 dark:hover:bg-gray-800 transition
                           text-gray-600 dark:text-gray-400">
                    <ShieldCheck class="w-4 h-4" />
                    <span>Usuarios</span>
                </Link>

                <NavUser />
            </SidebarFooter>
        </Sidebar>

        <!-- 🔥 CONTENIDO DINÁMICO -->
        <main class="flex-1 overflow-y-auto p-4">
            <slot />
        </main>

    </div>
</template>