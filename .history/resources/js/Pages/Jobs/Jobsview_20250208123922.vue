<script setup>

import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    job:{
        type:Array,
    }
});

const payment = computed(() => {
    if(job.payment === null){
        job.payment = 'Nincs megadva fizetés';
    }else{
        
    }
})
</script>
<template>
    <Head title="Asd"></Head>
    Munka
    <div>Név: {{ job.name }}</div>
    <div>Feladatkör: {{ job.subcateg_name }}</div>
    <div>Leírás: {{ job.description }}</div>
    <div>Cég név: {{ job.company_name }}</div>
    <div>Elvárások: {{ job.requirements }}</div>
    <div>Fizetés {{ payment }}</div>
</template>