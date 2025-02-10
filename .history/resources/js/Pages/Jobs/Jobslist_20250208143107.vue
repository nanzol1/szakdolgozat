<script setup>

import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    jobs:{
        type:Array,
    }
});

const sendApplication = () => {
    
};

</script>

<template>
    <Head title="Teszt"></Head>

    <div v-for="job in jobs" :key="job.id">
        {{ job.name }}
        {{ job.position_name }}
        {{ job.description }}
        <button @click="sendApplication">Érdeklődöm</button>
    </div>
</template>