<template>
  <header class="h-16 bg-white border-b border-slate-dark px-6 flex items-center">
    <div class="flex items-center">
      <slot>
        <span v-if="title" class="text-lg font-semibold text-slate-800">{{ title }}</span>
      </slot>
    </div>

    <div class="flex-1" />

    <div class="flex items-center gap-4">
      <NotificationBell />

      <div class="flex items-center gap-2">
        <Avatar :name="user.name" />
        <span class="text-sm font-medium text-slate-700">{{ user.name }}</span>
      </div>

      <Link
        href="/logout"
        method="post"
        as="button"
        class="text-sm text-slate-500 hover:text-slate-700 transition-colors"
      >
        Logout
      </Link>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import Avatar from '../UI/Avatar.vue'
import NotificationBell from './NotificationBell.vue'

const props = defineProps({
  title: {
    type: String,
    default: undefined,
  },
})

const page = usePage()
const user = computed(() => page.props.auth.user)
</script>
