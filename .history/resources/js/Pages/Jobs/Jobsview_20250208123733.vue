<script setup>

import { Head } from '@inertiajs/vue3';

const props = defineProps({
    job:{
        type:Array,
    }
});
</script>
<template>
    <Head title="Asd"></Head>
    Munka
    Név: {{ job }}
    Leírás: {{ job.description }}
    Cég név: {{ job.company_name }}
    Elvárások: {{ job.requirements }}
    Fizetés {{ job.payment }}
</template>