<script setup>
import { computed } from 'vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/Pages/Layouts/AppLayout.vue'
import Avatar from '@/Components/UI/Avatar.vue'

const props = defineProps({
    incubator: {
        type: Object,
        required: true,
    },
})

const form = useForm({
    incubator_id: props.incubator.id,
    motivation: '',
    pitch_deck_url: '',
    eligibility_confirmed: false,
})

const motivationLength = computed(() => form.motivation.length)
const motivationMin = 100

function submit() {
    if (motivationLength.value < motivationMin) {
        form.errors.motivation = `Motivation must be at least ${motivationMin} characters.`
        return
    }
    form.post('/dashboard/apply', {
        onSuccess: () => {
            // redirect handled server-side to /dashboard/applications with flash
        },
    })
}
</script>

<template>
    <Head title="Apply to Incubator" />

    <AppLayout title="Apply to Incubator">
        <div class="max-w-5xl mx-auto">
            <div class="flex flex-col lg:flex-row gap-8 items-start">

                <!-- LEFT: Incubator summary card (sticky) -->
                <div class="w-full lg:w-1/3 lg:sticky lg:top-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <!-- Logo + org name -->
                        <div class="flex items-center gap-4 mb-5">
                            <Avatar
                                :src="incubator.logo_url ?? null"
                                :name="incubator.organization_name ?? incubator.user?.name ?? 'Incubator'"
                                size="lg"
                            />
                            <div class="flex-1 min-w-0">
                                <h2 class="text-base font-bold text-navy leading-tight truncate">
                                    {{ incubator.organization_name ?? incubator.user?.name ?? 'Incubator' }}
                                </h2>
                                <p v-if="incubator.country" class="text-sm text-gray-500 mt-0.5 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                    {{ incubator.country }}
                                </p>
                            </div>
                        </div>

                        <!-- Focus sectors -->
                        <div v-if="incubator.focus_sectors && incubator.focus_sectors.length" class="mb-5">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Focus sectors</p>
                            <div class="flex flex-wrap gap-1.5">
                                <span
                                    v-for="sector in incubator.focus_sectors"
                                    :key="sector"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700"
                                >
                                    {{ sector }}
                                </span>
                            </div>
                        </div>

                        <!-- Capacity -->
                        <div v-if="incubator.capacity" class="flex items-center gap-2 text-sm text-gray-600 bg-gray-50 rounded-lg px-3 py-2.5">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                            <span>Up to <span class="font-semibold text-gray-800">{{ incubator.capacity }}</span> startups</span>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: Application form -->
                <div class="w-full lg:w-2/3">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                        <h1 class="text-xl font-bold text-navy mb-1">Submit your application</h1>
                        <p class="text-sm text-gray-500 mb-8">
                            Apply to join
                            <span class="font-medium text-gray-700">{{ incubator.organization_name ?? incubator.user?.name ?? 'this incubator' }}</span>.
                            Make sure to provide a compelling motivation.
                        </p>

                        <form @submit.prevent="submit" novalidate>

                            <!-- Motivation -->
                            <div class="mb-6">
                                <label for="motivation" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Motivation
                                    <span class="text-red-500 ml-0.5">*</span>
                                </label>
                                <div class="relative">
                                    <textarea
                                        id="motivation"
                                        v-model="form.motivation"
                                        rows="7"
                                        placeholder="Tell us about your startup and why you're applying..."
                                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent resize-y transition pb-6"
                                        :class="{ 'border-red-300 focus:ring-red-400': form.errors.motivation }"
                                    />
                                    <span
                                        class="absolute bottom-2 right-3 text-sm select-none pointer-events-none"
                                        :class="motivationLength >= motivationMin ? 'text-gray-400' : 'text-amber-500'"
                                    >
                                        {{ motivationLength }}&nbsp;/&nbsp;{{ motivationMin }} min
                                    </span>
                                </div>
                                <p v-if="form.errors.motivation" class="mt-1.5 text-xs text-red-500">
                                    {{ form.errors.motivation }}
                                </p>
                            </div>

                            <!-- Pitch deck URL -->
                            <div class="mb-6">
                                <label for="pitch_deck_url" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Pitch deck URL
                                    <span class="text-xs font-normal text-gray-400 ml-1">(optional)</span>
                                </label>
                                <input
                                    id="pitch_deck_url"
                                    v-model="form.pitch_deck_url"
                                    type="url"
                                    placeholder="https://docs.google.com/presentation/..."
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition"
                                    :class="{ 'border-red-300 focus:ring-red-400': form.errors.pitch_deck_url }"
                                />
                                <p v-if="form.errors.pitch_deck_url" class="mt-1.5 text-xs text-red-500">
                                    {{ form.errors.pitch_deck_url }}
                                </p>
                            </div>

                            <!-- Eligibility checkbox -->
                            <div class="mb-8">
                                <label class="flex items-start gap-3 cursor-pointer group">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <input
                                            id="eligibility_confirmed"
                                            v-model="form.eligibility_confirmed"
                                            type="checkbox"
                                            class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                                            :class="{ 'border-red-400': form.errors.eligibility_confirmed }"
                                        />
                                    </div>
                                    <span class="text-sm text-gray-700 group-hover:text-gray-900 transition-colors leading-snug">
                                        I confirm this startup meets the eligibility criteria for this program
                                    </span>
                                </label>
                                <p v-if="form.errors.eligibility_confirmed" class="mt-1.5 text-xs text-red-500 pl-7">
                                    {{ form.errors.eligibility_confirmed }}
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between gap-4">
                                <Link
                                    href="/dashboard/applications"
                                    class="text-sm text-gray-500 hover:text-gray-700 transition-colors"
                                >
                                    Cancel
                                </Link>

                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed transition-colors"
                                >
                                    <!-- Loading spinner -->
                                    <svg
                                        v-if="form.processing"
                                        class="w-4 h-4 animate-spin text-white"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                    </svg>
                                    {{ form.processing ? 'Submitting...' : 'Submit Application' }}
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>
