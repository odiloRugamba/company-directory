<template>
  <div class="bg-white rounded-xl shadow-sm p-6 relative overflow-hidden">
    <div class="flex items-start justify-between">
      <div class="flex-1">
        <p class="text-gray-500 text-sm uppercase tracking-wide font-medium">{{ title }}</p>
        <p class="text-navy font-bold text-3xl mt-1 text-[#1a2e4a]">{{ value }}</p>
        <p v-if="trend" class="text-xs text-gray-400 mt-2">{{ trend }}</p>
      </div>
      <div class="flex-shrink-0 w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-2xl ml-4">
        {{ icon }}
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
  value: {
    type: [String, Number],
    required: true,
  },
  icon: {
    type: String,
    required: true,
  },
  trend: {
    type: String,
    default: null,
  },
})
</script>
