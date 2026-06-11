<script setup>
import { ref, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Pages/Layouts/AppLayout.vue';
import Avatar from '@/Components/UI/Avatar.vue';

const props = defineProps({
    logs: Object,
    actions: Array,
});

const selectedAction = ref(new URLSearchParams(window.location.search).get('action') || '');

const onActionChange = () => {
    router.get(
        '/admin/activity-log',
        selectedAction.value ? { action: selectedAction.value } : {},
        { preserveState: true, replace: true }
    );
};

const formatDate = (dateStr) => {
    const d = new Date(dateStr);
    return new Intl.DateTimeFormat('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false,
    }).format(d);
};

const formatSubjectType = (type) => {
    if (!type) return '—';
    return type.split('\\').pop();
};
</script>

<template>
    <AppLayout title="Activity Log">
        <Head title="Activity Log — Admin" />

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-navy">Activity Log</h1>

            <div class="flex items-center gap-2">
                <label for="action-filter" class="text-sm text-gray-600">Filter by action:</label>
                <select
                    id="action-filter"
                    v-model="selectedAction"
                    @change="onActionChange"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#00C896] bg-white"
                >
                    <option value="">All actions</option>
                    <option v-for="action in actions" :key="action" :value="action">
                        {{ action }}
                    </option>
                </select>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-dark text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="text-left px-6 py-3">Timestamp</th>
                            <th class="text-left px-6 py-3">User</th>
                            <th class="text-left px-6 py-3">Action</th>
                            <th class="text-left px-6 py-3">Subject</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="logs.data.length === 0">
                            <td colspan="4" class="px-6 py-10 text-center text-gray-400 text-sm">
                                No activity log entries found.
                            </td>
                        </tr>
                        <tr
                            v-for="log in logs.data"
                            :key="log.id"
                            class="hover:bg-slate/50"
                        >
                            <td class="px-6 py-4 text-gray-500 whitespace-nowrap text-xs">
                                {{ formatDate(log.created_at) }}
                            </td>
                            <td class="px-6 py-4">
                                <div v-if="log.user" class="flex items-center gap-2">
                                    <Avatar
                                        :src="log.user.avatar_url ?? null"
                                        :name="log.user.name"
                                        size="sm"
                                    />
                                    <span class="text-gray-700 font-medium">{{ log.user.name }}</span>
                                </div>
                                <span v-else class="text-gray-400 italic text-xs">System</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block bg-gray-100 text-gray-700 font-mono text-sm px-2.5 py-0.5 rounded">
                                    {{ log.action }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                <span v-if="log.subject_type">
                                    {{ formatSubjectType(log.subject_type) }}
                                    <span v-if="log.subject_id" class="text-gray-400">#{{ log.subject_id }}</span>
                                </span>
                                <span v-else class="text-gray-400">—</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="logs.last_page > 1" class="px-6 py-4 border-t border-gray-100 flex flex-wrap gap-1">
                <component
                    v-for="link in logs.links"
                    :key="link.label"
                    :is="link.url ? 'a' : 'span'"
                    :href="link.url ?? undefined"
                    class="px-3 py-1 rounded text-sm"
                    :class="[
                        link.active
                            ? 'bg-navy text-white'
                            : link.url
                                ? 'text-gray-600 hover:bg-gray-100 cursor-pointer'
                                : 'text-gray-300 cursor-default',
                    ]"
                    v-html="link.label"
                />
            </div>
        </div>
    </AppLayout>
</template>
