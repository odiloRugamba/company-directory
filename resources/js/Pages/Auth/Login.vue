<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({ email: '', password: '', remember: false });

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const demoAccounts = [
    { label: 'Admin', email: 'admin@sania.africa', password: 'password', color: 'bg-coral/10 text-coral border-coral/20 hover:bg-coral/20' },
    { label: 'Incubator', email: 'incubator@hanga.rw', password: 'password', color: 'bg-navy/8 text-navy border-navy/20 hover:bg-navy/15' },
    { label: 'Startup', email: 'startup@demo.com', password: 'password', color: 'bg-emerald/10 text-emerald border-emerald/20 hover:bg-emerald/20' },
];

function fillDemo(account) {
    form.email = account.email;
    form.password = account.password;
}
</script>

<template>
    <Head title="Sign In — SANIA" />
    <div class="min-h-screen bg-slate flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="font-display text-3xl font-bold text-navy">SANIA</h1>
                <p class="text-gray-500 mt-1">Africa's Startup Ecosystem</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-dark p-8">
                <h2 class="text-xl font-bold text-navy mb-6">Welcome back</h2>
                <div v-if="status" class="mb-4 p-3 bg-emerald-light text-emerald text-sm rounded-lg">{{ status }}</div>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input v-model="form.email" type="email" required autocomplete="email" placeholder="you@example.com"
                            class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896]"
                            :class="form.errors.email ? 'border-red-500' : 'border-gray-300'" />
                        <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input v-model="form.password" type="password" required autocomplete="current-password"
                            class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896]"
                            :class="form.errors.password ? 'border-red-500' : 'border-gray-300'" />
                        <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input v-model="form.remember" type="checkbox" class="rounded border-gray-300 text-[#00C896]" />
                            Remember me
                        </label>
                        <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-[#00C896] hover:underline">Forgot password?</Link>
                    </div>
                    <button type="submit" :disabled="form.processing"
                        class="w-full bg-navy text-white rounded-lg py-2.5 font-semibold hover:bg-navy-light transition disabled:opacity-60">
                        {{ form.processing ? 'Signing in…' : 'Sign In' }}
                    </button>
                </form>

                <!-- Dev-mode credential picker -->
                <div class="mt-6 rounded-xl border border-dashed border-amber-300 bg-amber-50 p-4">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                        <span class="text-xs font-semibold text-amber-700 uppercase tracking-wide">Dev mode — demo accounts</span>
                    </div>
                    <p class="text-xs text-amber-600 mb-3">These credentials are for local development only. Remove this panel before going to production.</p>
                    <div class="flex gap-2">
                        <button
                            v-for="account in demoAccounts"
                            :key="account.email"
                            type="button"
                            :class="['flex-1 rounded-lg border px-3 py-2 text-xs font-semibold transition-colors', account.color]"
                            @click="fillDemo(account)"
                        >
                            {{ account.label }}
                        </button>
                    </div>
                    <p class="text-xs text-amber-500 mt-2 text-center">Click a role to fill credentials, then Sign In</p>
                </div>

                <p class="mt-4 text-center text-sm text-gray-500">
                    Don't have an account?
                    <Link :href="route('register')" class="text-[#00C896] font-semibold hover:underline">Create one</Link>
                </p>
            </div>
        </div>
    </div>
</template>
