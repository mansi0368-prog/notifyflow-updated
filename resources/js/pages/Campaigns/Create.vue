<template>
    <div class="p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Create Campaign</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Campaign Name</label>
                <input v-model="form.name" type="text"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. March Newsletter" />
                <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Email Subject</label>
                <input v-model="form.subject" type="text"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. Your monthly update is here!" />
                <p v-if="form.errors.subject" class="text-red-500 text-sm mt-1">{{ form.errors.subject }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Email Body</label>
                <textarea v-model="form.body" rows="8"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Write your email content here..."></textarea>
                <p v-if="form.errors.body" class="text-red-500 text-sm mt-1">{{ form.errors.body }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Schedule At (optional)</label>
                <input v-model="form.scheduled_at" type="datetime-local"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <p v-if="form.errors.scheduled_at" class="text-red-500 text-sm mt-1">{{ form.errors.scheduled_at }}</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" :disabled="form.processing"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 disabled:opacity-50">
                    {{ form.processing ? 'Creating...' : 'Create Campaign' }}
                </button>
                <Link :href="route('campaigns.index')"
                    class="px-6 py-2 border rounded hover:bg-gray-50">
                    Cancel
                </Link>
            </div>
        </form>
    </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    subject: '',
    body: '',
    scheduled_at: null,
})

function submit() {
    form.post(route('campaigns.store'))
}
</script>