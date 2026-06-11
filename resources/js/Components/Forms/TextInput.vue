<template>
  <div class="w-full">
    <input
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :disabled="disabled"
      :class="[
        'w-full px-3 py-2 rounded-lg border bg-white text-gray-900 placeholder-gray-400 transition-colors duration-150 outline-none',
        'focus:ring-2 focus:ring-[#00C896] focus:border-[#00C896]',
        error
          ? 'border-red-500 focus:ring-red-300 focus:border-red-500'
          : 'border-gray-300',
        disabled ? 'bg-gray-100 cursor-not-allowed opacity-60' : '',
      ]"
      @input="$emit('update:modelValue', $event.target.value)"
    />
    <p v-if="error" class="mt-1 text-sm text-red-500">{{ error }}</p>
  </div>
</template>

<script setup>
defineProps({
  modelValue: {
    type: [String, Number],
    default: '',
  },
  type: {
    type: String,
    default: 'text',
  },
  placeholder: {
    type: String,
    default: '',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  error: {
    type: String,
    default: '',
  },
})

defineEmits(['update:modelValue'])
</script>
