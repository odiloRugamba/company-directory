<script setup>
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Pages/Layouts/AppLayout.vue'
import StatCard from '@/Components/UI/StatCard.vue'
import Badge from '@/Components/UI/Badge.vue'
import Avatar from '@/Components/UI/Avatar.vue'

const props = defineProps({
    stats: {
        type: Object,
        required: true,
    },
    recent_applications: {
        type: Array,
        default: () => [],
    },
    recent_registrations: {
        type: Array,
        default: () => [],
    },
})

const daysAgo = (dateString) => {
    if (!dateString) return 'Unknown'
    const date = new Date(dateString)
    const now = new Date()
    const diffMs = now - date
    const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24))
    if (diffDays === 0) return 'today'
    if (diffDays === 1) return '1 day ago'
    return `${diffDays} days ago`
}

const formatDate = (dateString) => {
    if (!dateString) return '—'
    return new Date(dateString).toLocaleDateString('en-GB', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    })
}
</script>

<template>
    <AppLayout title="Admin Dashboard">
        <Head title="Admin Dashboard" />

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <StatCard
                title="Total Incubators"
                :value="stats.total_incubators ?? 0"
                icon="🏢"
            />
            <StatCard
                title="Total Startups"
                :value="stats.total_startups ?? 0"
                icon="🚀"
            />
            <StatCard
                title="Pending Applications"
                :value="stats.pending_applications ?? 0"
                icon="📋"
            />
            <StatCard
                title="Countries"
                :value="stats.countries ?? 0"
                icon="🌍"
            />
        </div>

        <!-- Two-column section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Recent Applications -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-[#1a2e4a]">Recent Applications</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="text-left px-6 py-3">Startup → Incubator</th>
                                <th class="text-left px-6 py-3">Status</th>
                                <th class="text-left px-6 py-3">Submitted</th>
                                <th class="text-left px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr
                                v-if="recent_applications.length === 0"
                            >
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400 text-sm">
                                    No recent applications.
                                </td>
                            </tr>
                            <tr
                                v-for="application in recent_applications"
                                :key="application.id"
                                class="hover:bg-gray-50"
                            >
                                <td class="px-6 py-4">
                                    <p class="font-medium text-[#1a2e4a]">{{ application.startup_name }}</p>
                                    <p class="text-xs text-gray-400">{{ application.incubator_name }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <Badge :status="application.status" />
                                </td>
                                <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                                    {{ formatDate(application.submitted_at ?? application.created_at) }}
                                </td>
                                <td class="px-6 py-4">
                                    <Link
                                        :href="`/admin/applications/${application.id}`"
                                        class="text-xs font-medium text-[#00C896] hover:underline whitespace-nowrap"
                                    >
                                        View
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Registrations -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-[#1a2e4a]">Recent Registrations</h2>
                </div>

                <ul class="divide-y divide-gray-100">
                    <li
                        v-if="recent_registrations.length === 0"
                        class="px-6 py-8 text-center text-gray-400 text-sm"
                    >
                        No recent registrations.
                    </li>
                    <li
                        v-for="user in recent_registrations"
                        :key="user.id"
                        class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50"
                    >
                        <Avatar
                            :src="user.avatar_url ?? null"
                            :name="user.name"
                            size="md"
                        />
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-[#1a2e4a] truncate">{{ user.name }}</p>
                            <p class="text-xs text-gray-400">joined {{ daysAgo(user.created_at) }}</p>
                        </div>
                        <Badge :status="user.role" />
                    </li>
                </ul>
            </div>

        </div>
    </AppLayout>
</template>
