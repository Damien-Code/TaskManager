<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ArcElement, BarElement, CategoryScale, Chart, Legend, LinearScale, Title, Tooltip } from 'chart.js';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { Button } from '@/components/ui/button';

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
            <div class="relative min-h-[100vh] flex-1 rounded-xl md:min-h-min">
                <section class="bg-white dark:bg-black">
                    <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
                        <div class="mr-auto place-self-center lg:col-span-7">
                            <h1 class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl dark:text-white">Stay Organized. Get Things Done.</h1>
                            <p class="max-w-2xl mb-6 font-light text-zinc-600 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">Welcome to your new favorite productivity tool.
                                Easily create, manage, and organize your tasks with custom categories. Whether you're planning your day, managing a project, or just keeping track of errands — we've got you covered. <span class="font-extrabold">Simple. Smart. Stress-free.</span></p>
                            <a :href="route('dashboard')" class="inline-flex items-center justify-center px-5 py-3 mr-3 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">
                                <Button>
                                Get started
                                </Button>
                            </a>
                        </div>
                        <div class="hidden lg:mt-0 lg:col-span-5 lg:flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="300" height="300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-todo-icon lucide-list-todo"><rect x="3" y="5" width="6" height="6" rx="1"/><path d="m3 17 2 2 4-4"/><path d="M13 6h8"/><path d="M13 12h8"/><path d="M13 18h8"/></svg>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AppLayout>
</template>
