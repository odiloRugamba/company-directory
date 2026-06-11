<template>
  <teleport to="body">
    <div v-if="open || isVisible" class="fixed inset-0 z-50 flex justify-end">
      <!-- Backdrop -->
      <transition
        enter-active-class="transition-opacity duration-300 ease-in-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-300 ease-in-out"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-if="open"
          class="fixed inset-0 bg-black/50"
          aria-hidden="true"
          @click="$emit('close')"
        />
      </transition>

      <!-- Panel -->
      <transition
        enter-active-class="transition-transform duration-300 ease-in-out"
        enter-from-class="translate-x-full"
        enter-to-class="translate-x-0"
        leave-active-class="transition-transform duration-300 ease-in-out"
        leave-from-class="translate-x-0"
        leave-to-class="translate-x-full"
        @after-leave="isVisible = false"
      >
        <div
          v-if="open"
          class="relative z-50 flex h-full w-full max-w-lg flex-col bg-white shadow-xl overflow-hidden"
        >
          <!-- Header -->
          <div class="flex items-center justify-between px-4 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">{{ title }}</h2>
            <button
              type="button"
              class="rounded-md p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-colors"
              @click="$emit('close')"
            >
              <span class="sr-only">Close panel</span>
              <svg
                class="h-5 w-5"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                aria-hidden="true"
              >
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Content -->
          <div class="flex-1 overflow-y-auto px-4 py-4">
            <slot />
          </div>

          <!-- Footer (optional) -->
          <div v-if="$slots.footer" class="border-t border-gray-200 px-4 py-4">
            <slot name="footer" />
          </div>
        </div>
      </transition>
    </div>
  </teleport>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  open: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: '',
  },
})

defineEmits(['close'])

const isVisible = ref(props.open)

watch(
  () => props.open,
  (val) => {
    if (val) {
      isVisible.value = true
    }
  }
)
</script>
