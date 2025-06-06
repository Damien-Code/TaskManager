<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger
} from '@/components/ui/dialog';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Textarea } from '@/components/ui/textarea';
import { getInitials } from '@/composables/useInitials';
import AppLayout from '@/layouts/AppLayout.vue';
import { cn } from '@/lib/utils';
import { type BreadcrumbItem, type SharedData, Standup, Team, User } from '@/types';
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import { DateFormatter, type DateValue, getLocalTimeZone, CalendarDate } from '@internationalized/date';
import { CalendarIcon, CirclePlus } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Bell, Check } from 'lucide-vue-next';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'



const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Daily Standup',
        href: '/daily-standup'
    }
];

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

interface Props {
    users: User[];
    standups: Standup[],
    teams: Team[]
    date?: string,
}

const props = defineProps<Props>();

const dateDate = new Date(props.date);
const test = new CalendarDate(dateDate.getFullYear(), dateDate.getMonth() + 1, dateDate.getDate());
// const showAvatar = computed(() => props.users.avatar && props.users.avatar !== '');

const getAvatar = (user: User) => {
    return computed(() => user.avatar && user.avatar !== '');
};

const df = new DateFormatter('nl-NL', {
    dateStyle: 'long'
});

const value = ref<DateValue>();

const form = useForm({
    accomplishment: '',
    doing: '',
    reflection: '',
    date: null
});

const submitStandup = () => {
    form.transform((data) => ({
        ...data,
        date: data.date ? new Date(data.date).toLocaleDateString() : null
    })).post(route('daily-standup.store'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast.success('Daily standup is successfully added!');
        }
    });
};

const showDate = (date: Date) => {
    if (date === undefined)
        date = null;
    router.visit(route('daily-standup.index', {
        date: date ? new Date(date).toLocaleDateString() : null
    }));
};


const selectedTeam = ref(route('daily-standup.index') || '') // Behoud huidige selectie bij pageload

const applyFilter = () => {
    router.get(route('daily-standup.index'), {
        team: selectedTeam.value || undefined,
    }, {
        preserveScroll: true,
        preserveState: true,
    })
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border p-4">
                    <Dialog>
                        <DialogTrigger as-child>
                            <Button variant="outline"> Create new standup</Button>
                        </DialogTrigger>
                        <DialogContent class="sm:max-w-[600px]">
                            <form @submit.prevent="submitStandup">
                                <DialogHeader>
                                    <DialogTitle>Create a new standup</DialogTitle>
                                    <DialogDescription> Prepare a new standup and show the team on what you are working
                                        on!
                                    </DialogDescription>
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
                                                    {{ form.date ? df.format(form.date.toDate(getLocalTimeZone())) : 'Pick a date'
                                                    }}
                                                </Button>
                                            </PopoverTrigger>
                                            <PopoverContent class="w-auto p-0">
                                                <Calendar v-model="form.date" initial-focus />
                                            </PopoverContent>
                                        </Popover>
                                    </div>
                                    <div class="grid grid-cols-4 items-center gap-4">
                                        <Label for="accomplishment" class="text-right"> What did you
                                            accomplish? </Label>
                                        <Textarea v-model="form.accomplishment"
                                                  placeholder="Type your accomplishments here." class="col-span-3" />
                                    </div>
                                    <div class="grid grid-cols-4 items-center gap-4">
                                        <Label for="doing" class="text-right"> What are you going to do? </Label>
                                        <Textarea v-model="form.doing"
                                                  placeholder="Type the things you are going to do here."
                                                  class="col-span-3" />
                                    </div>
                                    <div class="grid grid-cols-4 items-center gap-4">
                                        <Label for="reflection" class="text-right"> What could have gone
                                            better? </Label>
                                        <Textarea v-model="form.reflection" placeholder="Type your reflection here."
                                                  class="col-span-3" />
                                    </div>
                                </div>
                                <DialogFooter>
                                    <Button type="submit"> Save changes</Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
                    <Dialog>
                        <DialogTrigger as-child>
                            <Button variant="outline"> Create new Team</Button>
                        </DialogTrigger>
                        <DialogContent class="sm:max-w-[600px]">
                            <form @submit.prevent="submitStandup">
                                <DialogHeader>
                                    <DialogTitle>Create a new Team</DialogTitle>
                                    <DialogDescription> Prepare a new standup and show the team on what you are working
                                        on!
                                    </DialogDescription>
                                </DialogHeader>
                                <div class="grid gap-4 py-4">
                                    <div class="grid grid-cols-4 items-center gap-4">
                                        <Label for="accomplishment" class="text-right"> What will the team be called?</Label>
                                        <Input class="col-span-3" placeholder="Name"/>
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
                    class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border p-4"
                >
                    <div class="flex items-center gap-4 mb-6">
                        <Select v-model="selectedTeam">
                            <SelectTrigger class="w-[200px]">
                                <SelectValue placeholder="Kies een team" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="">All Teams</SelectItem>
                                <SelectItem
                                    v-for="team in teams"
                                    :key="team.id"
                                    :value="team.id"
                                >
                                    {{ team.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>

                        <Button @click="applyFilter">Filter</Button>
                    </div>
                </div>
                <div
                    class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border p-4">
                    <div class="flex justify-between">
                    <Heading title="Team Members" description="Invite your team members to collaborate" />

                        <Dialog>
                            <DialogTrigger as-child>
                                <Button variant="outline">
                                    <CirclePlus/>
                                </Button>
                            </DialogTrigger>
                            <DialogContent class="sm:max-w-[425px]">
                                <DialogHeader>
                                    <DialogTitle>Add new team members</DialogTitle>
                                    <DialogDescription>
                                        Make changes to your profile here. Click save when you're done.
                                    </DialogDescription>
                                </DialogHeader>
                                <div class="grid gap-4 py-4">
                                    <div class="grid grid-cols-4 items-center gap-4">
                                        <Label for="name" class="text-right">
                                            Name
                                        </Label>
                                        <Input id="name" value="Pedro Duarte" class="col-span-3" />
                                    </div>
                                </div>
                                <DialogFooter>
                                    <Button type="submit">
                                        Save changes
                                    </Button>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>
                    </div>
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
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="col-span-2 p-4 border-sidebar-border/70 dark:border-sidebar-border rounded-xl border">
                    <template v-for="standup in standups" :key="standup.accomplishment">
                        <Card :class="cn('col-span-3 mx-auto mb-4', $attrs.class ?? '')">
                            <CardHeader class="flex justify-between">
                                <div>
                                <CardTitle>{{ standup.user.name }}</CardTitle>
                                <CardDescription>{{ standup.date ? df.format(new Date(standup.date)) : '' }}
                                </CardDescription>
                                </div>
                                <Button v-if="standup.user_id === user.id">Edit</Button>
                            </CardHeader>
                            <CardContent class="grid gap-4">
                                <div class=" flex items-center space-x-4 rounded-md border p-4">
                                    <div class="flex-1 space-y-1">
                                        <Heading title="What did you accomplish today?" />
                                        <p class="text-sm font-medium leading-none">
                                            {{ standup.accomplishment }}
                                        </p>
                                    </div>
                                </div>
                                <div class=" flex items-center space-x-4 rounded-md border p-4">
                                    <div class="flex-1 space-y-1">
                                        <Heading title="What are you going to focus on?" />
                                        <p class="text-sm font-medium leading-none">
                                            {{ standup.doing }}
                                        </p>
                                    </div>
                                </div>
                                <div class=" flex items-center space-x-4 rounded-md border p-4">
                                    <div class="flex-1 space-y-1">
                                        <Heading title="What could have gone better?" />
                                        <p class="text-sm font-medium leading-none">
                                            {{ standup.reflection }}
                                        </p>
                                    </div>
                                </div>
                            </CardContent>
                            <CardFooter>
                                <Button class="w-full">
                                    <Check />
                                </Button>
                            </CardFooter>
                        </Card>
                    </template>
                    <div v-if="standups.length === 0">
                        <Heading title="No standups found for this date" />
                    </div>
                </div>
                <div class="border-sidebar-border/70 dark:border-sidebar-border rounded-xl border p-4">
                    <Calendar @update:model-value="showDate" :model-value="test" :weekday-format="'short'"
                              class="rounded-md w-fit" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
