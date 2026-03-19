<template>
    <div class="p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Create Contact List</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">List Name</label>
                <input v-model="form.name" type="text"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g. Newsletter Subscribers" />
                <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Description (optional)</label>
                <textarea v-model="form.description" rows="3"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="What is this list for?"></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" :disabled="form.processing"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 disabled:opacity-50">
                    {{ form.processing ? 'Creating...' : 'Create List' }}
                </button>
                <Link :href="route('contact-lists.index')"
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
    description: '',
})

function submit() {
    form.post(route('contact-lists.store'))
}
</script>