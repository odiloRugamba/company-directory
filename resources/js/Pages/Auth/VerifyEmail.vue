<script setup>
import { computed } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({ status: String });

const form = useForm({});
const verificationLinkSent = computed(() => usePage().props.status === 'verification-link-sent');
const submit = () => form.post(route('verification.send'));
</script>

<template>
    <Head title="Verify Email — SANIA" />
    <div class="min-h-screen bg-slate flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-slate-dark p-8 text-center">
            <div class="text-4xl mb-4">📧</div>
            <h2 class="text-xl font-bold text-navy mb-2">Verify your email</h2>
            <p class="text-sm text-gray-500 mb-6">Please verify your email before continuing. Check your inbox for the verification link.</p>
            <div v-if="verificationLinkSent" class="mb-4 p-3 bg-emerald-light text-[#00C896] text-sm rounded-lg">A new verification link has been sent.</div>
            <form @submit.prevent="submit">
                <button type="submit" :disabled="form.processing"
                    class="w-full bg-navy text-white rounded-lg py-2.5 font-semibold disabled:opacity-60 mb-3">
                    Resend Verification Email
                </button>
            </form>
            <Link :href="route('logout')" method="post" as="button" class="text-sm text-gray-500 hover:text-red-500">Log Out</Link>
        </div>
    </div>
</template>
