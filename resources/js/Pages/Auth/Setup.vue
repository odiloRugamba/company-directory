<script setup>
import { computed } from 'vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
import MultiSelect from '@/Components/Forms/MultiSelect.vue';
import FileUpload from '@/Components/Forms/FileUpload.vue';
import SelectInput from '@/Components/Forms/SelectInput.vue';

const props = defineProps({
    sectors: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const isIncubator = computed(() => user.value?.role === 'incubator');

// ── Sector options for startup SelectInput ───────────────────────────────────
const sectorOptions = computed(() =>
    props.sectors.map((s) => ({ value: s.slug, label: s.name }))
);

// ── Sector options for incubator MultiSelect ─────────────────────────────────
const sectorMultiOptions = computed(() =>
    props.sectors.map((s) => ({ value: s.slug, label: s.name }))
);

// ── Stage options ─────────────────────────────────────────────────────────────
const stageOptions = [
    { value: 'idea',           label: 'Idea',           description: 'Pre-product, validating the problem' },
    { value: 'mvp',            label: 'MVP',            description: 'Built something, testing with first users' },
    { value: 'early_traction', label: 'Early Traction', description: 'Paying customers, proving the model' },
    { value: 'growth',         label: 'Growth',         description: 'Scaling what works' },
    { value: 'scale',          label: 'Scale',          description: 'Expanding markets and team' },
];

const stageMultiOptions = stageOptions.map((s) => ({ value: s.value, label: s.label }));

// ── Incubator form ────────────────────────────────────────────────────────────
const incubatorForm = useForm({
    organization_name:  '',
    description:        '',
    country:            '',
    city:               '',
    website:            '',
    founded_year:       '',
    capacity:           '',
    focus_sectors:      [],
    supported_stages:   [],
    logo:               null,
}, { forceFormData: true });

const submitIncubator = () => {
    incubatorForm.post('/setup/incubator');
};

// ── Startup form ──────────────────────────────────────────────────────────────
const startupForm = useForm({
    company_name:  '',
    tagline:       '',
    description:   '',
    country:       '',
    city:          '',
    website:       '',
    sector:        '',
    stage:         '',
    team_size:     '',
    logo:          null,
}, { forceFormData: true });

const submitStartup = () => {
    startupForm.post('/setup/startup');
};
</script>

<template>
    <Head title="Complete Your Profile — SANIA" />

    <div class="min-h-screen bg-slate flex items-center justify-center p-4">
        <div class="w-full max-w-2xl">

            <!-- Brand -->
            <div class="text-center mb-8">
                <h1 class="font-display text-3xl font-bold text-navy">SANIA</h1>
                <p class="text-gray-500 mt-1">Africa's Startup Ecosystem</p>
            </div>

            <!-- Progress indicator -->
            <div class="mb-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-emerald text-white text-xs font-bold">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </span>
                        <span class="text-sm text-gray-400 line-through">Step 1: Create account</span>
                    </div>
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <div class="flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-navy text-white text-xs font-bold">2</span>
                        <span class="text-sm font-semibold text-navy">Complete your profile</span>
                    </div>
                </div>
                <p class="text-xs text-gray-400 text-right">Step 2 of 2: Complete your profile</p>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-dark p-8">

                <!-- ── INCUBATOR FORM ── -->
                <template v-if="isIncubator">
                    <h2 class="text-xl font-bold text-navy mb-1">Set up your incubator profile</h2>
                    <p class="text-sm text-gray-500 mb-6">Tell startups and investors about your organisation.</p>

                    <form @submit.prevent="submitIncubator" class="space-y-5">

                        <!-- Organization name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Organization name <span class="text-red-500">*</span></label>
                            <input
                                v-model="incubatorForm.organization_name"
                                type="text"
                                required
                                placeholder="e.g. kLab"
                                class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896]"
                                :class="incubatorForm.errors.organization_name ? 'border-red-500' : 'border-gray-300'"
                            />
                            <p v-if="incubatorForm.errors.organization_name" class="text-red-500 text-xs mt-1">{{ incubatorForm.errors.organization_name }}</p>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea
                                v-model="incubatorForm.description"
                                rows="4"
                                placeholder="What does your incubator do? Who do you support?"
                                class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896] resize-none"
                                :class="incubatorForm.errors.description ? 'border-red-500' : 'border-gray-300'"
                            ></textarea>
                            <p v-if="incubatorForm.errors.description" class="text-red-500 text-xs mt-1">{{ incubatorForm.errors.description }}</p>
                        </div>

                        <!-- Country / City -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                <input
                                    v-model="incubatorForm.country"
                                    type="text"
                                    placeholder="e.g. Rwanda"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896]"
                                    :class="incubatorForm.errors.country ? 'border-red-500' : 'border-gray-300'"
                                />
                                <p v-if="incubatorForm.errors.country" class="text-red-500 text-xs mt-1">{{ incubatorForm.errors.country }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input
                                    v-model="incubatorForm.city"
                                    type="text"
                                    placeholder="e.g. Kigali"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896]"
                                    :class="incubatorForm.errors.city ? 'border-red-500' : 'border-gray-300'"
                                />
                                <p v-if="incubatorForm.errors.city" class="text-red-500 text-xs mt-1">{{ incubatorForm.errors.city }}</p>
                            </div>
                        </div>

                        <!-- Website / Founded year -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                                <input
                                    v-model="incubatorForm.website"
                                    type="url"
                                    placeholder="https://example.com"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896]"
                                    :class="incubatorForm.errors.website ? 'border-red-500' : 'border-gray-300'"
                                />
                                <p v-if="incubatorForm.errors.website" class="text-red-500 text-xs mt-1">{{ incubatorForm.errors.website }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Founded year</label>
                                <input
                                    v-model="incubatorForm.founded_year"
                                    type="number"
                                    min="1900"
                                    :max="new Date().getFullYear()"
                                    placeholder="e.g. 2012"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896]"
                                    :class="incubatorForm.errors.founded_year ? 'border-red-500' : 'border-gray-300'"
                                />
                                <p v-if="incubatorForm.errors.founded_year" class="text-red-500 text-xs mt-1">{{ incubatorForm.errors.founded_year }}</p>
                            </div>
                        </div>

                        <!-- Capacity -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cohort capacity</label>
                            <input
                                v-model="incubatorForm.capacity"
                                type="number"
                                min="1"
                                placeholder="e.g. 20"
                                class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896]"
                                :class="incubatorForm.errors.capacity ? 'border-red-500' : 'border-gray-300'"
                            />
                            <p v-if="incubatorForm.errors.capacity" class="text-red-500 text-xs mt-1">{{ incubatorForm.errors.capacity }}</p>
                        </div>

                        <!-- Focus sectors -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Focus sectors</label>
                            <MultiSelect
                                v-model="incubatorForm.focus_sectors"
                                :options="sectorMultiOptions"
                                placeholder="Select sectors…"
                            />
                            <p v-if="incubatorForm.errors.focus_sectors" class="text-red-500 text-xs mt-1">{{ incubatorForm.errors.focus_sectors }}</p>
                        </div>

                        <!-- Supported stages -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supported startup stages</label>
                            <MultiSelect
                                v-model="incubatorForm.supported_stages"
                                :options="stageMultiOptions"
                                placeholder="Select stages…"
                            />
                            <p v-if="incubatorForm.errors.supported_stages" class="text-red-500 text-xs mt-1">{{ incubatorForm.errors.supported_stages }}</p>
                        </div>

                        <!-- Logo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                            <FileUpload
                                v-model="incubatorForm.logo"
                                accept="image/*"
                                :max-size-mb="2"
                            />
                            <p v-if="incubatorForm.errors.logo" class="text-red-500 text-xs mt-1">{{ incubatorForm.errors.logo }}</p>
                        </div>

                        <!-- Submit -->
                        <button
                            type="submit"
                            :disabled="incubatorForm.processing"
                            class="w-full bg-navy text-white rounded-lg py-2.5 font-semibold hover:bg-navy-light transition disabled:opacity-60 mt-2"
                        >
                            {{ incubatorForm.processing ? 'Saving…' : 'Complete setup' }}
                        </button>

                    </form>
                </template>

                <!-- ── STARTUP FORM ── -->
                <template v-else>
                    <h2 class="text-xl font-bold text-navy mb-1">Set up your startup profile</h2>
                    <p class="text-sm text-gray-500 mb-6">Help incubators and investors discover you.</p>

                    <form @submit.prevent="submitStartup" class="space-y-5">

                        <!-- Company name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Company name <span class="text-red-500">*</span></label>
                            <input
                                v-model="startupForm.company_name"
                                type="text"
                                required
                                placeholder="e.g. Zipline Rwanda"
                                class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896]"
                                :class="startupForm.errors.company_name ? 'border-red-500' : 'border-gray-300'"
                            />
                            <p v-if="startupForm.errors.company_name" class="text-red-500 text-xs mt-1">{{ startupForm.errors.company_name }}</p>
                        </div>

                        <!-- Tagline -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                            <input
                                v-model="startupForm.tagline"
                                type="text"
                                maxlength="120"
                                placeholder="One sentence that sums up what you do"
                                class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896]"
                                :class="startupForm.errors.tagline ? 'border-red-500' : 'border-gray-300'"
                            />
                            <p v-if="startupForm.errors.tagline" class="text-red-500 text-xs mt-1">{{ startupForm.errors.tagline }}</p>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea
                                v-model="startupForm.description"
                                rows="4"
                                placeholder="What problem are you solving and how?"
                                class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896] resize-none"
                                :class="startupForm.errors.description ? 'border-red-500' : 'border-gray-300'"
                            ></textarea>
                            <p v-if="startupForm.errors.description" class="text-red-500 text-xs mt-1">{{ startupForm.errors.description }}</p>
                        </div>

                        <!-- Country / City -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                <input
                                    v-model="startupForm.country"
                                    type="text"
                                    placeholder="e.g. Kenya"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896]"
                                    :class="startupForm.errors.country ? 'border-red-500' : 'border-gray-300'"
                                />
                                <p v-if="startupForm.errors.country" class="text-red-500 text-xs mt-1">{{ startupForm.errors.country }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input
                                    v-model="startupForm.city"
                                    type="text"
                                    placeholder="e.g. Nairobi"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896]"
                                    :class="startupForm.errors.city ? 'border-red-500' : 'border-gray-300'"
                                />
                                <p v-if="startupForm.errors.city" class="text-red-500 text-xs mt-1">{{ startupForm.errors.city }}</p>
                            </div>
                        </div>

                        <!-- Website / Team size -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                                <input
                                    v-model="startupForm.website"
                                    type="url"
                                    placeholder="https://example.com"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896]"
                                    :class="startupForm.errors.website ? 'border-red-500' : 'border-gray-300'"
                                />
                                <p v-if="startupForm.errors.website" class="text-red-500 text-xs mt-1">{{ startupForm.errors.website }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Team size</label>
                                <input
                                    v-model="startupForm.team_size"
                                    type="number"
                                    min="1"
                                    placeholder="e.g. 4"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald/30 focus:border-[#00C896]"
                                    :class="startupForm.errors.team_size ? 'border-red-500' : 'border-gray-300'"
                                />
                                <p v-if="startupForm.errors.team_size" class="text-red-500 text-xs mt-1">{{ startupForm.errors.team_size }}</p>
                            </div>
                        </div>

                        <!-- Sector -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sector</label>
                            <SelectInput
                                v-model="startupForm.sector"
                                :options="sectorOptions"
                                placeholder="Select a sector"
                                :error="startupForm.errors.sector"
                            />
                        </div>

                        <!-- Stage — visual selector -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stage <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-5 gap-2">
                                <button
                                    v-for="stage in stageOptions"
                                    :key="stage.value"
                                    type="button"
                                    @click="startupForm.stage = stage.value"
                                    class="relative flex flex-col gap-1 rounded-xl border-2 px-3 py-3 text-left transition-all focus:outline-none focus:ring-2 focus:ring-emerald/40"
                                    :class="startupForm.stage === stage.value
                                        ? 'border-[#00C896] bg-emerald-50 shadow-sm'
                                        : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'"
                                >
                                    <!-- Checkmark badge -->
                                    <span
                                        v-if="startupForm.stage === stage.value"
                                        class="absolute top-2 right-2 flex items-center justify-center w-4 h-4 rounded-full bg-[#00C896]"
                                    >
                                        <svg class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    </span>
                                    <span
                                        class="text-xs font-bold"
                                        :class="startupForm.stage === stage.value ? 'text-emerald' : 'text-gray-700'"
                                    >
                                        {{ stage.label }}
                                    </span>
                                    <span class="text-xs text-gray-500 leading-snug">{{ stage.description }}</span>
                                </button>
                            </div>
                            <p v-if="startupForm.errors.stage" class="text-red-500 text-xs mt-1">{{ startupForm.errors.stage }}</p>
                        </div>

                        <!-- Logo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                            <FileUpload
                                v-model="startupForm.logo"
                                accept="image/*"
                                :max-size-mb="2"
                            />
                            <p v-if="startupForm.errors.logo" class="text-red-500 text-xs mt-1">{{ startupForm.errors.logo }}</p>
                        </div>

                        <!-- Submit -->
                        <button
                            type="submit"
                            :disabled="startupForm.processing"
                            class="w-full bg-navy text-white rounded-lg py-2.5 font-semibold hover:bg-navy-light transition disabled:opacity-60 mt-2"
                        >
                            {{ startupForm.processing ? 'Saving…' : 'Complete setup' }}
                        </button>

                    </form>
                </template>

            </div>
        </div>
    </div>
</template>
