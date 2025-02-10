<script setup>

import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    jobs:{
        type:Object,
    },
    filters:{
        type:String
    }
});

const search = ref(props.search.value || '');




</script>

<template>
    <Head title="Teszt"></Head>
    <div class="bock">
        Szűrők
    </div>
    <input type="text" v-model="search" placeholder="Keresés...">
    <div v-for="job in jobs" :key="job.id">
        {{ job.name }}
        {{ job.active }}
        {{ job.position_name }}
        {{ job.description }}
        <Link :href="route('jobs.view',{id:job.id})">Érdeklődöm</Link>
    </div>
</template>