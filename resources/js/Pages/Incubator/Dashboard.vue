<template>
    <AppLayout>
        <Head title="Incubator Dashboard" />

        <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Dashboard</h1>

            <Alert
                v-if="stats.completeness < 100"
                variant="warning"
                class="mb-6"
            >
                Complete your profile to increase visibility.
                <Link href="/dashboard/profile" class="font-medium underline ml-1">
                    Complete profile
                </Link>
            </Alert>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <StatCard
                    label="Applications Received"
                    :value="stats.total_received"
                    icon="📨"
                />
                <StatCard
                    label="Pending Review"
                    :value="stats.pending"
                    icon="⏳"
                />
                <StatCard
                    label="Accepted Startups"
                    :value="stats.accepted"
                    icon="✅"
                />
                <StatCard
                    label="Profile Completeness"
                    :value="stats.completeness + '%'"
                    icon="📊"
                />
            </div>

            <div class="flex flex-wrap gap-4">
                <Link
                    href="/dashboard/applications"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                >
                    Review Applications
                </Link>
                <Link
                    :href="`/incubators/${profile.slug}`"
                    target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-white text-indigo-600 text-sm font-medium rounded-md shadow-sm border border-indigo-600 hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                >
                    View Public Profile
                </Link>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Pages/Layouts/AppLayout.vue';
import StatCard from '@/Components/UI/StatCard.vue';
import Alert from '@/Components/UI/Alert.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    stats: {
        type: Object,
        required: true,
    },
    profile: {
        type: Object,
        required: true,
    },
});
</script>
