<template>
  <!-- Desktop Sidebar -->
  <aside class="hidden md:flex w-64 bg-navy text-white flex-col min-h-screen">
    <!-- Logo -->
    <div class="px-6 py-8 border-b border-white/10">
      <span class="text-2xl font-bold text-white tracking-widest" style="font-family: 'Syne', sans-serif;">
        SANIA
      </span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-1">
      <Link
        v-for="item in navItems"
        :key="item.href"
        :href="item.href"
        :class="[
          'flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-150',
          isActive(item.href)
            ? 'bg-white/20 text-white'
            : 'text-white/70 hover:bg-white/10 hover:text-white',
        ]"
      >
        <span class="text-lg leading-none">{{ item.icon }}</span>
        <span>{{ item.label }}</span>
      </Link>
    </nav>
  </aside>

  <!-- Mobile Bottom Navigation Bar -->
  <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 flex flex-row">
    <Link
      v-for="item in navItems"
      :key="item.href"
      :href="item.href"
      :class="[
        'flex flex-1 flex-col items-center justify-center py-2 px-1 text-xs font-medium transition-colors duration-150',
        isActive(item.href)
          ? 'text-navy'
          : 'text-gray-500 hover:text-navy',
      ]"
    >
      <span class="text-xl leading-none mb-1">{{ item.icon }}</span>
      <span class="truncate max-w-full">{{ item.label }}</span>
    </Link>
  </nav>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
  role: {
    type: String,
    required: true,
    validator: (value) => ['admin', 'incubator', 'startup'].includes(value),
  },
});

const page = usePage();

const navMap = {
  admin: [
    { label: 'Overview', href: '/admin/dashboard', icon: '📊' },
    { label: 'Incubators', href: '/admin/incubators', icon: '🏢' },
    { label: 'Applications', href: '/admin/applications', icon: '📋' },
    { label: 'Activity Log', href: '/admin/activity-log', icon: '📜' },
  ],
  incubator: [
    { label: 'Overview', href: '/dashboard/overview', icon: '📊' },
    { label: 'Applications', href: '/dashboard/applications', icon: '📋' },
    { label: 'Profile', href: '/dashboard/profile', icon: '👤' },
  ],
  startup: [
    { label: 'Overview', href: '/dashboard/startup', icon: '🚀' },
    { label: 'My Applications', href: '/dashboard/applications', icon: '📋' },
    { label: 'Find Incubators', href: '/directory', icon: '🔍' },
    { label: 'Profile', href: '/dashboard/profile', icon: '👤' },
  ],
};

const navItems = computed(() => navMap[props.role] ?? []);

function isActive(href) {
  const url = page.url;
  if (href === '/') return url === '/';
  return url === href || url.startsWith(href + '/') || url.startsWith(href + '?');
}
</script>
