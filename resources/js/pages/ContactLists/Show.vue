<template>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold">{{ list.name }}</h1>
                <p class="text-gray-500">{{ list.subscribers_count }} subscribers</p>
            </div>
            <Link :href="route('contact-lists.upload', list.id)"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Upload CSV
            </Link>
        </div>

        <!-- Success message -->
        <div v-if="$page.props.flash?.success"
            class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
            {{ $page.props.flash.success }}
        </div>

        <div v-if="subscribers.data.length === 0" class="text-gray-500">
            No subscribers yet. Upload a CSV to add some!
        </div>

        <table v-else class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-50">
                    <th class="text-left p-3 border-b font-medium">Email</th>
                    <th class="text-left p-3 border-b font-medium">First Name</th>
                    <th class="text-left p-3 border-b font-medium">Last Name</th>
                    <th class="text-left p-3 border-b font-medium">Status</th>
                    <th class="text-left p-3 border-b font-medium">Subscribed</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="subscriber in subscribers.data" :key="subscriber.id"
                    class="hover:bg-gray-50">
                    <td class="p-3 border-b">{{ subscriber.email }}</td>
                    <td class="p-3 border-b">{{ subscriber.first_name || '-' }}</td>
                    <td class="p-3 border-b">{{ subscriber.last_name || '-' }}</td>
                    <td class="p-3 border-b">
                        <span :class="subscriber.status === 'subscribed' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'"
                            class="text-xs px-2 py-1 rounded-full">
                            {{ subscriber.status }}
                        </span>
                    </td>
                    <td class="p-3 border-b text-sm text-gray-500">{{ subscriber.subscribed_at }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    list: Object,
    subscribers: Object,
})
</script>