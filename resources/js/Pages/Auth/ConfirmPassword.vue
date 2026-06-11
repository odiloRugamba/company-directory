<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({ password: '' });
const submit = () => form.post(route('password.confirm'), { onFinish: () => form.reset() });
</script>

<template>
    <Head title="Confirm Password — SANIA" />
    <div class="min-h-screen bg-slate flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-slate-dark p-8">
            <h2 class="text-xl font-bold text-navy mb-2">Confirm your password</h2>
            <p class="text-sm text-gray-500 mb-6">This is a secure area. Please confirm your password before continuing.</p>
            <form @submit.prevent="submit" class="space-y-4">
                <input v-model="form.password" type="password" required placeholder="Password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-[#00C896]" />
                <p v-if="form.errors.password" class="text-red-500 text-xs">{{ form.errors.password }}</p>
                <button type="submit" :disabled="form.processing"
                    class="w-full bg-navy text-white rounded-lg py-2.5 font-semibold disabled:opacity-60">
                    Confirm
                </button>
            </form>
        </div>
    </div>
</template>
