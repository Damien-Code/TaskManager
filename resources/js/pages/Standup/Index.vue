<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Textarea } from '@/components/ui/textarea';
import { getInitials } from '@/composables/useInitials';
import AppLayout from '@/layouts/AppLayout.vue';
import { cn } from '@/lib/utils';
import { type BreadcrumbItem, User } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { DateFormatter, type DateValue, getLocalTimeZone } from '@internationalized/date';
import { CalendarIcon } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Daily Standup',
        href: '/daily-standup',
    },
];

interface Props {
    users: User[];
}

defineProps<Props>();
// const showAvatar = computed(() => props.users.avatar && props.users.avatar !== '');

const getAvatar = (user: User) => {
    return computed(() => user.avatar && user.avatar !== '');
};

const df = new DateFormatter('en-US', {
    dateStyle: 'long',
});

const value = ref<DateValue>();

const form = useForm({
    accomplishment: '',
    doing: '',
    reflection: '',
    date: null,
});

const submitStandup = () => {
    form.transform((data) => ({
        ...data,
        date: data.date ? data.date.toDate(getLocalTimeZone()) : null,
    })).post(route('daily-standup.store'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast.success('Daily standup is successfully added!');
        },
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border p-4">
                    <Dialog>
                        <DialogTrigger as-child>
                            <Button variant="outline"> Create new standup</Button>
                        </DialogTrigger>
                        <DialogContent class="sm:max-w-[600px]">
                            <form @submit.prevent="submitStandup">
                                <DialogHeader>
                                    <DialogTitle>Create a new standup</DialogTitle>
                                    <DialogDescription> Prepare a new standup and show the team on what you are working on! </DialogDescription>
                                </DialogHeader>
                                <div class="grid gap-4 py-4">
                                    <div class="grid grid-cols-4 items-center gap-4">
                                        <Label for="date" class="text-right"> The date of the standup </Label>
                                        <Popover>
                                            <PopoverTrigger as-child>
                                                <Button
                                                    variant="outline"
                                                    :class="cn('w-[280px] justify-start text-left font-normal', !value && 'text-muted-foreground')"
                                                >
                                                    <CalendarIcon class="mr-2 h-4 w-4" />
                                                    {{ form.date ? df.format(form.date.toDate(getLocalTimeZone())) : 'Pick a date' }}
                                                </Button>
                                            </PopoverTrigger>
                                            <PopoverContent class="w-auto p-0">
                                                <Calendar v-model="form.date" initial-focus />
                                            </PopoverContent>
                                        </Popover>
                                    </div>
                                    <div class="grid grid-cols-4 items-center gap-4">
                                        <Label for="accomplishment" class="text-right"> What did you accomplish? </Label>
                                        <Textarea v-model="form.accomplishment" placeholder="Type your accomplishments here." class="col-span-3" />
                                    </div>
                                    <div class="grid grid-cols-4 items-center gap-4">
                                        <Label for="doing" class="text-right"> What are you going to do? </Label>
                                        <Textarea v-model="form.doing" placeholder="Type the things you are going to do here." class="col-span-3" />
                                    </div>
                                    <div class="grid grid-cols-4 items-center gap-4">
                                        <Label for="reflection" class="text-right"> What could have gone better? </Label>
                                        <Textarea v-model="form.reflection" placeholder="Type your reflection here." class="col-span-3" />
                                    </div>
                                </div>
                                <DialogFooter>
                                    <Button type="submit"> Save changes</Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
                </div>
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border relative flex aspect-video flex-col items-center justify-start overflow-hidden rounded-xl border py-4"
                ></div>
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border p-4">
                    <Heading title="Team Members" description="Invite your team members to collaborate" />
                    <div class="overflow-scroll h-full">
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
                </div>
            </div>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">

            </div>
        </div>
    </AppLayout>
</template>
