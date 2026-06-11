<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Pages/Layouts/AppLayout.vue'
import StatCard from '@/Components/UI/StatCard.vue'
import EmptyState from '@/Components/UI/EmptyState.vue'

defineProps({
  stats: {
    type: Object,
    required: true,
  },
  hasApplications: {
    type: Boolean,
    required: true,
  },
})
</script>

<template>
  <Head title="Dashboard" />

  <AppLayout title="Dashboard">
    <div v-if="!hasApplications" class="w-full">
      <EmptyState
        icon="🚀"
        title="Start Your Journey"
        description="Find the right incubator to accelerate your startup."
        actionLabel="Browse Incubators"
        actionHref="/directory"
      />
    </div>

    <div v-else>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <StatCard
          title="Total Applications"
          :value="stats.total"
          icon="📋"
        />
        <StatCard
          title="Accepted"
          :value="stats.accepted"
          icon="✅"
        />
        <StatCard
          title="Pending"
          :value="stats.pending"
          icon="⏳"
        />
        <StatCard
          title="Rejected"
          :value="stats.rejected"
          icon="❌"
        />
      </div>

      <div class="flex justify-end">
        <Link
          href="/directory"
          class="inline-flex items-center px-5 py-2.5 text-white text-sm font-medium rounded-lg transition-colors"
          style="background-color: #1e3a5f;"
          @mouseover="$event.currentTarget.style.backgroundColor = '#162d4a'"
          @mouseleave="$event.currentTarget.style.backgroundColor = '#1e3a5f'"
        >
          Find More Incubators
        </Link>
      </div>
    </div>
  </AppLayout>
</template>
