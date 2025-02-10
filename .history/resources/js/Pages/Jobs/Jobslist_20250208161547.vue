<script setup>

import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    jobs:{
        type:Object,
    }
});



</script>

<template>
    <Head title="Teszt"></Head>

    <div v-for="job in jobs" :key="job.id">
        {{ job.name }}
        {{ job.active }}
        {{ job.position_name }}
        {{ job.description }}
        <Link :href="route('jobs.view',{id:job.id})">Érdeklődöm</Link>
    </div>
</template>