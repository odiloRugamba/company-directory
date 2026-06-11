<script setup>
defineProps({
    modelValue: {
        default: null,
    },
    options: {
        type: Array,
        default: () => [],
    },
    placeholder: {
        type: String,
        default: null,
    },
    error: {
        type: String,
        default: null,
    },
});

defineEmits(['update:modelValue']);
</script>

<template>
    <div>
        <select
            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-full"
            :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': error }"
            :value="modelValue"
            @change="$emit('update:modelValue', $event.target.value)"
        >
            <option v-if="placeholder" value="" disabled :selected="modelValue === null || modelValue === ''">
                {{ placeholder }}
            </option>
            <option
                v-for="option in options"
                :key="option.value"
                :value="option.value"
            >
                {{ option.label }}
            </option>
        </select>
        <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    </div>
</template>
