<template>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Contact Lists</h1>
            <Link :href="route('contact-lists.create')"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                New List
            </Link>
        </div>

        <div v-if="lists.data.length === 0" class="text-gray-500">
            No contact lists yet. Create your first one!
        </div>

        <div v-else class="space-y-4">
            <div v-for="list in lists.data" :key="list.id"
                class="border rounded-lg p-4 flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-lg">{{ list.name }}</h2>
                    <p class="text-gray-500 text-sm">{{ list.description }}</p>
                    <p class="text-blue-600 text-sm font-medium">{{ list.subscribers_count }} subscribers</p>
                </div>
                <div class="flex gap-3">
                    <Link :href="route('contact-lists.show', list.id)"
                        class="text-blue-600 hover:underline text-sm">View</Link>
                    <Link :href="route('contact-lists.upload', list.id)"
                        class="text-green-600 hover:underline text-sm">Upload CSV</Link>
                    <button @click="deleteList(list.id)"
                        class="text-red-600 hover:underline text-sm">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'

defineProps({ lists: Object })

function deleteList(id) {
    if (confirm('Delete this contact list and all its subscribers?')) {
        router.delete(route('contact-lists.destroy', id))
    }
}
</script>