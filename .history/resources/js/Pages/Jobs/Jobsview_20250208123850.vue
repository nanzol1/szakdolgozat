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
    }
})
</script>
<template>
    <Head title="Asd"></Head>
    Munka
    <div>Név: {{ job.name }}</div>
    Feladatkör: {{ job.subcateg_name }}
    Leírás: {{ job.description }}
    Cég név: {{ job.company_name }}
    Elvárások: {{ job.requirements }}
    Fizetés {{ job.payment }}
</template>