<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Pages/Layouts/AppLayout.vue'
import Badge from '@/Components/UI/Badge.vue'
import Avatar from '@/Components/UI/Avatar.vue'
import SlideOver from '@/Components/UI/SlideOver.vue'

const props = defineProps({
    columns: {
        type: Object,
        required: true,
        // { pending: [], under_review: [], decided: [] }
    },
})

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------
function daysAgo(dateStr) {
    if (!dateStr) return ''
    const diff = Date.now() - new Date(dateStr).getTime()
    const days = Math.floor(diff / 86_400_000)
    if (days === 0) return 'today'
    if (days === 1) return '1 day ago'
    return `${days} days ago`
}

function getCookie(name) {
    const match = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '=([^;]*)'))
    return match ? decodeURIComponent(match[1]) : null
}

// ---------------------------------------------------------------------------
// Kanban column definitions
// ---------------------------------------------------------------------------
const kanbanColumns = [
    {
        key: 'pending',
        label: 'Pending',
        dotClass: 'bg-yellow-400',
        emptyText: 'No pending applications.',
    },
    {
        key: 'under_review',
        label: 'Under Review',
        dotClass: 'bg-blue-500',
        emptyText: 'No applications under review.',
    },
    {
        key: 'decided',
        label: 'Decided',
        dotClass: 'bg-gray-400',
        emptyText: 'No decided applications yet.',
    },
]

// ---------------------------------------------------------------------------
// SlideOver state
// ---------------------------------------------------------------------------
const slideOpen = ref(false)
const selected = ref(null)
const reviewerNotes = ref('')
const saving = ref(false)
const actionLoading = ref(false)
const saveError = ref(null)

function openApp(app) {
    selected.value = app
    reviewerNotes.value = app.reviewer_notes ?? ''
    saveError.value = null
    slideOpen.value = true
}

function closeSlide() {
    slideOpen.value = false
    selected.value = null
}

// ---------------------------------------------------------------------------
// Derived startup profile fields
// ---------------------------------------------------------------------------
const profile = computed(() => selected.value?.startup?.startupProfile ?? {})
const companyName = computed(() =>
    profile.value.company_name ?? selected.value?.startup?.name ?? 'Unknown',
)

// ---------------------------------------------------------------------------
// Auto-save reviewer notes on blur
// ---------------------------------------------------------------------------
async function saveNotes() {
    if (!selected.value) return
    saving.value = true
    saveError.value = null
    try {
        await window.axios.patch(
            `/api/v1/applications/${selected.value.id}/status`,
            { reviewer_notes: reviewerNotes.value },
            {
                headers: {
                    'X-XSRF-TOKEN': getCookie('XSRF-TOKEN'),
                },
            },
        )
    } catch (e) {
        saveError.value = 'Failed to save notes.'
    } finally {
        saving.value = false
    }
}

// ---------------------------------------------------------------------------
// Status actions
// ---------------------------------------------------------------------------
async function changeStatus(status) {
    if (!selected.value) return
    actionLoading.value = true
    saveError.value = null
    try {
        await window.axios.patch(
            `/api/v1/applications/${selected.value.id}/status`,
            { status },
            {
                headers: {
                    'X-XSRF-TOKEN': getCookie('XSRF-TOKEN'),
                },
            },
        )
        closeSlide()
        router.reload()
    } catch (e) {
        saveError.value = 'Action failed. Please try again.'
    } finally {
        actionLoading.value = false
    }
}
</script>

<template>
    <AppLayout title="Applications">

        <!-- Kanban board — horizontal scroll on all viewports, never stacks -->
        <div class="overflow-x-auto pb-4">
            <div class="flex gap-4 min-w-[720px]">

                <div
                    v-for="col in kanbanColumns"
                    :key="col.key"
                    class="flex flex-col flex-1 min-w-[220px]"
                >
                    <!-- Column header -->
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" :class="col.dotClass" />
                        <span class="text-sm font-semibold text-gray-700">{{ col.label }}</span>
                        <span class="ml-auto text-xs text-gray-400 font-medium">
                            {{ (columns[col.key] ?? []).length }}
                        </span>
                    </div>

                    <!-- Cards -->
                    <div class="flex flex-col gap-3">
                        <template v-if="(columns[col.key] ?? []).length > 0">
                            <div
                                v-for="app in columns[col.key]"
                                :key="app.id"
                                class="bg-white rounded-lg shadow-sm p-4 cursor-pointer hover:shadow-md transition-shadow"
                                @click="openApp(app)"
                            >
                                <!-- Top row: avatar + company name -->
                                <div class="flex items-center gap-3 mb-3">
                                    <Avatar
                                        :src="app.startup?.startupProfile?.logo_url ?? null"
                                        :name="app.startup?.startupProfile?.company_name ?? app.startup?.name ?? 'Unknown'"
                                        size="sm"
                                    />
                                    <span class="text-sm font-semibold text-navy leading-tight truncate">
                                        {{ app.startup?.startupProfile?.company_name ?? app.startup?.name ?? '—' }}
                                    </span>
                                </div>

                                <!-- Tags row -->
                                <div class="flex flex-wrap gap-1.5 mb-3">
                                    <span
                                        v-if="app.startup?.startupProfile?.sector"
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700"
                                    >
                                        {{ app.startup.startupProfile.sector }}
                                    </span>
                                    <span
                                        v-if="app.startup?.startupProfile?.stage"
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-50 text-orange-700 capitalize"
                                    >
                                        {{ app.startup.startupProfile.stage.replace(/_/g, ' ') }}
                                    </span>
                                </div>

                                <!-- Footer: time ago + status badge -->
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-400">{{ daysAgo(app.created_at) }}</span>
                                    <Badge :status="app.status" />
                                </div>
                            </div>
                        </template>

                        <!-- Empty state -->
                        <div
                            v-else
                            class="bg-white rounded-lg border border-dashed border-gray-200 p-6 text-center text-xs text-gray-400"
                        >
                            {{ col.emptyText }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- SlideOver -->
        <SlideOver
            :open="slideOpen"
            :title="selected ? (selected.startup?.startupProfile?.company_name ?? selected.startup?.name ?? 'Application') : ''"
            @close="closeSlide"
        >
            <template v-if="selected">
                <!-- Startup identity -->
                <div class="flex items-start gap-4 mb-6">
                    <Avatar
                        :src="profile.logo_url ?? null"
                        :name="companyName"
                        size="lg"
                    />
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-bold text-navy leading-tight">{{ companyName }}</h3>
                        <p v-if="profile.tagline" class="text-sm text-gray-500 mt-0.5 leading-snug">{{ profile.tagline }}</p>
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            <span
                                v-if="profile.sector"
                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700"
                            >
                                {{ profile.sector }}
                            </span>
                            <span
                                v-if="profile.stage"
                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-50 text-orange-700 capitalize"
                            >
                                {{ profile.stage.replace(/_/g, ' ') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Meta info -->
                <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm mb-6">
                    <div v-if="profile.team_size">
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Team size</dt>
                        <dd class="text-gray-700 mt-0.5">{{ profile.team_size }}</dd>
                    </div>
                    <div v-if="profile.country">
                        <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Country</dt>
                        <dd class="text-gray-700 mt-0.5">{{ profile.country }}</dd>
                    </div>
                </dl>

                <hr class="border-gray-100 mb-6" />

                <!-- Motivation -->
                <div class="mb-6">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Motivation</p>
                    <div class="bg-gray-50 rounded-lg p-3 text-sm text-gray-700 whitespace-pre-wrap max-h-48 overflow-y-auto leading-relaxed">
                        {{ selected.motivation || '—' }}
                    </div>
                </div>

                <!-- Pitch deck -->
                <div v-if="selected.pitch_deck_url" class="mb-6">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Pitch deck</p>
                    <a
                        :href="selected.pitch_deck_url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1.5 text-sm text-indigo-600 hover:text-indigo-800 underline underline-offset-2"
                    >
                        <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        View pitch deck
                    </a>
                </div>

                <!-- Reviewer notes -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Reviewer notes</p>
                        <span v-if="saving" class="text-xs text-gray-400 italic">Saving…</span>
                    </div>
                    <textarea
                        v-model="reviewerNotes"
                        rows="4"
                        placeholder="Add your notes here…"
                        class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent resize-y transition"
                        @blur="saveNotes"
                    />
                    <p v-if="saveError" class="text-xs text-red-500 mt-1">{{ saveError }}</p>
                </div>

                <!-- Action buttons -->
                <div class="flex flex-col gap-2">
                    <!-- pending -->
                    <template v-if="selected.status === 'pending'">
                        <button
                            :disabled="actionLoading"
                            class="w-full rounded-lg px-4 py-2.5 text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-60 transition-colors"
                            @click="changeStatus('under_review')"
                        >
                            Move to Under Review
                        </button>
                    </template>

                    <!-- under_review -->
                    <template v-else-if="selected.status === 'under_review'">
                        <button
                            :disabled="actionLoading"
                            class="w-full rounded-lg px-4 py-2.5 text-sm font-semibold bg-emerald-600 text-white hover:bg-emerald-700 disabled:opacity-60 transition-colors"
                            @click="changeStatus('accepted')"
                        >
                            Accept
                        </button>
                        <button
                            :disabled="actionLoading"
                            class="w-full rounded-lg px-4 py-2.5 text-sm font-semibold bg-red-600 text-white hover:bg-red-700 disabled:opacity-60 transition-colors"
                            @click="changeStatus('rejected')"
                        >
                            Reject
                        </button>
                    </template>

                    <!-- accepted / rejected -->
                    <template v-else>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500">Current status:</span>
                            <Badge :status="selected.status" />
                        </div>
                    </template>
                </div>
            </template>
        </SlideOver>

    </AppLayout>
</template>
