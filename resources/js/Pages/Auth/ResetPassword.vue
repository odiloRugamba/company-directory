<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({ token: String, email: String });

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => form.post(route('password.store'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
});
</script>

<template>
    <Head title="Reset Password — SANIA" />
    <div class="min-h-screen bg-slate flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-slate-dark p-8">
            <h2 class="text-xl font-bold text-navy mb-6">Set new password</h2>
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input v-model="form.email" type="email" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-[#00C896]" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input v-model="form.password" type="password" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-[#00C896]" />
                    <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input v-model="form.password_confirmation" type="password" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-[#00C896]" />
                </div>
                <button type="submit" :disabled="form.processing"
                    class="w-full bg-navy text-white rounded-lg py-2.5 font-semibold disabled:opacity-60">
                    {{ form.processing ? 'Saving…' : 'Reset Password' }}
                </button>
            </form>
        </div>
    </div>
</template>
