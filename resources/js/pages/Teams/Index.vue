<script setup lang="ts">
import { Button, buttonVariants } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { FormControl, FormField, FormItem, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Team, User } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

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
defineProps<Props>();

const form = useForm({
    name: '',
    user_ids: []
})
const submitTeam = () => {
    form.post(route('teams.store'), {
        onSuccess: () => {
            toast.success('Team was succesfully created!');
            form.reset()
        }
    })

}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Task Categories List" />

        <div class="mt-4">
            <Dialog>
                <DialogTrigger as-child>
                    <Button variant="outline"> Create new team </Button>
                </DialogTrigger>
                <DialogContent class="sm:max-w-[425px]">
                    <DialogHeader>
                        <DialogTitle>Create your new team</DialogTitle>
                        <DialogDescription> Create a new team and start collaborating! </DialogDescription>
                    </DialogHeader>
                    <form @submit.prevent="submitTeam">
                    <div class="grid gap-4 py-4">
                        <div class="grid grid-cols-4 items-center gap-4">
                            <Label for="name" class="text-right"> Team name </Label>
                            <Input id="name" class="col-span-3" v-model="form.name"/>
                        </div>
                        <div class="grid grid-cols-4 items-center gap-4">
                            <Label for="members" class="text-right"> Members </Label>
                            <FormField v-slot="{ componentField }" name="email">
                                <FormItem>
                                    <Select v-bind="componentField" v-model="form.user_ids">
                                        <FormControl>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select a member to add to your team" />
                                            </SelectTrigger>
                                        </FormControl>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectItem v-for="user in users" :key="user.id" :value="user.id">
                                                    {{ user.name }}
                                                </SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                    <FormMessage />
                                </FormItem>
                            </FormField>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="submit"> Create team </Button>
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
