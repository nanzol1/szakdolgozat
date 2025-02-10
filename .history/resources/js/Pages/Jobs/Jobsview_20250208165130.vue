<script setup>

import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
    job:{
        type:Object,
    },
    application:{
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
    const isAppliedArray = Object.values(props.application);
    isAppliedArray.forEach(element => {
        if((element.job_id === props.job.id || element.user_id === user.id) && element.is_deleted !== 1){
            isApplied.value = true;
        }else{
            isApplied.value = false;
        }
    });

});



const computeApplied = computed(() => {
    if(isApplied.value){
        return isAppliedText.value = "Már jelentkeztem";
    }else{
        return isAppliedText.value = "Jelentkezem";
    }
});

const isJobActive = computed(() => {
    if(props.job.active === 1){
        return true;
    }else{
        return false;
    }
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
    {{ job.active }}
    <div>Feladatkör: {{ job.subcateg_name }}</div>
    <div>Leírás: {{ job.description }}</div>
    <div>Cég név: {{ job.company_name }}</div>
    <div>Elvárások: {{ job.requirements }}</div>
    <div>Fizetés: {{ payment }}</div>
    <button @click="sendApplication" :disabled="isApplied" v-if="isJobActive"> {{ computeApplied }}</button>
    <button @click="revokeApplication" :disabled="!isApplied" v-show="!isJobActive">Mégsem</button>
</template>