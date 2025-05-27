<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ArcElement, BarElement, CategoryScale, Chart, Legend, LinearScale, Title, Tooltip } from 'chart.js';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

Chart.register(ArcElement, Tooltip, Legend, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface Props {
    completedVsPendingTaskChart: [];
    pendingTasksToday: number;
    tasksCreatedByDay: object;
}

defineProps<Props>();
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
