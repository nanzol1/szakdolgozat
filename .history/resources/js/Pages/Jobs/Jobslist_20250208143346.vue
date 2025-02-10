<script setup>

import { Head, Link, router } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const isApplied = ref(false);

const props = defineProps({
    jobs:{
        type:Array,
    }
});

const sendApplication = () => {
    router.post(route('jobs.view',{id:job.id}),{
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

</script>

<template>
    <Head title="Teszt"></Head>

    <div v-for="job in jobs" :key="job.id">
        {{ job.name }}
        {{ job.position_name }}
        {{ job.description }}
        <button @click="sendApplication" :disabled="isApplied">Érdeklődöm</button>
    </div>
</template>