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
        <template v-for="job in myjobs">
            <div>
                {{ job}}
            </div>
        </template>
    </AuthenticatedLayout>
</template>