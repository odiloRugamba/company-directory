<template>
  <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
    <div class="flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-6">
      <span class="text-4xl leading-none" role="img" :aria-label="title">{{ icon }}</span>
    </div>

    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ title }}</h3>

    <p class="text-sm text-gray-500 max-w-sm mb-6">{{ description }}</p>

    <a
      v-if="actionLabel && actionHref"
      :href="actionHref"
      class="inline-flex items-center px-5 py-2.5 bg-navy-700 text-white text-sm font-medium rounded-lg hover:bg-navy-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy-700 transition-colors"
      style="background-color: #1e3a5f;"
      @mouseover="$event.currentTarget.style.backgroundColor = '#162d4a'"
      @mouseleave="$event.currentTarget.style.backgroundColor = '#1e3a5f'"
    >
      {{ actionLabel }}
    </a>
  </div>
</template>

<script setup>
defineProps({
  icon: {
    type: String,
    required: true,
  },
  title: {
    type: String,
    required: true,
  },
  description: {
    type: String,
    required: true,
  },
  actionLabel: {
    type: String,
    default: null,
  },
  actionHref: {
    type: String,
    default: null,
  },
})
</script>
