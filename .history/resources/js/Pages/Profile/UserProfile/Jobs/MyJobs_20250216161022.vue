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
        <div class="dark:text-white mb-3">
            Munkák amire jelentkeztem
        </div>
        <template v-for="job in myjobs">
            <div class="dark:text-white mb-3">
                
            </div>
        </template>
    </AuthenticatedLayout>
</template>