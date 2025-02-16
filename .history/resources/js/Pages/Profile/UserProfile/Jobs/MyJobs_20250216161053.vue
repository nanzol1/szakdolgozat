<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
});
</script>

<template>
    <Head title="Munkáim"></Head>
    <AuthenticatedLayout>
        <div class="dark:text-white mb-3">
            Munkák amire jelentkeztem
        </div>
    </AuthenticatedLayout>
</template>