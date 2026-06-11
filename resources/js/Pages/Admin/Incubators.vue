<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Pages/Layouts/AppLayout.vue';
import Badge from '@/Components/UI/Badge.vue';
import Avatar from '@/Components/UI/Avatar.vue';
import Modal from '@/Components/UI/Modal.vue';

defineProps({
    incubators: Object,
});

const search = ref('');
const confirmDeactivate = ref(null);

const handleSearch = () => {
    router.get('/admin/incubators', { search: search.value }, { preserveState: true, replace: true });
};

const verifyIncubator = (incubator) => {
    if (incubator.is_verified) {
        if (!confirm('Are you sure you want to unverify this incubator?')) return;
    }
    router.patch(`/admin/incubators/${incubator.id}/verify`);
};

const deactivateUser = () => {
    if (!confirmDeactivate.value) return;
    router.patch(`/admin/users/${confirmDeactivate.value}/deactivate`, {}, {
        onSuccess: () => { confirmDeactivate.value = null; },
    });
};
</script>

<template>
    <AppLayout title="Incubators">
        <Head title="Incubators — Admin" />

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-navy">Incubators</h1>
            <input
                v-model="search"
                @input="handleSearch"
                type="text"
                placeholder="Search incubators…"
                class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-[#00C896] w-64"
            />
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-dark text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="text-left px-6 py-3">Incubator</th>
                            <th class="text-left px-6 py-3">Country</th>
                            <th class="text-left px-6 py-3">Sectors</th>
                            <th class="text-left px-6 py-3">Capacity</th>
                            <th class="text-left px-6 py-3">Verified</th>
                            <th class="text-left px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="incubator in incubators.data" :key="incubator.id" class="hover:bg-slate/50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <Avatar :src="incubator.logo_url" :name="incubator.organization_name" size="sm" />
                                    <div>
                                        <p class="font-medium text-navy">{{ incubator.organization_name }}</p>
                                        <p class="text-xs text-gray-500">{{ incubator.user?.email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ incubator.country }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    <template v-if="incubator.focus_sectors && incubator.focus_sectors.length">
                                        <span
                                            v-for="sector in incubator.focus_sectors.slice(0, 2)"
                                            :key="sector"
                                            class="inline-block bg-gray-100 text-gray-700 px-2 py-0.5 rounded-full text-xs"
                                        >
                                            {{ sector }}
                                        </span>
                                        <span
                                            v-if="incubator.focus_sectors.length > 2"
                                            class="inline-block bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full text-xs"
                                        >
                                            …
                                        </span>
                                    </template>
                                    <span v-else class="text-gray-400 text-xs">—</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ incubator.capacity ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <Badge :status="incubator.is_verified ? 'verified' : 'pending'" />
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <button
                                        @click="verifyIncubator(incubator)"
                                        class="text-xs font-medium"
                                        :class="incubator.is_verified
                                            ? 'text-yellow-600 hover:text-yellow-800'
                                            : 'text-[#00C896] hover:text-emerald-700'"
                                    >
                                        {{ incubator.is_verified ? 'Unverify' : 'Verify' }}
                                    </button>
                                    <button
                                        @click="confirmDeactivate = incubator.user?.id"
                                        class="text-xs text-red-600 hover:text-red-800 font-medium"
                                    >
                                        Deactivate
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!incubators.data || incubators.data.length === 0">
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400 text-sm">No incubators found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="incubators.last_page > 1" class="px-6 py-4 border-t border-gray-100 flex gap-2">
                <Link
                    v-for="link in incubators.links"
                    :key="link.label"
                    :href="link.url || '#'"
                    class="px-3 py-1 rounded text-sm"
                    :class="link.active ? 'bg-navy text-white' : 'text-gray-600 hover:bg-gray-100'"
                    v-html="link.label"
                />
            </div>
        </div>

        <!-- Deactivate Confirmation Modal -->
        <Modal :open="!!confirmDeactivate" title="Deactivate User" @close="confirmDeactivate = null">
            <p class="text-gray-600 mb-6">Are you sure you want to deactivate this incubator's account? They will lose access to the platform.</p>
            <div class="flex gap-3 justify-end">
                <button
                    @click="confirmDeactivate = null"
                    class="px-4 py-2 text-sm text-gray-600 border rounded-lg hover:bg-gray-50"
                >
                    Cancel
                </button>
                <button
                    @click="deactivateUser"
                    class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700"
                >
                    Deactivate
                </button>
            </div>
        </Modal>
    </AppLayout>
</template>
