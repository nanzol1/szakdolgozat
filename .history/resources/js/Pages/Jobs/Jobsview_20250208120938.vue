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
    {{ job.id }}
</template>