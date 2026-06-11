<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Pages/Layouts/AppLayout.vue'
import Badge from '@/Components/UI/Badge.vue'
import Avatar from '@/Components/UI/Avatar.vue'
import EmptyState from '@/Components/UI/EmptyState.vue'

const props = defineProps({
    applications: {
        type: Array,
        required: true,
    },
})

function formatDate(dateStr) {
    if (!dateStr) return '—'
    return new Date(dateStr).toLocaleDateString('en-GB', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    })
}

function truncate(text, length = 80) {
    if (!text) return null
    return text.length > length ? text.slice(0, length).trimEnd() + '…' : text
}
</script>

<template>
    <AppLayout title="My Applications">
        <Head title="My Applications" />

        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-navy">My Applications</h1>
                <p class="text-sm text-gray-500 mt-1">Track the status of your incubator applications.</p>
            </div>

            <!-- Empty state -->
            <EmptyState
                v-if="applications.length === 0"
                icon="📭"
                title="No Applications Yet"
                description="You haven't applied to any incubators yet. Browse the directory to find one that's right for you."
                action-label="Browse Directory"
                action-href="/directory"
            />

            <!-- Table -->
            <div v-else class="overflow-x-auto rounded-xl border border-gray-100 shadow-sm bg-white">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Incubator
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Submitted
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Reviewer Notes
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr
                            v-for="application in applications"
                            :key="application.id"
                            class="hover:bg-gray-50 transition-colors"
                        >
                            <!-- Incubator -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <Avatar
                                        :src="application.incubator?.incubatorProfile?.logo_url ?? null"
                                        :name="application.incubator?.incubatorProfile?.org_name ?? application.incubator?.name ?? 'Incubator'"
                                        size="sm"
                                    />
                                    <span class="text-sm font-semibold text-navy leading-tight">
                                        {{ application.incubator?.incubatorProfile?.org_name ?? application.incubator?.name ?? '—' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <Badge :status="application.status" />
                            </td>

                            <!-- Submitted date -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ formatDate(application.created_at) }}
                            </td>

                            <!-- Reviewer Notes -->
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                                <span
                                    v-if="application.reviewer_notes"
                                    :title="application.reviewer_notes"
                                    class="cursor-default"
                                >
                                    {{ truncate(application.reviewer_notes) }}
                                </span>
                                <span v-else class="text-gray-400">—</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
