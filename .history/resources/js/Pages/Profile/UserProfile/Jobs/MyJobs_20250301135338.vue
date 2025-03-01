<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Applied from '../Components/Applied.vue';

const props = defineProps({
    myjobs:{
        type:Object,
    }
});

const showSelectedJob = (id) => {
    props.appliactions.forEach(element => {
        if(element.id === id){
            selectedJob.value = element;
            isPopup.value = true;
        }
    });
};
</script>

<template>
    <AuthenticatedLayout>
    <Head title="Munkáim"></Head>

        <v-container class="py-12">
            <v-row>
                <v-col cols="6">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100 vuetify-container">
                            <Applied :isShort="true" :myjobs="myjobs"></Applied>
                        </div>
                    </div>
                </v-col>
                <v-col cols="6">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100 vuetify-container">
                            <div v-for="job in myjobs.data" :key="job.id">
                                <v-btn @click="showSelectedJob(apps.id)"></v-btn>
                            </div>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </AuthenticatedLayout>
</template>