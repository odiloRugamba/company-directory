<template>
  <span
    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
    :class="tailwindClasses"
    :style="inlineStyles"
  >
    {{ label }}
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  status: {
    type: String,
    required: true,
  },
})

const colorMap = {
  pending: {
    tailwind: 'bg-yellow-100 text-yellow-800',
    style: {},
  },
  under_review: {
    tailwind: 'bg-blue-100 text-blue-800',
    style: {},
  },
  accepted: {
    tailwind: '',
    style: { backgroundColor: '#d1fae5', color: '#00C896' },
  },
  rejected: {
    tailwind: 'bg-red-100 text-red-800',
    style: {},
  },
  verified: {
    tailwind: '',
    style: { backgroundColor: '#d1fae5', color: '#00C896' },
  },
  admin: {
    tailwind: 'bg-purple-100 text-purple-800',
    style: {},
  },
  incubator: {
    tailwind: '',
    style: { backgroundColor: '#E8EDF5', color: '#0F1F3D' },
  },
  startup: {
    tailwind: '',
    style: { backgroundColor: '#fff0ed', color: '#ff6b4a' },
  },
}

const tailwindClasses = computed(() => {
  return colorMap[props.status]?.tailwind ?? 'bg-gray-100 text-gray-800'
})

const inlineStyles = computed(() => {
  return colorMap[props.status]?.style ?? {}
})

const label = computed(() => {
  return props.status
    .replace(/_/g, ' ')
    .replace(/^\w/, (c) => c.toUpperCase())
})
</script>
