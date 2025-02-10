<script setup>

import { Head } from '@inertiajs/vue3';

const props = defineProps({
    jobs:{
        type:Array,
    }
});

</script>

<template>
    <Head title="Teszt"></Head>

    <div v-for="jobs in jobs"></div>
</template>