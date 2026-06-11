<template>
  <Head :title="incubator.org_name" />

  <PublicLayout>
    <!-- HEADER -->
    <div class="bg-[#001f5b] text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-300 mb-6">
          <Link href="/directory" class="hover:text-white transition">Directory</Link>
          <span>/</span>
          <span class="text-white font-medium truncate">{{ incubator.org_name }}</span>
        </nav>

        <!-- Profile header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
          <Avatar
            :src="incubator.logo_url ?? null"
            :name="incubator.org_name"
            size="lg"
          />

          <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-3 mb-1">
              <h1 class="font-display text-2xl sm:text-3xl font-bold text-white leading-tight">
                {{ incubator.org_name }}
              </h1>
              <Badge v-if="incubator.user?.email_verified_at" status="verified" />
            </div>

            <p class="text-gray-300 text-sm mb-3">
              <span v-if="incubator.city">{{ incubator.city }}, </span>
              <span>{{ incubator.country }}</span>
            </p>

            <a
              v-if="incubator.website_url"
              :href="incubator.website_url"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-1.5 text-[#00C896] hover:text-emerald-300 text-sm font-medium transition"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
              </svg>
              {{ incubator.website_url.replace(/^https?:\/\//, '') }}
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- TWO-COLUMN BODY -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
      <div class="flex flex-col lg:flex-row gap-8">

        <!-- Left column (2/3) -->
        <div class="flex-1 min-w-0">

          <!-- Description -->
          <section class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">About</h2>
            <p v-if="incubator.bio" class="text-gray-700 leading-relaxed whitespace-pre-line">{{ incubator.bio }}</p>
            <p v-else class="text-gray-400 italic">No description provided.</p>
          </section>

          <!-- Details grid -->
          <section class="mb-8 bg-white border border-gray-100 rounded-xl p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900 mb-5">Details</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

              <!-- Focus Sectors -->
              <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Focus Sectors</p>
                <div v-if="focusSectors.length" class="flex flex-wrap gap-2">
                  <span
                    v-for="sector in focusSectors"
                    :key="sector"
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#E8EDF5] text-[#0F1F3D]"
                  >
                    {{ sector }}
                  </span>
                </div>
                <p v-else class="text-gray-400 text-sm">—</p>
              </div>

              <!-- Supported Stages -->
              <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Supported Stages</p>
                <div v-if="supportedStages.length" class="flex flex-wrap gap-2">
                  <span
                    v-for="stage in supportedStages"
                    :key="stage"
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#fff0ed] text-[#ff6b4a]"
                  >
                    {{ stage }}
                  </span>
                </div>
                <p v-else class="text-gray-400 text-sm">—</p>
              </div>

              <!-- Capacity -->
              <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Capacity</p>
                <p v-if="incubator.capacity" class="text-gray-800 text-sm font-medium">
                  {{ incubator.capacity }} startups
                </p>
                <p v-else class="text-gray-400 text-sm">—</p>
              </div>

              <!-- Founded Year -->
              <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Founded Year</p>
                <p v-if="incubator.founded_year" class="text-gray-800 text-sm font-medium">
                  {{ incubator.founded_year }}
                </p>
                <p v-else class="text-gray-400 text-sm">—</p>
              </div>

            </div>
          </section>

        </div>

        <!-- Right column (1/3) — Apply card sticky -->
        <div class="w-full lg:w-80 flex-shrink-0">
          <div class="sticky top-24">

            <!-- canApply: show apply button -->
            <div v-if="canApply" class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm">
              <h3 class="text-base font-semibold text-gray-900 mb-1">Interested in joining?</h3>
              <p class="text-sm text-gray-500 mb-5">Submit your application to this incubator program.</p>
              <button
                type="button"
                class="w-full bg-[#001f5b] hover:bg-[#0a3080] text-white font-semibold py-2.5 px-4 rounded-lg transition text-sm"
                @click="showApplyModal = true"
              >
                Apply Now
              </button>
            </div>

            <!-- hasApplied: status card -->
            <div v-else-if="hasApplied" class="bg-white border border-[#00C896]/30 rounded-xl p-6 shadow-sm">
              <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-full bg-[#d1fae5] flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#00C896]" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900">Application Submitted</h3>
              </div>
              <p class="text-sm text-gray-500">Your application has been received. The incubator team will be in touch.</p>
            </div>

            <!-- Not authenticated -->
            <div v-else-if="!auth.user" class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm">
              <h3 class="text-base font-semibold text-gray-900 mb-1">Want to apply?</h3>
              <p class="text-sm text-gray-500 mb-5">Create an account or log in to submit your application.</p>
              <Link
                :href="route('login')"
                class="block w-full text-center bg-[#001f5b] hover:bg-[#0a3080] text-white font-semibold py-2.5 px-4 rounded-lg transition text-sm"
              >
                Login to Apply
              </Link>
            </div>

          </div>
        </div>

      </div>
    </div>

    <!-- ALUMNI STARTUPS SECTION -->
    <div v-if="acceptedStartups && acceptedStartups.length" class="border-t border-gray-100 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h2 class="text-xl font-bold text-gray-900 font-display mb-6">Alumni Startups</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          <div
            v-for="startup in acceptedStartups"
            :key="startup.id"
            class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-shadow"
          >
            <div class="flex items-center gap-3 mb-3">
              <Avatar
                :src="startup.logo_url ?? null"
                :name="startup.org_name"
                size="sm"
              />
              <div class="min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ startup.org_name }}</p>
              </div>
            </div>

            <div class="flex flex-wrap gap-1.5">
              <span
                v-if="startup.sector"
                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[#E8EDF5] text-[#0F1F3D]"
              >
                {{ startup.sector }}
              </span>
              <span
                v-if="startup.stage"
                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[#fff0ed] text-[#ff6b4a]"
              >
                {{ startup.stage }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

  </PublicLayout>

  <!-- Apply Modal -->
  <Modal
    :open="showApplyModal"
    title="Apply to join"
    @close="closeModal"
  >
    <form @submit.prevent="submitApplication">
      <div class="space-y-5">

        <!-- Motivation -->
        <div>
          <label for="motivation" class="block text-sm font-medium text-gray-700 mb-1.5">
            Motivation <span class="text-red-500">*</span>
          </label>
          <textarea
            id="motivation"
            v-model="form.motivation"
            rows="5"
            placeholder="Tell us why you want to join this incubator and what your startup does... (minimum 100 characters)"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#001f5b] focus:border-transparent resize-none transition"
            :class="{ 'border-red-400 focus:ring-red-400': motivationError }"
          />
          <div class="flex justify-between items-center mt-1">
            <p v-if="motivationError" class="text-xs text-red-500">{{ motivationError }}</p>
            <p v-else class="text-xs text-gray-400"></p>
            <p class="text-xs text-gray-400 ml-auto" :class="{ 'text-red-500': form.motivation.length > 0 && form.motivation.length < 100, 'text-[#00C896]': form.motivation.length >= 100 }">
              {{ form.motivation.length }} / 100 min
            </p>
          </div>
        </div>

        <!-- Pitch Deck URL -->
        <div>
          <label for="pitch_deck_url" class="block text-sm font-medium text-gray-700 mb-1.5">
            Pitch Deck URL
          </label>
          <input
            id="pitch_deck_url"
            v-model="form.pitch_deck_url"
            type="url"
            placeholder="https://docs.google.com/..."
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#001f5b] focus:border-transparent transition"
          />
          <p class="text-xs text-gray-400 mt-1">Optional — link to your pitch deck or business plan</p>
        </div>

      </div>
    </form>

    <template #footer>
      <div class="flex justify-end gap-3">
        <button
          type="button"
          class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition"
          @click="closeModal"
        >
          Cancel
        </button>
        <button
          type="button"
          class="px-4 py-2 text-sm font-semibold text-white bg-[#001f5b] hover:bg-[#0a3080] rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
          :disabled="form.processing || form.motivation.length < 100"
          @click="submitApplication"
        >
          <svg v-if="form.processing" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 12 0 12 12h4a8 8 0 01-8 8v-4a8 8 0 01-8-8z"></path>
          </svg>
          Submit Application
        </button>
      </div>
    </template>
  </Modal>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router, usePage, Head } from '@inertiajs/vue3'
import Avatar from '@/Components/UI/Avatar.vue'
import Badge from '@/Components/UI/Badge.vue'
import Modal from '@/Components/UI/Modal.vue'
import PublicLayout from '@/Pages/Layouts/PublicLayout.vue'

const props = defineProps({
  incubator: {
    type: Object,
    required: true,
  },
  acceptedStartups: {
    type: Array,
    default: () => [],
  },
  hasApplied: {
    type: Boolean,
    default: false,
  },
  canApply: {
    type: Boolean,
    default: false,
  },
})

const page = usePage()
const auth = computed(() => page.props.auth ?? {})

// Normalize array fields that may be stored as JSON strings or arrays
const focusSectors = computed(() => {
  const val = props.incubator.focus_sectors
  if (!val) return []
  if (Array.isArray(val)) return val
  try { return JSON.parse(val) } catch { return [] }
})

const supportedStages = computed(() => {
  const val = props.incubator.supported_stages
  if (!val) return []
  if (Array.isArray(val)) return val
  try { return JSON.parse(val) } catch { return [] }
})

// Apply modal state
const showApplyModal = ref(false)

const form = ref({
  motivation: '',
  pitch_deck_url: '',
  processing: false,
  incubator_id: props.incubator.id,
})

const motivationError = ref('')

function closeModal() {
  showApplyModal.value = false
  motivationError.value = ''
}

function submitApplication() {
  motivationError.value = ''

  if (form.value.motivation.trim().length < 100) {
    motivationError.value = 'Motivation must be at least 100 characters.'
    return
  }

  form.value.processing = true

  router.post('/dashboard/apply', {
    incubator_id: form.value.incubator_id,
    motivation: form.value.motivation,
    pitch_deck_url: form.value.pitch_deck_url || null,
  }, {
    onSuccess: () => {
      showApplyModal.value = false
      form.value.motivation = ''
      form.value.pitch_deck_url = ''
    },
    onError: (errors) => {
      if (errors.motivation) {
        motivationError.value = errors.motivation
      }
    },
    onFinish: () => {
      form.value.processing = false
    },
  })
}
</script>
