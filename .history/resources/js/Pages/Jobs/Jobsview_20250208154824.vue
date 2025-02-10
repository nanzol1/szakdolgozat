<script setup>

import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
    job:{
        type:Object,
    }
});

const user = usePage().props.auth.user;

const isApplied = ref(false);
const isAppliedText = ref('Jelentkezem');

const payment = computed(() => {
    if(props.job.payment === null){
        return 'Nincs megadva fizetés';
    }else{
        return props.job.payment;
    }
});

onMounted(() => {
    const isAppliedArray = JSON.parse(user.applied_for);
    isAppliedArray.forEach(element => {
        if(element === props.job.id){
            isApplied.value = true;
        }else{
            isApplied.value = false;
        }
    });
    if(isApplied.value){
        isAppliedText.value = "Már jelentkeztem";
    }else{
        isAppliedText.value = "Jelentkezem";
    }
});

const computeApplied = computed(() => {
    return isApplied;
});

const sendApplication = () => {
    router.post(route('jobs.apply',{id:props.job.id}),{
        job_id: props.job.id,
    },{
        onSuccess: () => {
            isApplied.value = true;
        },
        onError: (error) => {
            console.log(error);
        }
    });
};

const revokeApplication = () => {
    router.post(route('jobs.apply.revoke',{id:props.job.id}),{
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
    <button @click="sendApplication" :disabled="isApplied">{{ isAppliedText }}</button>
    <button @click="revokeApplication" :disabled="!isApplied">Mégsem</button>
    ss {{ computeApplied }}
</template>