<script setup>

import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';
import { computed } from 'vue';

const props = defineProps({
    job:{
        type:Object,
    }
});

const payment = computed(() => {
    if(props.job.payment === null){
        return 'Nincs megadva fizetés';
    }else{
        return props.job.payment;
    }
});

const apply = () => {
    router.post(route('jobs.apply',{id:props.job.id}));
};
</script>
<template>
    <Head title="Asd"></Head>
    Munka
    <div>Név: {{ job.name }}</div>
    <div>Feladatkör: {{ job.subcateg_name }}</div>
    <div>Leírás: {{ job.description }}</div>
    <div>Cég név: {{ job.company_name }}</div>
    <div>Elvárások: {{ job.requirements }}</div>
    <div>Fizetés: {{ payment }}</div>
    <button @click="">Jelentkezem</button>
</template>