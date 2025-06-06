<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, User} from '@/types';
import { Head } from '@inertiajs/vue3';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { computed } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Daily Standup',
        href: '/daily-standup',
    },
];
interface Props {
    users: User[]
}
const props = defineProps<Props>()
// const showAvatar = computed(() => props.users.avatar && props.users.avatar !== '');

const getAvatar = (user: User) => {
    return computed(() => user.avatar && user.avatar !== '');
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-scroll rounded-xl border p-4">
                    <Heading title="Team Members" description="Invite your team members to collaborate" />
                    <div v-for="user in users" :key="user.id" class="flex flex-row gap-6 py-2">
                        <Avatar class="h-8 w-8 overflow-hidden rounded-lg">
                            <AvatarImage v-if="getAvatar(user)" :src="user.avatar" :alt="user.name" />
                            <AvatarFallback class="rounded-lg text-black dark:text-white">
                                {{ getInitials(user.name) }}
                            </AvatarFallback>
                        </Avatar>
                        <HeadingSmall :title="user.name" :description="user.email" />
                    </div>
                </div>
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border relative flex aspect-video flex-col items-center justify-start overflow-hidden rounded-xl border py-4">

                </div>
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border relative flex aspect-video flex-col items-center justify-start overflow-hidden rounded-xl border py-4"
                ></div>
            </div>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min"></div>
        </div>
    </AppLayout>
</template>
