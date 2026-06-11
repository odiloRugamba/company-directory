<template>
    <PublicLayout>
        <Head title="Africa's Startup Ecosystem, Connected" />

        <!-- HERO -->
        <section class="bg-navy-900 w-full py-24 px-6" style="background-color: #0f2044;">
            <div class="max-w-5xl mx-auto text-center">
                <h1 class="font-display text-5xl font-bold text-white leading-tight mb-6">
                    Africa's Startup Ecosystem, Connected
                </h1>
                <p class="text-lg text-blue-100 max-w-2xl mx-auto mb-10">
                    SANIA connects Africa's most innovative startups with world-class incubators and accelerators.
                </p>
                <div class="flex flex-wrap gap-4 justify-center">
                    <Link
                        href="/directory"
                        class="inline-flex items-center px-6 py-3 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-semibold transition-colors duration-200"
                    >
                        Explore the Directory
                    </Link>
                    <Link
                        href="/register"
                        class="inline-flex items-center px-6 py-3 rounded-lg border-2 border-white text-white hover:bg-white hover:text-navy-900 font-semibold transition-colors duration-200"
                        style="--tw-hover-text-color: #0f2044;"
                    >
                        Join the Ecosystem
                    </Link>
                </div>
            </div>
        </section>

        <!-- STATS BAR -->
        <section class="bg-slate-50 border-b border-slate-200">
            <div class="max-w-5xl mx-auto px-6 py-12 grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
                <div>
                    <p class="text-4xl font-bold text-slate-800">{{ totalIncubators.toLocaleString() }}</p>
                    <p class="text-sm text-slate-500 mt-1 uppercase tracking-wide">Incubators &amp; Accelerators</p>
                </div>
                <div>
                    <p class="text-4xl font-bold text-slate-800">{{ totalStartups.toLocaleString() }}</p>
                    <p class="text-sm text-slate-500 mt-1 uppercase tracking-wide">Startups</p>
                </div>
                <div>
                    <p class="text-4xl font-bold text-slate-800">{{ totalCountries.toLocaleString() }}</p>
                    <p class="text-sm text-slate-500 mt-1 uppercase tracking-wide">Countries</p>
                </div>
            </div>
        </section>

        <!-- FEATURED INCUBATORS -->
        <section class="max-w-6xl mx-auto px-6 py-16">
            <h2 class="text-3xl font-bold text-slate-800 mb-8">Featured Incubators</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="incubator in featuredIncubators"
                    :key="incubator.slug"
                    class="rounded-xl border border-slate-200 bg-white shadow-sm p-6 flex flex-col"
                >
                    <!-- Avatar + name row -->
                    <div class="flex items-center gap-4 mb-4">
                        <img
                            v-if="incubator.logo_url"
                            :src="incubator.logo_url"
                            :alt="incubator.organization_name"
                            class="w-12 h-12 rounded-full object-cover border border-slate-200 shrink-0"
                        />
                        <div
                            v-else
                            class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg font-bold shrink-0"
                        >
                            {{ incubator.organization_name.charAt(0) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-800 truncate">{{ incubator.organization_name }}</p>
                            <p class="text-sm text-slate-500">
                                {{ countryFlag(incubator.country) }} {{ incubator.country }}
                            </p>
                        </div>
                    </div>

                    <!-- Sector tags -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span
                            v-for="sector in (incubator.focus_sectors || []).slice(0, 2)"
                            :key="sector"
                            class="px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200"
                        >
                            {{ sector }}
                        </span>
                    </div>

                    <!-- Description (2-line clamp) -->
                    <p class="text-sm text-slate-600 line-clamp-2 flex-1 mb-6">
                        {{ incubator.description }}
                    </p>

                    <!-- CTA -->
                    <Link
                        :href="`/directory/${incubator.slug}`"
                        class="mt-auto inline-flex items-center text-sm font-semibold text-emerald-600 hover:text-emerald-800 transition-colors"
                    >
                        View Profile
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                </div>
            </div>
        </section>

        <!-- SECTOR PILLS -->
        <section class="bg-slate-50 border-t border-slate-200 py-10">
            <div class="max-w-6xl mx-auto px-6">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-4">Browse by Sector</h3>
                <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                    <Link
                        v-for="sector in sectors"
                        :key="sector.slug"
                        :href="`/directory?sector=${sector.slug}`"
                        class="flex-none inline-flex items-center gap-2 px-4 py-2 rounded-full border border-slate-300 bg-white text-sm text-slate-700 hover:border-emerald-400 hover:text-emerald-700 hover:bg-emerald-50 transition-colors whitespace-nowrap"
                    >
                        <span v-if="sector.icon">{{ sector.icon }}</span>
                        {{ sector.name }}
                    </Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<script setup>
import { Link, Head } from '@inertiajs/vue3';
import PublicLayout from '@/Pages/Layouts/PublicLayout.vue';

defineProps({
    totalIncubators: {
        type: Number,
        default: 0,
    },
    totalStartups: {
        type: Number,
        default: 0,
    },
    totalCountries: {
        type: Number,
        default: 0,
    },
    sectors: {
        type: Array,
        default: () => [],
    },
    featuredIncubators: {
        type: Array,
        default: () => [],
    },
});

const COUNTRY_FLAGS = {
    RW: '🇷🇼',
    KE: '🇰🇪',
    NG: '🇳🇬',
    GH: '🇬🇭',
    SN: '🇸🇳',
    EG: '🇪🇬',
    ZA: '🇿🇦',
    Rwanda: '🇷🇼',
    Kenya: '🇰🇪',
    Nigeria: '🇳🇬',
    Ghana: '🇬🇭',
    Senegal: '🇸🇳',
    Egypt: '🇪🇬',
    'South Africa': '🇿🇦',
};

function countryFlag(country) {
    if (!country) return '';
    return COUNTRY_FLAGS[country] || '';
}
</script>

<style scoped>
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
</style>
