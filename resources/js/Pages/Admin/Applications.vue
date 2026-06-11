<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Pages/Layouts/AppLayout.vue';
import Badge from '@/Components/UI/Badge.vue';
import Avatar from '@/Components/UI/Avatar.vue';

const props = defineProps({
    applications: Object,
    activeStatus: {
        type: String,
        default: null,
    },
});

const tabs = [
    { label: 'All', value: null },
    { label: 'Pending', value: 'pending' },
    { label: 'Under Review', value: 'under_review' },
    { label: 'Accepted', value: 'accepted' },
    { label: 'Rejected', value: 'rejected' },
];

const expandedRows = ref(new Set());

const toggleRow = (id) => {
    if (expandedRows.value.has(id)) {
        expandedRows.value.delete(id);
    } else {
        expandedRows.value.add(id);
    }
};

const isExpanded = (id) => expandedRows.value.has(id);

const navigateTab = (value) => {
    const params = value ? { status: value } : {};
    router.get('/admin/applications', params);
};

const formatDate = (dateStr) => {
    if (!dateStr) return '—';
    return new Date(dateStr).toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const isActiveTab = (value) => {
    if (value === null) return props.activeStatus === null || props.activeStatus === undefined || props.activeStatus === '';
    return props.activeStatus === value;
};
</script>

<template>
    <AppLayout title="Applications">
        <Head title="Applications — Admin" />

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-navy mb-4">Applications</h1>

            <!-- Tab Bar -->
            <div class="flex gap-1 border-b border-gray-200">
                <button
                    v-for="tab in tabs"
                    :key="String(tab.value)"
                    @click="navigateTab(tab.value)"
                    class="px-4 py-2 text-sm font-medium border-b-2 transition-colors"
                    :class="isActiveTab(tab.value)
                        ? 'border-[#00C896] text-[#00C896]'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                >
                    {{ tab.label }}
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-dark text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="text-left px-6 py-3">Startup</th>
                            <th class="text-left px-6 py-3">Incubator</th>
                            <th class="text-left px-6 py-3">Status</th>
                            <th class="text-left px-6 py-3">Submitted</th>
                            <th class="text-left px-6 py-3">Last Updated</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template v-for="application in applications.data" :key="application.id">
                            <!-- Main Row -->
                            <tr
                                class="hover:bg-slate/50 cursor-pointer select-none"
                                @click="toggleRow(application.id)"
                            >
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <Avatar
                                            :src="application.startup?.startupProfile?.logo_url ?? null"
                                            :name="application.startup?.startupProfile?.company_name ?? application.startup?.name ?? 'Unknown'"
                                            size="sm"
                                        />
                                        <div>
                                            <p class="font-medium text-navy">
                                                {{ application.startup?.startupProfile?.company_name ?? application.startup?.name ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <Avatar
                                            :src="application.incubator?.incubatorProfile?.logo_url ?? null"
                                            :name="application.incubator?.incubatorProfile?.organization_name ?? application.incubator?.name ?? 'Unknown'"
                                            size="sm"
                                        />
                                        <div>
                                            <p class="font-medium text-navy">
                                                {{ application.incubator?.incubatorProfile?.organization_name ?? application.incubator?.name ?? '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <Badge :status="application.status" />
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ formatDate(application.created_at) }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    <div class="flex items-center justify-between gap-4">
                                        <span>{{ formatDate(application.updated_at) }}</span>
                                        <span
                                            class="text-gray-400 transition-transform duration-200"
                                            :class="isExpanded(application.id) ? 'rotate-180' : ''"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </span>
                                    </div>
                                </td>
                            </tr>

                            <!-- Expanded Detail Row -->
                            <tr v-show="isExpanded(application.id)">
                                <td colspan="5" class="px-6 py-0">
                                    <Transition
                                        enter-active-class="transition-all duration-200 ease-out"
                                        enter-from-class="opacity-0 -translate-y-1"
                                        enter-to-class="opacity-100 translate-y-0"
                                        leave-active-class="transition-all duration-150 ease-in"
                                        leave-from-class="opacity-100 translate-y-0"
                                        leave-to-class="opacity-0 -translate-y-1"
                                    >
                                        <div v-show="isExpanded(application.id)" class="py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Motivation</p>
                                                <div class="bg-gray-50 rounded-lg p-3 text-sm text-gray-700 whitespace-pre-wrap min-h-[60px]">
                                                    {{ application.motivation ?? '—' }}
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Reviewer Notes</p>
                                                <div class="bg-gray-50 rounded-lg p-3 text-sm text-gray-700 whitespace-pre-wrap min-h-[60px]">
                                                    {{ application.reviewer_notes ?? '—' }}
                                                </div>
                                            </div>
                                        </div>
                                    </Transition>
                                </td>
                            </tr>
                        </template>

                        <tr v-if="!applications.data || applications.data.length === 0">
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">
                                No applications found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="applications.last_page > 1" class="px-6 py-4 border-t border-gray-100 flex gap-2 flex-wrap">
                <Link
                    v-for="link in applications.links"
                    :key="link.label"
                    :href="link.url || '#'"
                    class="px-3 py-1 rounded text-sm"
                    :class="link.active ? 'bg-navy text-white' : 'text-gray-600 hover:bg-gray-100'"
                    v-html="link.label"
                />
            </div>
        </div>
    </AppLayout>
</template>
