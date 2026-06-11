<template>
  <div
    :class="[sizeClass, 'rounded-full flex items-center justify-center overflow-hidden flex-shrink-0']"
  >
    <img
      v-if="src"
      :src="src"
      :alt="name"
      :class="[sizeClass, 'rounded-full object-cover']"
    />
    <div
      v-else
      :class="[sizeClass, 'rounded-full bg-navy flex items-center justify-center']"
      style="background-color: #001f5b;"
    >
      <span :class="textSizeClass" class="text-white font-semibold select-none leading-none">
        {{ initials }}
      </span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  src: {
    type: String,
    default: null,
  },
  name: {
    type: String,
    required: true,
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value),
  },
})

const sizeClass = computed(() => {
  const sizes = {
    sm: 'w-8 h-8',
    md: 'w-10 h-10',
    lg: 'w-16 h-16',
  }
  return sizes[props.size]
})

const textSizeClass = computed(() => {
  const sizes = {
    sm: 'text-xs',
    md: 'text-sm',
    lg: 'text-xl',
  }
  return sizes[props.size]
})

const initials = computed(() => {
  if (!props.name) return ''
  return props.name
    .trim()
    .split(/\s+/)
    .slice(0, 2)
    .map((word) => word.charAt(0).toUpperCase())
    .join('')
})
</script>
