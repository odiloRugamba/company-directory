<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Pages/Layouts/AppLayout.vue';
import Badge from '@/Components/UI/Badge.vue';
import Avatar from '@/Components/UI/Avatar.vue';
import Modal from '@/Components/UI/Modal.vue';

defineProps({
    startups: Object,
});

const search = ref('');
const confirmDeactivate = ref(null);

const handleSearch = () => {
    router.get('/admin/startups', { search: search.value }, { preserveState: true, replace: true });
};

const deactivateUser = () => {
    if (!confirmDeactivate.value) return;
    router.patch(`/admin/users/${confirmDeactivate.value}/deactivate`, {}, {
        onSuccess: () => { confirmDeactivate.value = null; },
    });
};
</script>

<template>
    <AppLayout title="Startups">
        <Head title="Startups — Admin" />

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-navy">Startups</h1>
            <input
                v-model="search"
                @input="handleSearch"
                type="text"
                placeholder="Search startups…"
                class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-[#00C896] w-64"
            />
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-dark text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="text-left px-6 py-3">Startup</th>
                            <th class="text-left px-6 py-3">Country</th>
                            <th class="text-left px-6 py-3">Sector</th>
                            <th class="text-left px-6 py-3">Stage</th>
                            <th class="text-left px-6 py-3">Team</th>
                            <th class="text-left px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="startup in startups.data" :key="startup.id" class="hover:bg-slate/50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <Avatar :src="startup.logo_url" :name="startup.company_name" size="sm" />
                                    <div>
                                        <p class="font-medium text-navy">{{ startup.company_name }}</p>
                                        <p class="text-xs text-gray-500">{{ startup.tagline }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ startup.country }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block bg-gray-100 text-gray-700 px-2 py-0.5 rounded-full text-xs">
                                    {{ startup.sector }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <Badge :status="startup.stage" />
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ startup.team_size }}</td>
                            <td class="px-6 py-4">
                                <button
                                    @click="confirmDeactivate = startup.user_id"
                                    class="text-xs text-red-600 hover:text-red-800 font-medium"
                                >
                                    Deactivate
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="startups.last_page > 1" class="px-6 py-4 border-t border-gray-100 flex gap-2">
                <Link
                    v-for="link in startups.links"
                    :key="link.label"
                    :href="link.url || '#'"
                    class="px-3 py-1 rounded text-sm"
                    :class="link.active ? 'bg-navy text-white' : 'text-gray-600 hover:bg-gray-100'"
                    v-html="link.label"
                />
            </div>
        </div>

        <!-- Confirmation Modal -->
        <Modal :open="!!confirmDeactivate" title="Deactivate User" @close="confirmDeactivate = null">
            <div class="p-6">
                <p class="text-gray-600 mb-6">Are you sure you want to deactivate this user?</p>
                <div class="flex gap-3 justify-end">
                    <button @click="confirmDeactivate = null" class="px-4 py-2 text-sm text-gray-600 border rounded-lg hover:bg-gray-50">Cancel</button>
                    <button @click="deactivateUser" class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700">Deactivate</button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>
