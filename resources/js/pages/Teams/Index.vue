<script setup lang="ts">
import { Button, buttonVariants } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Team, User } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import { computed, ref } from 'vue';
import { useFilter } from 'reka-ui';
import { Combobox, ComboboxAnchor, ComboboxEmpty, ComboboxGroup, ComboboxInput, ComboboxItem, ComboboxList } from '@/components/ui/combobox';
import { TagsInput, TagsInputInput, TagsInputItem, TagsInputItemDelete, TagsInputItemText } from '@/components/ui/tags-input';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Daily Standups',
        href: '/daily-standup',
    },
    {
        title: 'Teams',
        href: '/teams',
    },
];

interface Props {
    teams: Team[];
    users: User[];
}

const props = defineProps<Props>();

const form = useForm({
    name: '',
    user_ids: [],
});
const submitTeam = () => {
    form.post(route('teams.store'), {
        onSuccess: () => {
            toast.success('Team was succesfully created!');
            form.reset();
        },
    });
};

const open = ref(false);
const searchTerm = ref('');
const { contains } = useFilter({ sensitivity: 'base' });

const filteredUsers = computed(() => {
    const available = props.users.filter((user) => !form.user_ids.includes(user.id));
    return searchTerm.value ? available.filter((user) => contains(user.name, searchTerm.value)) : available;
});

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Task Categories List" />

        <div class="mt-4">
            <Dialog>
                <DialogTrigger as-child>
                    <Button variant="outline"> Create new team</Button>
                </DialogTrigger>
                <DialogContent class="sm:max-w-[425px]">
                    <DialogHeader>
                        <DialogTitle>Create your new team</DialogTitle>
                        <DialogDescription> Create a new team and start collaborating!</DialogDescription>
                    </DialogHeader>
                    <form @submit.prevent="submitTeam">
                        <div class="grid gap-4 py-4">
                            <div class="grid grid-cols-4 items-center gap-4">
                                <Label for="name" class="text-right"> Team name </Label>
                                <Input id="name" class="col-span-3" v-model="form.name" />
                            </div>
                            <div class="grid grid-cols-4 items-center gap-4">
                                <Label for="members" class="text-right"> Members </Label>
                                <Combobox v-model="form.user_ids" v-model:open="open" :ignore-filter="true" class="col-span-3">
                                    <ComboboxAnchor as-child>
                                        <TagsInput>
                                            <TagsInputItem v-for="id in form.user_ids" :key="id" :value="id">
                                                <TagsInputItemText>
                                                    {{ props.users.find((user) => user.id === id)?.name ?? 'Unknown' }}
                                                </TagsInputItemText>
                                                <TagsInputItemDelete @click.stop="form.user_ids = form.user_ids.filter((i) => i !== id)" />
                                            </TagsInputItem>

                                            <ComboboxInput v-model="searchTerm" as-child>
                                                <TagsInputInput placeholder="Search users..." />
                                            </ComboboxInput>
                                        </TagsInput>

                                        <ComboboxList>
                                            <ComboboxEmpty>No users found</ComboboxEmpty>
                                            <ComboboxGroup>
                                                <ComboboxItem
                                                    v-for="user in filteredUsers"
                                                    :key="user.id"
                                                    :value="user.id"
                                                    @select.prevent="
                                                        (ev) => {
                                                            const val = ev.detail.value;
                                                            if (typeof val === 'number' && !form.user_ids.includes(val)) {
                                                                form.user_ids.push(val);
                                                                searchTerm = '';
                                                            }
                                                            if (filteredUsers.length === 0) {
                                                                open = false;
                                                            }
                                                        }
                                                    "
                                                >
                                                    {{ user.name }}
                                                </ComboboxItem>
                                            </ComboboxGroup>
                                        </ComboboxList>
                                    </ComboboxAnchor>
                                </Combobox>
                            </div>
                        </div>
                        <DialogFooter>
                            <Button type="submit"> Create team</Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>

        <Table class="mt-4">
            <TableHeader>
                <TableRow>
                    <TableHead>Team</TableHead>
                    <TableHead class="w-[200px] text-right">Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="team in teams" :key="team.id">
                    <TableCell>{{ team.name }}</TableCell>
                    <TableCell class="flex gap-x-2 text-right">
                        <Link :class="buttonVariants({ variant: 'default' })" :href="`/task-categories/${team.id}/edit`">Edit </Link>
                        <Button variant="destructive" class="mr-2">Leave this team</Button>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </AppLayout>
</template>
