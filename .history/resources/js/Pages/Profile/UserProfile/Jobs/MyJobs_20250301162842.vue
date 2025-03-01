<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Applied from '../Components/Applied.vue';
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
    myjobs:{
        type:Object,
    }
});

const selectedJob = ref(null);

const handleSelect = (job) => {
    selectedJob.value = job;
};
onMounted(() => {
    const queryString = window.location.search;
    const URL = new URLSearchParams(queryString);
    const id = URL.get('id');
    if(id){
        selectedJob.value = props.myjobs.data.find(element => element.job_id === Number(id));
    }
});

const divideAddress = computed(() => {
    if(selectedJob){
        let zip = selectedJob.value.job_address.split(',')[0];
        let city = selectedJob.value.job_address.split(',')[1];
        let address = selectedJob.value.job_address.split(',')[2];

        return zip+', '+city+', '+address;
    }
});
</script>

<template>
    <AuthenticatedLayout>
    <Head title="Munkáim"></Head>

        {{ selectedJob }}
        <v-container class="py-12">
            <v-row>
                <v-col cols="6">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100 vuetify-container">
                            <Applied :isShort="false" :myjobs="myjobs" @showSelectedJob="handleSelect"></Applied>
                        </div>
                    </div>
                </v-col>
                <v-col cols="6">
                    <v-card v-if="selectedJob">
                        <v-card-title :class="[{'bg-gray-300':selectedJob.job_active === 'Inaktív'},{'bg-green-500':selectedJob.job_active === 'Aktív'}]">
                            <div class="flex flex-col">
                                <span class="font-weight-black">{{selectedJob.job_name}}</span>
                                <span class="font-weight-medium">{{ selectedJob.job_cname }}</span>
                            </div>
                        </v-card-title>
                        <v-card-subtitle class="mt-3">
                            <div class="flex justify-between mb-1">
                                <span>{{selectedJob.job_catname}} / {{ selectedJob.job_scatname }}</span>
                                <span class="font-weight-black">{{divideAddress}}</span>
                            </div>
                            <v-divider class="border-opacity-100" :thickness="2"></v-divider>
                        </v-card-subtitle>
                        <v-card-text>
                            <div class="flex flex-col mb-2">
                                <span>{{selectedJob.job_description}}</span>
                            <v-divider class="border-opacity-100" :thickness="2"></v-divider>

                                <span>{{selectedJob.job_requirements}}</span>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </AuthenticatedLayout>
</template>