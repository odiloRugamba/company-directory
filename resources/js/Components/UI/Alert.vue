<template>
  <div
    v-if="visible"
    :class="[
      'flex items-start gap-3 px-4 py-3 rounded border-l-4',
      colorClasses.bg,
      colorClasses.border,
      colorClasses.text,
    ]"
    role="alert"
  >
    <span class="text-lg leading-none mt-0.5 flex-shrink-0">{{ icon }}</span>

    <p class="flex-1 text-sm font-medium">{{ message }}</p>

    <button
      v-if="dismissible"
      type="button"
      class="flex-shrink-0 ml-auto -mr-1 -mt-0.5 p-1 rounded hover:bg-black/10 focus:outline-none focus:ring-2 focus:ring-current transition-colors"
      aria-label="Dismiss"
      @click="dismiss"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="h-4 w-4"
        viewBox="0 0 20 20"
        fill="currentColor"
        aria-hidden="true"
      >
        <path
          fill-rule="evenodd"
          d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
          clip-rule="evenodd"
        />
      </svg>
    </button>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  type: {
    type: String,
    default: 'info',
    validator: (value) => ['success', 'error', 'warning', 'info'].includes(value),
  },
  message: {
    type: String,
    required: true,
  },
  dismissible: {
    type: Boolean,
    default: true,
  },
})

const emit = defineEmits(['dismiss'])

const visible = ref(true)

const colorMap = {
  success: {
    bg: 'bg-green-50',
    border: 'border-green-500',
    text: 'text-green-800',
  },
  error: {
    bg: 'bg-red-50',
    border: 'border-red-500',
    text: 'text-red-800',
  },
  warning: {
    bg: 'bg-yellow-50',
    border: 'border-yellow-500',
    text: 'text-yellow-800',
  },
  info: {
    bg: 'bg-blue-50',
    border: 'border-blue-500',
    text: 'text-blue-800',
  },
}

const iconMap = {
  success: '✅',
  error: '❌',
  warning: '⚠️',
  info: 'ℹ️',
}

const colorClasses = computed(() => colorMap[props.type] ?? colorMap.info)
const icon = computed(() => iconMap[props.type] ?? iconMap.info)

function dismiss() {
  visible.value = false
  emit('dismiss')
}
</script>
