<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    myjobs:{
        type:Object,
    }
});
</script>

<template>
    <Head title="Munkáim"></Head>
    <AuthenticatedLayout>
        <div class="dark:text-white">
            M
        </div>
        <template v-for="job in myjobs">
            <div class="text-white mb-3">
                {{ job }}
            </div>
        </template>
    </AuthenticatedLayout>
</template>