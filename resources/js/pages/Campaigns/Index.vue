<template>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Campaigns</h1>
            <Link :href="route('campaigns.create')"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                New Campaign
            </Link>
        </div>

        <div v-if="campaigns.data.length === 0" class="text-gray-500">
            No campaigns yet. Create your first one!
        </div>

        <div v-else class="space-y-4">
            <div v-for="campaign in campaigns.data" :key="campaign.id"
                class="border rounded-lg p-4 flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-lg">{{ campaign.name }}</h2>
                    <p class="text-gray-500 text-sm">{{ campaign.subject }}</p>
                    <span :class="statusClass(campaign.status)"
                        class="text-xs px-2 py-1 rounded-full font-medium">
                        {{ campaign.status }}
                    </span>
                </div>
                <div class="flex gap-2">
                    <Link :href="route('campaigns.edit', campaign.id)"
                        class="text-blue-600 hover:underline text-sm">Edit</Link>
                    <button @click="deleteCampaign(campaign.id)"
                        class="text-red-600 hover:underline text-sm">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
    campaigns: Object,
})

function statusClass(status) {
    const classes = {
        draft: 'bg-gray-100 text-gray-600',
        scheduled: 'bg-blue-100 text-blue-600',
        sending: 'bg-yellow-100 text-yellow-600',
        sent: 'bg-green-100 text-green-600',
        failed: 'bg-red-100 text-red-600',
    }
    return classes[status] || 'bg-gray-100 text-gray-600'
}

function deleteCampaign(id) {
    if (confirm('Are you sure you want to delete this campaign?')) {
        router.delete(route('campaigns.destroy', id))
    }
}
</script>