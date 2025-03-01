<script setup>
import { ref } from 'vue';

const props = defineProps({
    isShort:{
        type:Boolean,
    },
    myjobs:{
        type:Object,
    }
});
const headers = ref([
    {title:'Megnevezés',align:'start',sortable:true,key:'name'}
]);
const items = ref({
    name:Object.values(props.myjobs)?.map(e => e.job_name),
});

const isShort = ref(props.isShort || false);
</script>

<template>
    {{ items }}
    <div v-if="isShort">
        <div class="mb-3">
            Munkák amire jelentkeztem (rövid)
        </div>
        <template v-for="job in myjobs" :key="job.id">
            <v-row>
                <v-col cols="12">
                    <v-data-table-server :headers="headers"></v-data-table-server>
                    <div class="flex justify-between gap-2 lg:gap-3">
                        <div class="flex gap-2 lg:gap-3">
                            <div>{{ job.job_name }}</div>
                            <div>{{ job.job_scatname }}</div>
                        </div>
                        <div class="flex gap-2 lg:gap-3">
                            <div>{{ job.job_status_name }}</div>
                            <div>{{ job.job_active }} <v-icon icon="mdi mdi-circle-medium" :color="job.job_active === 1 ? 'success' : '#9c9c9c'"></v-icon> </div>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </template>
    </div>
    <div v-else>
        <v-container>
            <v-row>
                <v-col cols="12">
                    <div class="mb-3">
                    Munkák amire jelentkeztem (teljes)
                    </div>
                    <template v-for="job in myjobs" :key="job.id">
                        <div class="mb-3">
                            {{ job }}
                        </div>
                    </template>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>