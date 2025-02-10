<script setup>

import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    job:{
        type:Object,
    }
});

const isApplied = ref(false);

const payment = computed(() => {
    if(props.job.payment === null){
        return 'Nincs megadva fizetés';
    }else{
        return props.job.payment;
    }
});

const sendApplication = () => {
    router.post(route('jobs.apply',{id:props.job.id}),{
        job_id: props.job.id,
    },{
        onSuccess: () => {
            isApplied.value = false;
        },
        onError: (error) => {
            console.log(error);
        }
    });
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
    <button @click="sendApplication" :disabled="isApplied">Jelentkezem</button>
</template>