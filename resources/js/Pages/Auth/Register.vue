<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'startup',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />
    <div class="min-h-screen bg-slate flex items-center justify-center p-4">
        <div class="w-full max-w-lg">
            <div class="text-center mb-8">
                <h1 class="font-display text-3xl font-bold text-navy">SANIA</h1>
                <p class="text-gray-500 mt-1">Africa's Startup Ecosystem</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-dark p-8">
                <h2 class="text-xl font-bold text-navy mb-6">Create your account</h2>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Role selector -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">I am joining as…</label>
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Incubator card -->
                            <div
                                @click="form.role = 'incubator'"
                                class="cursor-pointer rounded-xl p-4 text-center transition-all select-none"
                                :class="form.role === 'incubator'
                                    ? 'border-2 border-[#00C896] bg-emerald-light'
                                    : 'border-2 border-gray-200 bg-white hover:border-gray-300'"
                            >
                                <div class="text-3xl mb-2">🏢</div>
                                <div class="font-semibold text-navy text-sm">I represent an Incubator</div>
                                <div class="text-xs text-gray-500 mt-1">Launch programs, mentor startups</div>
                            </div>
                            <!-- Startup card -->
                            <div
                                @click="form.role = 'startup'"
                                class="cursor-pointer rounded-xl p-4 text-center transition-all select-none"
                                :class="form.role === 'startup'
                                    ? 'border-2 border-[#00C896] bg-emerald-light'
                                    : 'border-2 border-gray-200 bg-white hover:border-gray-300'"
                            >
                                <div class="text-3xl mb-2">🚀</div>
                                <div class="font-semibold text-navy text-sm">I'm building a Startup</div>
                                <div class="text-xs text-gray-500 mt-1">Find support, apply to programs</div>
                            </div>
                        </div>
                        <p v-if="form.errors.role" class="text-red-500 text-xs mt-1">{{ form.errors.role }}</p>
                    </div>

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Jane Doe"
                            class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896]"
                            :class="form.errors.name ? 'border-red-500' : 'border-gray-300'"
                        />
                        <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            required
                            autocomplete="username"
                            placeholder="you@example.com"
                            class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896]"
                            :class="form.errors.email ? 'border-red-500' : 'border-gray-300'"
                        />
                        <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input
                            v-model="form.password"
                            type="password"
                            required
                            autocomplete="new-password"
                            class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896]"
                            :class="form.errors.password ? 'border-red-500' : 'border-gray-300'"
                        />
                        <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            required
                            autocomplete="new-password"
                            class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896]"
                            :class="form.errors.password_confirmation ? 'border-red-500' : 'border-gray-300'"
                        />
                        <p v-if="form.errors.password_confirmation" class="text-red-500 text-xs mt-1">{{ form.errors.password_confirmation }}</p>
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full bg-navy text-white rounded-lg py-2.5 font-semibold hover:bg-navy-light transition disabled:opacity-60"
                    >
                        {{ form.processing ? 'Creating account…' : 'Create Account' }}
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-gray-500">
                    Already have an account?
                    <Link href="/login" class="text-[#00C896] font-semibold hover:underline">Sign in</Link>
                </p>
            </div>
        </div>
    </div>
</template>
