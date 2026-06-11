<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({ status: String });

const form = useForm({ email: '' });
const submit = () => form.post(route('password.email'));
</script>

<template>
    <Head title="Forgot Password — SANIA" />
    <div class="min-h-screen bg-slate flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="font-display text-3xl font-bold text-navy">SANIA</h1>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-dark p-8">
                <h2 class="text-xl font-bold text-navy mb-2">Forgot your password?</h2>
                <p class="text-sm text-gray-500 mb-6">Enter your email and we'll send you a reset link.</p>
                <div v-if="status" class="mb-4 p-3 bg-emerald-light text-[#00C896] text-sm rounded-lg">{{ status }}</div>
                <form @submit.prevent="submit" class="space-y-4">
                    <input v-model="form.email" type="email" required placeholder="your@email.com"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-[#00C896]" />
                    <p v-if="form.errors.email" class="text-red-500 text-xs">{{ form.errors.email }}</p>
                    <button type="submit" :disabled="form.processing"
                        class="w-full bg-navy text-white rounded-lg py-2.5 font-semibold disabled:opacity-60">
                        {{ form.processing ? 'Sending…' : 'Send Reset Link' }}
                    </button>
                </form>
                <p class="mt-4 text-center text-sm"><Link :href="route('login')" class="text-[#00C896] hover:underline">Back to login</Link></p>
            </div>
        </div>
    </div>
</template>
