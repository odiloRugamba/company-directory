<template>
  <div ref="containerRef" class="relative w-full">
    <div
      class="flex flex-wrap items-center gap-1.5 min-h-[2.5rem] px-3 py-2 border border-gray-300 rounded-lg bg-white cursor-pointer focus-within:ring-2 focus-within:ring-emerald-500 focus-within:border-emerald-500"
      @click="toggleDropdown"
    >
      <span
        v-for="selected in selectedOptions"
        :key="selected.value"
        class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-800 rounded-full"
        @click.stop
      >
        {{ selected.label }}
        <button
          type="button"
          class="flex items-center justify-center w-3.5 h-3.5 rounded-full hover:bg-emerald-200 focus:outline-none"
          @click.stop="removeOption(selected.value)"
        >
          <svg class="w-2.5 h-2.5" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1 1l8 8M9 1L1 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
          </svg>
        </button>
      </span>

      <span
        v-if="selectedOptions.length === 0"
        class="text-gray-400 text-sm select-none"
      >
        {{ placeholder }}
      </span>

      <span class="ml-auto pl-1 flex items-center">
        <svg
          class="w-4 h-4 text-gray-400 transition-transform duration-150"
          :class="{ 'rotate-180': isOpen }"
          viewBox="0 0 20 20"
          fill="currentColor"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
        </svg>
      </span>
    </div>

    <transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <ul
        v-if="isOpen"
        class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto py-1"
      >
        <li
          v-for="option in remainingOptions"
          :key="option.value"
          class="flex items-center px-3 py-2 text-sm text-gray-700 cursor-pointer hover:bg-emerald-50 hover:text-emerald-900 select-none"
          @click.stop="addOption(option.value)"
        >
          {{ option.label }}
        </li>
        <li
          v-if="remainingOptions.length === 0"
          class="px-3 py-2 text-sm text-gray-400 select-none"
        >
          No options available
        </li>
      </ul>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'

const props = defineProps({
  options: {
    type: Array,
    default: () => [],
  },
  modelValue: {
    type: Array,
    default: () => [],
  },
  placeholder: {
    type: String,
    default: 'Select options...',
  },
})

const emit = defineEmits(['update:modelValue'])

const isOpen = ref(false)
const containerRef = ref(null)

const selectedOptions = computed(() =>
  props.options.filter((opt) => props.modelValue.includes(opt.value))
)

const remainingOptions = computed(() =>
  props.options.filter((opt) => !props.modelValue.includes(opt.value))
)

function toggleDropdown() {
  isOpen.value = !isOpen.value
}

function addOption(value) {
  emit('update:modelValue', [...props.modelValue, value])
  if (remainingOptions.value.length <= 1) {
    isOpen.value = false
  }
}

function removeOption(value) {
  emit('update:modelValue', props.modelValue.filter((v) => v !== value))
}

function handleOutsideClick(event) {
  if (containerRef.value && !containerRef.value.contains(event.target)) {
    isOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('mousedown', handleOutsideClick)
})

onBeforeUnmount(() => {
  document.removeEventListener('mousedown', handleOutsideClick)
})
</script>
