<script setup>
import { ref, reactive, computed, watch, onMounted, onBeforeUnmount } from 'vue'
import { Link, router, usePage, Head } from '@inertiajs/vue3'
import PublicLayout from '@/Pages/Layouts/PublicLayout.vue'
import Badge from '@/Components/UI/Badge.vue'
import Avatar from '@/Components/UI/Avatar.vue'

const props = defineProps({
    items: {
        type: Object,
        required: true,
    },
    tab: {
        type: String,
        default: 'incubators',
    },
    sectors: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
})

const sidebarOpen = ref(false)

const localFilters = reactive({
    search: props.filters.search ?? '',
    country: props.filters.country ?? '',
    sectors: props.filters.sectors ?? [],
    stages: props.filters.stages ?? [],
    verified_only: props.filters.verified_only ?? false,
})

const stages = ['idea', 'mvp', 'early_traction', 'growth', 'scale']

let searchDebounceTimer = null

function applyFilters(overrides = {}) {
    const payload = {
        tab: props.tab,
        search: localFilters.search || undefined,
        country: localFilters.country || undefined,
        sectors: localFilters.sectors.length ? localFilters.sectors : undefined,
        stages: localFilters.stages.length ? localFilters.stages : undefined,
        verified_only: localFilters.verified_only || undefined,
        ...overrides,
    }
    Object.keys(payload).forEach((k) => {
        if (payload[k] === undefined || payload[k] === false) delete payload[k]
    })
    router.get('/directory', payload, { preserveState: true, replace: true })
}

function onSearchInput() {
    clearTimeout(searchDebounceTimer)
    searchDebounceTimer = setTimeout(() => {
        applyFilters()
    }, 400)
}

function onCountryInput() {
    clearTimeout(searchDebounceTimer)
    searchDebounceTimer = setTimeout(() => {
        applyFilters()
    }, 400)
}

function onSectorChange() {
    applyFilters()
}

function onStageChange() {
    applyFilters()
}

function onVerifiedToggle() {
    applyFilters()
}

function switchTab(newTab) {
    const payload = {
        tab: newTab,
        search: localFilters.search || undefined,
        country: localFilters.country || undefined,
        sectors: localFilters.sectors.length ? localFilters.sectors : undefined,
    }
    Object.keys(payload).forEach((k) => {
        if (payload[k] === undefined) delete payload[k]
    })
    router.get('/directory', payload, { preserveState: false, replace: false })
}

onBeforeUnmount(() => {
    clearTimeout(searchDebounceTimer)
})

const isIncubatorTab = computed(() => props.tab === 'incubators')
const isStartupTab = computed(() => props.tab === 'startups')

function stageLabel(stage) {
    const map = {
        idea: 'Idea',
        mvp: 'MVP',
        early_traction: 'Early Traction',
        growth: 'Growth',
        scale: 'Scale',
    }
    return map[stage] ?? stage
}

const stageBadgeStatus = {
    idea: 'pending',
    mvp: 'startup',
    early_traction: 'accepted',
    growth: 'incubator',
    scale: 'verified',
}
</script>

<template>
    <Head title="Directory" />
    <PublicLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Page header -->
            <div class="mb-6">
                <h1 class="text-3xl font-display font-bold text-gray-900">Directory</h1>
                <p class="text-gray-500 mt-1 text-sm">Browse incubators and startups across Africa's innovation ecosystem.</p>
            </div>

            <!-- Tab switcher -->
            <div class="flex gap-1 mb-6 border-b border-gray-200">
                <button
                    type="button"
                    class="px-5 py-2.5 text-sm font-semibold rounded-t-lg transition focus:outline-none"
                    :class="isIncubatorTab
                        ? 'bg-white border border-b-white border-gray-200 text-[#0F1F3D] -mb-px'
                        : 'text-gray-500 hover:text-gray-700'"
                    @click="switchTab('incubators')"
                >
                    Incubators
                </button>
                <button
                    type="button"
                    class="px-5 py-2.5 text-sm font-semibold rounded-t-lg transition focus:outline-none"
                    :class="isStartupTab
                        ? 'bg-white border border-b-white border-gray-200 text-[#0F1F3D] -mb-px'
                        : 'text-gray-500 hover:text-gray-700'"
                    @click="switchTab('startups')"
                >
                    Startups
                </button>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">

                <!-- Mobile sidebar toggle -->
                <div class="lg:hidden flex items-center justify-between mb-2">
                    <button
                        type="button"
                        class="flex items-center gap-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg px-3 py-2 bg-white hover:bg-gray-50 transition"
                        @click="sidebarOpen = !sidebarOpen"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 010 2H4a1 1 0 01-1-1zm3 5a1 1 0 011-1h10a1 1 0 010 2H7a1 1 0 01-1-1zm4 5a1 1 0 011-1h4a1 1 0 010 2h-4a1 1 0 01-1-1z" />
                        </svg>
                        Filters
                    </button>
                </div>

                <!-- Filter sidebar -->
                <aside
                    class="w-full lg:w-64 flex-shrink-0"
                    :class="{ 'hidden lg:block': !sidebarOpen }"
                >
                    <div class="bg-white border border-gray-200 rounded-xl p-5 space-y-6 sticky top-20">

                        <!-- Search -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Search</label>
                            <input
                                v-model="localFilters.search"
                                type="text"
                                placeholder="Name, keyword…"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#00C896] focus:border-transparent"
                                @input="onSearchInput"
                            />
                        </div>

                        <!-- Country -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Country</label>
                            <input
                                v-model="localFilters.country"
                                type="text"
                                placeholder="e.g. Rwanda"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#00C896] focus:border-transparent"
                                @input="onCountryInput"
                            />
                        </div>

                        <!-- Sector -->
                        <div v-if="sectors && sectors.length">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Sector</label>
                            <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                                <label
                                    v-for="sector in sectors"
                                    :key="sector"
                                    class="flex items-center gap-2 cursor-pointer group"
                                >
                                    <input
                                        type="checkbox"
                                        :value="sector"
                                        v-model="localFilters.sectors"
                                        class="w-4 h-4 rounded border-gray-300 text-[#00C896] focus:ring-[#00C896] cursor-pointer"
                                        @change="onSectorChange"
                                    />
                                    <span class="text-sm text-gray-700 group-hover:text-gray-900">{{ sector }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Stage (startups only) -->
                        <div v-if="isStartupTab">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Stage</label>
                            <div class="space-y-1.5">
                                <label
                                    v-for="stage in stages"
                                    :key="stage"
                                    class="flex items-center gap-2 cursor-pointer group"
                                >
                                    <input
                                        type="checkbox"
                                        :value="stage"
                                        v-model="localFilters.stages"
                                        class="w-4 h-4 rounded border-gray-300 text-[#00C896] focus:ring-[#00C896] cursor-pointer"
                                        @change="onStageChange"
                                    />
                                    <span class="text-sm text-gray-700 group-hover:text-gray-900">{{ stageLabel(stage) }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Verified Only (incubators only) -->
                        <div v-if="isIncubatorTab">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <button
                                    type="button"
                                    role="switch"
                                    :aria-checked="localFilters.verified_only"
                                    class="relative inline-flex h-5 w-9 flex-shrink-0 rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#00C896] focus:ring-offset-2"
                                    :class="localFilters.verified_only ? 'bg-[#00C896]' : 'bg-gray-200'"
                                    @click="localFilters.verified_only = !localFilters.verified_only; onVerifiedToggle()"
                                >
                                    <span
                                        class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                        :class="localFilters.verified_only ? 'translate-x-4' : 'translate-x-0'"
                                    />
                                </button>
                                <span class="text-sm font-medium text-gray-700">Verified Only</span>
                            </label>
                        </div>

                    </div>
                </aside>

                <!-- Main content -->
                <div class="flex-1 min-w-0">

                    <!-- Result count -->
                    <p class="text-sm text-gray-500 mb-4">
                        Showing <span class="font-semibold text-gray-700">{{ items.total ?? items.data?.length ?? 0 }}</span> results
                    </p>

                    <!-- Empty state -->
                    <div
                        v-if="!items.data || items.data.length === 0"
                        class="bg-white border border-gray-200 rounded-xl p-12 flex flex-col items-center justify-center text-center"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M16.65 16.65A7.5 7.5 0 1116.65 2a7.5 7.5 0 010 14.65z" />
                        </svg>
                        <p class="text-gray-500 text-sm">No results found. Try adjusting your filters.</p>
                    </div>

                    <!-- Incubator grid -->
                    <div
                        v-else-if="isIncubatorTab"
                        class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5"
                    >
                        <div
                            v-for="incubator in items.data"
                            :key="incubator.id"
                            class="bg-white border border-gray-200 rounded-xl p-5 flex flex-col gap-3 hover:shadow-md transition-shadow"
                        >
                            <!-- Header -->
                            <div class="flex items-start gap-3">
                                <Avatar
                                    :src="incubator.logo_url ?? null"
                                    :name="incubator.name"
                                    size="lg"
                                />
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="text-sm font-semibold text-gray-900 truncate">{{ incubator.name }}</h3>
                                        <Badge v-if="incubator.is_verified" status="verified" />
                                    </div>
                                    <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ incubator.country ?? '—' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Focus sectors -->
                            <div v-if="incubator.focus_sectors && incubator.focus_sectors.length" class="flex flex-wrap gap-1">
                                <span
                                    v-for="s in incubator.focus_sectors.slice(0, 3)"
                                    :key="s"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700"
                                >
                                    {{ s }}
                                </span>
                                <span
                                    v-if="incubator.focus_sectors.length > 3"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600"
                                >
                                    +{{ incubator.focus_sectors.length - 3 }}
                                </span>
                            </div>

                            <!-- Capacity -->
                            <p v-if="incubator.capacity" class="text-xs text-gray-500">
                                <span class="font-medium text-gray-700">{{ incubator.capacity }}</span> startups capacity
                            </p>

                            <!-- Description -->
                            <p class="text-xs text-gray-600 line-clamp-2 flex-1">
                                {{ incubator.description ?? 'No description provided.' }}
                            </p>

                            <!-- Action -->
                            <Link
                                :href="`/directory/incubators/${incubator.id}`"
                                class="mt-auto inline-flex items-center justify-center w-full rounded-lg border border-[#0F1F3D] text-[#0F1F3D] text-xs font-semibold py-2 hover:bg-[#0F1F3D] hover:text-white transition"
                            >
                                View Profile
                            </Link>
                        </div>
                    </div>

                    <!-- Startup grid -->
                    <div
                        v-else-if="isStartupTab"
                        class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5"
                    >
                        <div
                            v-for="startup in items.data"
                            :key="startup.id"
                            class="bg-white border border-gray-200 rounded-xl p-5 flex flex-col gap-3 hover:shadow-md transition-shadow"
                        >
                            <!-- Header -->
                            <div class="flex items-start gap-3">
                                <Avatar
                                    :src="startup.logo_url ?? null"
                                    :name="startup.name"
                                    size="lg"
                                />
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-sm font-semibold text-gray-900 truncate">{{ startup.name }}</h3>
                                    <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ startup.country ?? '—' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Sector + Stage row -->
                            <div class="flex items-center gap-2 flex-wrap">
                                <span
                                    v-if="startup.sector"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700"
                                >
                                    {{ startup.sector }}
                                </span>
                                <Badge
                                    v-if="startup.stage"
                                    :status="stageBadgeStatus[startup.stage] ?? 'pending'"
                                />
                            </div>

                            <!-- Team size -->
                            <p v-if="startup.team_size" class="text-xs text-gray-500">
                                <span class="font-medium text-gray-700">{{ startup.team_size }}</span> team members
                            </p>

                            <!-- Tagline -->
                            <p class="text-xs text-gray-600 line-clamp-2 flex-1">
                                {{ startup.tagline ?? 'No tagline provided.' }}
                            </p>

                            <!-- Action -->
                            <Link
                                :href="`/directory/startups/${startup.id}`"
                                class="mt-auto inline-flex items-center justify-center w-full rounded-lg border border-[#0F1F3D] text-[#0F1F3D] text-xs font-semibold py-2 hover:bg-[#0F1F3D] hover:text-white transition"
                            >
                                View Profile
                            </Link>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div
                        v-if="items.links && items.links.length > 3"
                        class="mt-8 flex items-center justify-center gap-1 flex-wrap"
                    >
                        <template v-for="link in items.links" :key="link.label">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                preserve-state
                                class="px-3 py-1.5 rounded-lg text-sm font-medium transition"
                                :class="link.active
                                    ? 'bg-[#0F1F3D] text-white'
                                    : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50'"
                                v-html="link.label"
                            />
                            <span
                                v-else
                                class="px-3 py-1.5 rounded-lg text-sm font-medium text-gray-300 border border-gray-200 bg-white cursor-not-allowed"
                                v-html="link.label"
                            />
                        </template>
                    </div>

                </div>
            </div>
        </div>
    </PublicLayout>
</template>
