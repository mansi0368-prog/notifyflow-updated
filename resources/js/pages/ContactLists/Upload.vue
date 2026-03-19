<template>
    <div class="p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-2">Upload Subscribers</h1>
        <p class="text-gray-500 mb-6">Upload a CSV file to add subscribers to <strong>{{ list.name }}</strong></p>

        <!-- CSV format guide -->
        <div class="bg-blue-50 border border-blue-200 rounded p-4 mb-6">
            <p class="text-blue-700 font-medium text-sm mb-2">📋 CSV Format:</p>
            <code class="text-blue-600 text-sm">email, first_name, last_name</code>
            <p class="text-blue-600 text-sm mt-1">Only <strong>email</strong> column is required. Duplicates are skipped automatically.</p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Select CSV File</label>
                <input type="file" accept=".csv,.txt"
                    @change="form.csv_file = $event.target.files[0]"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <p v-if="form.errors.csv_file" class="text-red-500 text-sm mt-1">{{ form.errors.csv_file }}</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" :disabled="form.processing"
                    class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 disabled:opacity-50">
                    {{ form.processing ? 'Uploading...' : 'Upload CSV' }}
                </button>
                <Link :href="route('contact-lists.show', list.id)"
                    class="px-6 py-2 border rounded hover:bg-gray-50">
                    Cancel
                </Link>
            </div>
        </form>
    </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({ list: Object })

const form = useForm({ csv_file: null })

function submit() {
    form.post(route('contact-lists.upload.csv', props.list.id), {
        forceFormData: true,
    })
}
</script>