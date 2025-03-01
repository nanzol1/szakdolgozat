<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Applied from '../Components/Applied.vue';

const props = defineProps({
    myjobs:{
        type:Object,
    }
});
</script>

<template>
    <Head title="Munkáim"></Head>
    <AuthenticatedLayout>
        <Applied :isShort="false" :myjobs="myjobs"></Applied>
    </AuthenticatedLayout>
</template>