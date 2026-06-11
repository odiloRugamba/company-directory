<script setup>
import { computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Pages/Layouts/AppLayout.vue';
import MultiSelect from '@/Components/Forms/MultiSelect.vue';
import FileUpload from '@/Components/Forms/FileUpload.vue';

const props = defineProps({
    profile: {
        type: Object,
        required: true,
    },
    sectors: {
        type: Array,
        default: () => [],
    },
});

const sectorOptions = computed(() =>
    props.sectors.map((s) => ({ label: s.name, value: s.slug }))
);

const stageOptions = [
    { label: 'Idea', value: 'idea' },
    { label: 'MVP', value: 'mvp' },
    { label: 'Early Traction', value: 'early_traction' },
    { label: 'Growth', value: 'growth' },
    { label: 'Scale', value: 'scale' },
];

const form = useForm({
    _method: 'PUT',
    organization_name: props.profile.organization_name ?? '',
    description: props.profile.description ?? '',
    country: props.profile.country ?? '',
    city: props.profile.city ?? '',
    website: props.profile.website ?? '',
    founded_year: props.profile.founded_year ?? '',
    capacity: props.profile.capacity ?? '',
    focus_sectors: props.profile.focus_sectors ?? [],
    supported_stages: props.profile.supported_stages ?? [],
    logo: null,
}, { forceFormData: true });

function submit() {
    form.post('/dashboard/profile', {
        preserveScroll: true,
    });
}

const previewLogoUrl = computed(() => {
    if (form.logo instanceof File) {
        return URL.createObjectURL(form.logo);
    }
    return props.profile.logo_url ?? null;
});

const previewDescription = computed(() => {
    const desc = form.description ?? '';
    return desc.length > 100 ? desc.slice(0, 100) + '...' : desc;
});

const previewSectors = computed(() => {
    return form.focus_sectors.slice(0, 2);
});

function sectorLabel(value) {
    const opt = sectorOptions.value.find((o) => o.value === value);
    return opt ? opt.label : value;
}
</script>

<template>
    <AppLayout title="Edit Profile">
        <Head title="Edit Profile" />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Incubator Profile</h1>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- LEFT COLUMN: Edit Form (2/3) -->
                <div class="lg:w-2/3">
                    <form @submit.prevent="submit" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
                        <!-- Organization Name -->
                        <div>
                            <label for="organization_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Organization Name
                            </label>
                            <input
                                id="organization_name"
                                v-model="form.organization_name"
                                type="text"
                                class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                placeholder="Your organization name"
                            />
                            <p v-if="form.errors.organization_name" class="mt-1 text-xs text-red-600">
                                {{ form.errors.organization_name }}
                            </p>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                Description
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="4"
                                class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none"
                                placeholder="Describe your incubator..."
                            />
                            <p v-if="form.errors.description" class="mt-1 text-xs text-red-600">
                                {{ form.errors.description }}
                            </p>
                        </div>

                        <!-- Country & City -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-1">
                                    Country
                                </label>
                                <input
                                    id="country"
                                    v-model="form.country"
                                    type="text"
                                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    placeholder="e.g. Rwanda"
                                />
                                <p v-if="form.errors.country" class="mt-1 text-xs text-red-600">
                                    {{ form.errors.country }}
                                </p>
                            </div>
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                                    City
                                </label>
                                <input
                                    id="city"
                                    v-model="form.city"
                                    type="text"
                                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    placeholder="e.g. Kigali"
                                />
                                <p v-if="form.errors.city" class="mt-1 text-xs text-red-600">
                                    {{ form.errors.city }}
                                </p>
                            </div>
                        </div>

                        <!-- Website -->
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 mb-1">
                                Website
                            </label>
                            <input
                                id="website"
                                v-model="form.website"
                                type="url"
                                class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                placeholder="https://yourwebsite.com"
                            />
                            <p v-if="form.errors.website" class="mt-1 text-xs text-red-600">
                                {{ form.errors.website }}
                            </p>
                        </div>

                        <!-- Founded Year & Capacity -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="founded_year" class="block text-sm font-medium text-gray-700 mb-1">
                                    Founded Year
                                </label>
                                <input
                                    id="founded_year"
                                    v-model="form.founded_year"
                                    type="number"
                                    min="1900"
                                    :max="new Date().getFullYear()"
                                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    placeholder="e.g. 2015"
                                />
                                <p v-if="form.errors.founded_year" class="mt-1 text-xs text-red-600">
                                    {{ form.errors.founded_year }}
                                </p>
                            </div>
                            <div>
                                <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">
                                    Capacity (startups per cohort)
                                </label>
                                <input
                                    id="capacity"
                                    v-model="form.capacity"
                                    type="number"
                                    min="1"
                                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    placeholder="e.g. 20"
                                />
                                <p v-if="form.errors.capacity" class="mt-1 text-xs text-red-600">
                                    {{ form.errors.capacity }}
                                </p>
                            </div>
                        </div>

                        <!-- Focus Sectors -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Focus Sectors
                            </label>
                            <MultiSelect
                                v-model="form.focus_sectors"
                                :options="sectorOptions"
                                placeholder="Select sectors..."
                            />
                            <p v-if="form.errors.focus_sectors" class="mt-1 text-xs text-red-600">
                                {{ form.errors.focus_sectors }}
                            </p>
                        </div>

                        <!-- Supported Stages -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Supported Stages
                            </label>
                            <MultiSelect
                                v-model="form.supported_stages"
                                :options="stageOptions"
                                placeholder="Select stages..."
                            />
                            <p v-if="form.errors.supported_stages" class="mt-1 text-xs text-red-600">
                                {{ form.errors.supported_stages }}
                            </p>
                        </div>

                        <!-- Logo Upload -->
                        <div>
                            <FileUpload
                                v-model="form.logo"
                                label="Logo"
                                accept="image/*"
                                :max-size-mb="2"
                            />
                            <p v-if="form.errors.logo" class="mt-1 text-xs text-red-600">
                                {{ form.errors.logo }}
                            </p>
                        </div>

                        <!-- Submit -->
                        <div class="flex justify-end pt-2">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed transition"
                            >
                                <svg
                                    v-if="form.processing"
                                    class="h-4 w-4 animate-spin"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                                </svg>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <!-- RIGHT COLUMN: Live Preview (1/3) -->
                <div class="lg:w-1/3">
                    <div class="sticky top-6">
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-3">Live Preview</p>

                        <!-- Public incubator card -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <!-- Card header -->
                            <div class="bg-[#001f5b] px-5 py-4 flex items-center gap-3">
                                <!-- Logo -->
                                <div class="h-12 w-12 flex-shrink-0 rounded-lg overflow-hidden bg-white/10 flex items-center justify-center">
                                    <img
                                        v-if="previewLogoUrl"
                                        :src="previewLogoUrl"
                                        alt="Logo"
                                        class="h-full w-full object-cover"
                                    />
                                    <span
                                        v-else
                                        class="text-white text-lg font-bold select-none"
                                    >
                                        {{ (form.organization_name || 'I').charAt(0).toUpperCase() }}
                                    </span>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h3 class="text-white font-semibold text-base leading-tight truncate">
                                            {{ form.organization_name || 'Organization Name' }}
                                        </h3>
                                        <!-- Verified badge -->
                                        <span
                                            v-if="profile.is_verified"
                                            class="inline-flex items-center gap-1 rounded-full bg-emerald-500/20 px-2 py-0.5 text-[11px] font-medium text-emerald-300 ring-1 ring-inset ring-emerald-500/30 flex-shrink-0"
                                        >
                                            <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                            </svg>
                                            Verified
                                        </span>
                                    </div>
                                    <p class="text-gray-300 text-xs mt-0.5 truncate">
                                        <template v-if="form.city && form.country">{{ form.city }}, {{ form.country }}</template>
                                        <template v-else-if="form.country">{{ form.country }}</template>
                                        <template v-else-if="form.city">{{ form.city }}</template>
                                        <template v-else>Location</template>
                                    </p>
                                </div>
                            </div>

                            <!-- Card body -->
                            <div class="px-5 py-4 space-y-3">
                                <!-- Focus sector chips -->
                                <div class="flex flex-wrap gap-1.5">
                                    <span
                                        v-for="slug in previewSectors"
                                        :key="slug"
                                        class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800"
                                    >
                                        {{ sectorLabel(slug) }}
                                    </span>
                                    <span
                                        v-if="form.focus_sectors.length === 0"
                                        class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500"
                                    >
                                        No sectors selected
                                    </span>
                                    <span
                                        v-if="form.focus_sectors.length > 2"
                                        class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500"
                                    >
                                        +{{ form.focus_sectors.length - 2 }} more
                                    </span>
                                </div>

                                <!-- Description -->
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    <template v-if="previewDescription">{{ previewDescription }}</template>
                                    <span v-else class="italic text-gray-400">No description provided.</span>
                                </p>
                            </div>
                        </div>

                        <p class="mt-3 text-xs text-gray-400 text-center">
                            This is how your card appears in the public directory.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
