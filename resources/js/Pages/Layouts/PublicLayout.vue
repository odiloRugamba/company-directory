<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth?.user);
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <nav class="bg-navy text-white sticky top-0 z-40 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <Link href="/" class="font-display text-2xl font-bold text-white hover:text-[#00C896] transition">SANIA</Link>

                <div class="hidden md:flex items-center gap-6">
                    <Link href="/directory" class="text-gray-300 hover:text-white text-sm font-medium transition">Directory</Link>
                </div>

                <div class="flex items-center gap-3">
                    <template v-if="user">
                        <Link href="/dashboard" class="text-gray-300 hover:text-white text-sm">Dashboard</Link>
                    </template>
                    <template v-else>
                        <Link :href="route('login')" class="text-gray-300 hover:text-white text-sm font-medium">Login</Link>
                        <Link :href="route('register')"
                            class="bg-[#00C896] text-white px-4 py-1.5 rounded-lg text-sm font-semibold hover:bg-emerald-600 transition">
                            Register
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <!-- Main content -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-navy text-gray-400 py-8 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p class="font-display text-lg font-bold text-white mb-2">SANIA</p>
                <p class="text-sm">Connecting Africa's startup ecosystem. © {{ new Date().getFullYear() }}</p>
            </div>
        </footer>
    </div>
</template>
