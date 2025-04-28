<script setup lang="ts">
import { Pagination, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import type { PaginatedResponse } from '@/types';
import { router } from '@inertiajs/vue3';

interface Props {
    resource: PaginatedResponse;
}

const props = withDefaults(defineProps<Props>(), {
    resource: null,
});
</script>

<template>
    <Pagination :items-per-page="10" :total="100" :sibling-count="1" show-edges :default-page="2" class="mx-auto">
        <div class="flex items-center gap-1">
            <div v-if="props.resource.last_page === 1">
                <div class="mt-4 text-center text-gray-500">No more items to show.</div>
            </div>
            <div v-if="props.resource.last_page !== 1">
                <PaginationPrevious v-on:click="() => router.visit(props.resource?.prev_page_url)" v-if="props.resource.prev_page_url" />

                <PaginationNext v-on:click="() => router.visit(props.resource?.next_page_url)" v-if="props.resource.next_page_url" />
            </div>
        </div>
    </Pagination>
</template>
