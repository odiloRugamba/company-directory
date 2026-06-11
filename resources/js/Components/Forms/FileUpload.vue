<template>
  <div class="flex flex-col gap-1">
    <label v-if="label" class="text-sm font-medium text-gray-700">{{ label }}</label>

    <div
      v-if="!previewUrl"
      class="relative flex flex-col items-center justify-center gap-3 rounded-lg border-2 border-dashed border-gray-300 px-6 py-10 transition-colors"
      :class="isDragging ? 'border-indigo-400 bg-indigo-50' : 'hover:border-gray-400 hover:bg-gray-50'"
      @dragenter.prevent="isDragging = true"
      @dragover.prevent="isDragging = true"
      @dragleave.prevent="isDragging = false"
      @drop.prevent="onDrop"
    >
      <svg
        class="h-10 w-10 text-gray-400"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
        aria-hidden="true"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"
        />
      </svg>

      <div class="text-center">
        <p class="text-sm text-gray-600">
          <span
            class="cursor-pointer font-semibold text-indigo-600 hover:text-indigo-500"
            @click="openFilePicker"
          >Click to upload</span>
          or drag and drop
        </p>
        <p class="mt-1 text-xs text-gray-500">{{ acceptDescription }} up to {{ maxSizeMb }}MB</p>
      </div>

      <input
        ref="inputRef"
        type="file"
        class="sr-only"
        :accept="accept"
        @change="onInputChange"
      />
    </div>

    <div
      v-else
      class="relative overflow-hidden rounded-lg border-2 border-dashed border-gray-300"
    >
      <img
        :src="previewUrl"
        alt="Preview"
        class="h-48 w-full object-cover"
      />
      <button
        type="button"
        class="absolute right-2 top-2 flex items-center gap-1 rounded-md bg-white/90 px-2 py-1 text-xs font-medium text-gray-700 shadow hover:bg-white"
        @click="remove"
      >
        <svg
          class="h-3.5 w-3.5"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="2"
          stroke="currentColor"
          aria-hidden="true"
        >
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
        Remove
      </button>
    </div>

    <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: File,
    default: null,
  },
  accept: {
    type: String,
    default: 'image/*',
  },
  maxSizeMb: {
    type: Number,
    default: 2,
  },
  label: {
    type: String,
    default: '',
  },
})

const emit = defineEmits(['update:modelValue'])

const inputRef = ref(null)
const isDragging = ref(false)
const previewUrl = ref(null)
const error = ref('')

const acceptDescription = computed(() => {
  if (props.accept === 'image/*') return 'PNG, JPG, GIF, WEBP'
  return props.accept.replace(/,/g, ', ')
})

watch(
  () => props.modelValue,
  (file) => {
    if (!file) {
      clearPreview()
    }
  },
)

function openFilePicker() {
  inputRef.value?.click()
}

function onInputChange(event) {
  const file = event.target.files?.[0] ?? null
  processFile(file)
  event.target.value = ''
}

function onDrop(event) {
  isDragging.value = false
  const file = event.dataTransfer.files?.[0] ?? null
  processFile(file)
}

function processFile(file) {
  error.value = ''

  if (!file) return

  const maxBytes = props.maxSizeMb * 1024 * 1024
  if (file.size > maxBytes) {
    error.value = `File is too large. Maximum allowed size is ${props.maxSizeMb}MB.`
    return
  }

  clearPreview()
  previewUrl.value = URL.createObjectURL(file)
  emit('update:modelValue', file)
}

function remove() {
  clearPreview()
  emit('update:modelValue', null)
}

function clearPreview() {
  if (previewUrl.value) {
    URL.revokeObjectURL(previewUrl.value)
    previewUrl.value = null
  }
  error.value = ''
}
</script>
