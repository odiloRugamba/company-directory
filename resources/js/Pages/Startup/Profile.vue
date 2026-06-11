<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Pages/Layouts/AppLayout.vue';
import SelectInput from '@/Components/Forms/SelectInput.vue';
import FileUpload from '@/Components/Forms/FileUpload.vue';

const props = defineProps({
    profile: Object,
    sectors: Array,
});

const stages = [
    { value: 'idea', label: 'Idea', desc: 'Pre-product, validating the problem' },
    { value: 'mvp', label: 'MVP', desc: 'Built something, testing with first users' },
    { value: 'early_traction', label: 'Early Traction', desc: 'Paying customers, proving the model' },
    { value: 'growth', label: 'Growth', desc: 'Scaling what works' },
    { value: 'scale', label: 'Scale', desc: 'Expanding markets and team' },
];

const form = useForm({
    company_name: props.profile?.company_name ?? '',
    tagline: props.profile?.tagline ?? '',
    description: props.profile?.description ?? '',
    country: props.profile?.country ?? '',
    city: props.profile?.city ?? '',
    website: props.profile?.website ?? '',
    founded_year: props.profile?.founded_year ?? '',
    team_size: props.profile?.team_size ?? '',
    sector: props.profile?.sector ?? '',
    stage: props.profile?.stage ?? '',
    logo: null,
});

function submit() {
    form.put(route('dashboard.profile'), {
        forceFormData: true,
    });
}
</script>

<template>
    <AppLayout>
        <Head title="Startup Profile" />

        <div class="max-w-2xl space-y-6">
            <div>
                <h1 class="text-2xl font-bold font-display text-navy">Startup Profile</h1>
                <p class="text-sm text-gray-500 mt-1">Your public profile in the SANIA directory</p>
            </div>

            <form
                class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-6"
                @submit.prevent="submit"
            >
                <!-- Logo -->
                <FileUpload
                    v-model="form.logo"
                    label="Logo"
                    accept="image/*"
                />
                <p v-if="form.errors.logo" class="mt-1 text-xs text-red-600">{{ form.errors.logo }}</p>

                <!-- Company Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Company Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model="form.company_name"
                        type="text"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy/30 focus:border-navy"
                        :class="{ 'border-red-500': form.errors.company_name }"
                        required
                    />
                    <p v-if="form.errors.company_name" class="mt-1 text-xs text-red-600">{{ form.errors.company_name }}</p>
                </div>

                <!-- Tagline -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                    <input
                        v-model="form.tagline"
                        type="text"
                        placeholder="One-line description of what you do"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy/30 focus:border-navy"
                        :class="{ 'border-red-500': form.errors.tagline }"
                    />
                    <p v-if="form.errors.tagline" class="mt-1 text-xs text-red-600">{{ form.errors.tagline }}</p>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea
                        v-model="form.description"
                        rows="4"
                        placeholder="Tell incubators about your startup, your mission, and the problem you're solving..."
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy/30 focus:border-navy"
                        :class="{ 'border-red-500': form.errors.description }"
                    />
                    <p v-if="form.errors.description" class="mt-1 text-xs text-red-600">{{ form.errors.description }}</p>
                </div>

                <!-- Country & City -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <input
                            v-model="form.country"
                            type="text"
                            placeholder="Rwanda"
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy/30 focus:border-navy"
                            :class="{ 'border-red-500': form.errors.country }"
                        />
                        <p v-if="form.errors.country" class="mt-1 text-xs text-red-600">{{ form.errors.country }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input
                            v-model="form.city"
                            type="text"
                            placeholder="Kigali"
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy/30 focus:border-navy"
                            :class="{ 'border-red-500': form.errors.city }"
                        />
                        <p v-if="form.errors.city" class="mt-1 text-xs text-red-600">{{ form.errors.city }}</p>
                    </div>
                </div>

                <!-- Website -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                    <input
                        v-model="form.website"
                        type="url"
                        placeholder="https://example.com"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy/30 focus:border-navy"
                        :class="{ 'border-red-500': form.errors.website }"
                    />
                    <p v-if="form.errors.website" class="mt-1 text-xs text-red-600">{{ form.errors.website }}</p>
                </div>

                <!-- Founded Year & Team Size -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Founded Year</label>
                        <input
                            v-model="form.founded_year"
                            type="number"
                            placeholder="2023"
                            min="1900"
                            :max="new Date().getFullYear()"
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy/30 focus:border-navy"
                            :class="{ 'border-red-500': form.errors.founded_year }"
                        />
                        <p v-if="form.errors.founded_year" class="mt-1 text-xs text-red-600">{{ form.errors.founded_year }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Team Size <span class="text-red-500">*</span></label>
                        <input
                            v-model="form.team_size"
                            type="number"
                            placeholder="5"
                            min="1"
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-navy/30 focus:border-navy"
                            :class="{ 'border-red-500': form.errors.team_size }"
                            required
                        />
                        <p v-if="form.errors.team_size" class="mt-1 text-xs text-red-600">{{ form.errors.team_size }}</p>
                    </div>
                </div>

                <!-- Sector -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sector</label>
                    <SelectInput
                        v-model="form.sector"
                        :options="sectors.map(s => ({ value: s.slug, label: s.name }))"
                        placeholder="Select a sector"
                        :error="form.errors.sector"
                    />
                </div>

                <!-- Stage Selector -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stage</label>
                    <div class="flex flex-wrap gap-3">
                        <button
                            v-for="stage in stages"
                            :key="stage.value"
                            type="button"
                            class="flex-1 min-w-[140px] rounded-lg px-4 py-3 text-left transition-colors cursor-pointer"
                            :class="form.stage === stage.value
                                ? 'border-2 border-[#00C896] bg-emerald-50'
                                : 'border border-gray-200 bg-white hover:border-gray-300'"
                            @click="form.stage = stage.value"
                        >
                            <span class="block text-sm font-semibold text-gray-900">{{ stage.label }}</span>
                            <span class="block text-xs text-gray-500 mt-0.5">{{ stage.desc }}</span>
                        </button>
                    </div>
                    <p v-if="form.errors.stage" class="mt-1 text-xs text-red-600">{{ form.errors.stage }}</p>
                </div>

                <!-- Submit -->
                <div class="flex justify-end pt-2">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="bg-navy text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-navy-light disabled:opacity-50 transition-colors"
                    >
                        {{ form.processing ? 'Saving...' : 'Save Profile' }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
