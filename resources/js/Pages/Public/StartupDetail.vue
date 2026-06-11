<template>
    <PublicLayout>
        <Head :title="startup.company_name" />

        <!-- HEADER BANNER -->
        <section class="bg-white border-b border-slate-200">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                    <!-- Logo avatar -->
                    <Avatar
                        :src="startup.logo_url ?? null"
                        :name="startup.company_name"
                        size="lg"
                    />

                    <!-- Company meta -->
                    <div class="flex-1 min-w-0">
                        <h1
                            class="text-3xl font-bold text-slate-900 truncate"
                            style="font-family: 'Syne', sans-serif;"
                        >
                            {{ startup.company_name }}
                        </h1>

                        <p class="mt-1 text-sm text-slate-500">
                            {{ startup.country }}<template v-if="startup.city">, {{ startup.city }}</template>
                        </p>

                        <div class="mt-3 flex flex-wrap items-center gap-3">
                            <Badge :status="startup.stage" />

                            <a
                                v-if="startup.website"
                                :href="startup.website"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1 text-sm text-emerald-600 hover:text-emerald-800 font-medium transition-colors"
                            >
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                                {{ websiteHostname }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- BODY -->
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- LEFT COLUMN -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Tagline -->
                    <p
                        v-if="startup.tagline"
                        class="text-lg text-slate-600 italic"
                    >
                        "{{ startup.tagline }}"
                    </p>

                    <!-- Description -->
                    <div v-if="startup.description">
                        <h2 class="text-base font-semibold text-slate-700 uppercase tracking-wide mb-3">About</h2>
                        <p class="text-slate-600 leading-relaxed whitespace-pre-line">{{ startup.description }}</p>
                    </div>

                    <!-- Details row -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div v-if="startup.sector">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Sector</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                {{ startup.sector }}
                            </span>
                        </div>

                        <div v-if="startup.team_size">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Team Size</p>
                            <p class="text-sm text-slate-700 font-medium">{{ startup.team_size }} people</p>
                        </div>

                        <div v-if="startup.founded_year">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Founded</p>
                            <p class="text-sm text-slate-700 font-medium">{{ startup.founded_year }}</p>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Info card -->
                <div class="lg:col-span-1">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-6 space-y-5">
                        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Summary</h3>

                        <dl class="space-y-4">
                            <div v-if="startup.stage">
                                <dt class="text-xs text-slate-400 font-medium uppercase tracking-wide">Stage</dt>
                                <dd class="mt-1">
                                    <Badge :status="startup.stage" />
                                </dd>
                            </div>

                            <div v-if="startup.country">
                                <dt class="text-xs text-slate-400 font-medium uppercase tracking-wide">Location</dt>
                                <dd class="mt-1 text-sm text-slate-700">
                                    {{ startup.country }}<template v-if="startup.city">, {{ startup.city }}</template>
                                </dd>
                            </div>

                            <div v-if="startup.sector">
                                <dt class="text-xs text-slate-400 font-medium uppercase tracking-wide">Sector</dt>
                                <dd class="mt-1 text-sm text-slate-700">{{ startup.sector }}</dd>
                            </div>

                            <div v-if="startup.team_size">
                                <dt class="text-xs text-slate-400 font-medium uppercase tracking-wide">Team Size</dt>
                                <dd class="mt-1 text-sm text-slate-700">{{ startup.team_size }} people</dd>
                            </div>

                            <div v-if="startup.founded_year">
                                <dt class="text-xs text-slate-400 font-medium uppercase tracking-wide">Founded</dt>
                                <dd class="mt-1 text-sm text-slate-700">{{ startup.founded_year }}</dd>
                            </div>

                            <div v-if="startup.website">
                                <dt class="text-xs text-slate-400 font-medium uppercase tracking-wide">Website</dt>
                                <dd class="mt-1">
                                    <a
                                        :href="startup.website"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-sm text-emerald-600 hover:text-emerald-800 break-all transition-colors"
                                    >
                                        {{ websiteHostname }}
                                    </a>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

            </div>
        </section>
    </PublicLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Pages/Layouts/PublicLayout.vue';
import Avatar from '@/Components/UI/Avatar.vue';
import Badge from '@/Components/UI/Badge.vue';

const props = defineProps({
    startup: {
        type: Object,
        required: true,
    },
});

const websiteHostname = computed(() => {
    if (!props.startup.website) return '';
    try {
        return new URL(props.startup.website).hostname.replace(/^www\./, '');
    } catch {
        return props.startup.website;
    }
});
</script>
