<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Sidebar from '@/Components/Layout/Sidebar.vue';
import TopBar from '@/Components/Layout/TopBar.vue';
import Alert from '@/Components/UI/Alert.vue';
import { useFlash } from '@/Composables/useFlash';

const props = defineProps({
    title: { type: String, default: '' },
});

const page = usePage();
const user = computed(() => page.props.auth?.user);
const role = computed(() => user.value?.role ?? 'startup');
const { success, error } = useFlash();
</script>

<template>
    <div class="flex h-screen bg-slate overflow-hidden">
        <Sidebar :role="role" />

        <div class="flex flex-col flex-1 overflow-hidden">
            <TopBar :title="title">
                <slot name="title" />
            </TopBar>

            <main class="flex-1 overflow-y-auto p-6">
                <div v-if="success" class="mb-4">
                    <Alert type="success" :message="success" />
                </div>
                <div v-if="error" class="mb-4">
                    <Alert type="error" :message="error" />
                </div>

                <slot />
            </main>
        </div>
    </div>
</template>
