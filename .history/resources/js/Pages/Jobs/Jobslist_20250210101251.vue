<script setup>

import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    jobs:{
        type:Object,
    },
    categories:{
        type:Object,
    }
});



</script>

<template>
    <Head title="Teszt"></Head>
    <div class="bock">
        Szűrők
    </div>
    <Link v-for="cats in categories" :key="cats.id" :href="cats.name">{{ cats.name }}</Link>
    <div v-for="job in jobs" :key="job.id">
        {{ job.name }}
        {{ job.active }}
        {{ job.position_name }}
        {{ job.description }}
        <Link :href="route('jobs.view',{id:job.id})">Érdeklődöm</Link>
    </div>
</template>