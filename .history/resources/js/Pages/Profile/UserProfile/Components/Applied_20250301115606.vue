<script setup>
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, ref, watch } from 'vue';

const props = defineProps({
    isShort:{
        type:Boolean,
    },
    myjobs:{
        type:Object,
    }
});
const headers = ref([
    {title:'Megnevezés',align:'start',sortable:true,key:'name'},
    {title:'Kategória',align:'start',sortable:true,key:'subcat'},
    {title:'Állapot',align:'start',sortable:true,key:'status'},
    {title:'Aktív',align:'start',sortable:true,key:'active'},
]);


const items = computed(() => {
    return Object.values(props.myjobs.data || {}).map(job => ({
        id:job.job_id,
        name:job.job_name,
        subcat:job.job_scatname,
        status:job.job_status_name,
        active:job.job_active,
    }));
});
const itemsPerPage = ref(props.myjobs.per_page);
const isShort = ref(props.isShort || false);
const itemsTotal = ref(props.myjobs.total);
const loading = ref(false);
const currentPage = ref(props.myjobs.current_page);

const loadItems = async({page,itemsPerPage}) => {
    loading.value = true;
    const response = await axios.get(route("applied.jobs.load"),
    {
        params:{
            page: currentPage.value, 
            perPage:itemsPerPage.value
        }
    }, 
    {
        preserveState: true,
        preserveScroll: true,
        replace:true,
        only: ["myjobs"],
    });

    console.log(response);
    currentPage.value = response.data.list.current_page;
};

watch([currentPage, itemsPerPage], ([newPage, newPerPage]) => {
    loadItems({page:newPage,itemsPerPage:newPerPage});
});
</script>

<template>
    {{ props.myjobs }}
    <div v-if="isShort">
        <div class="mb-3">
            Munkák amire jelentkeztem (rövid)
        </div>
        <template v-for="job in myjobs.data" :key="job.id">
            <v-row>
                <v-col cols="12">
                    <v-data-table-server :loading="loading" @update:options="loadItems" v-model:page="currentPage" v-model:items-per-page="itemsPerPage" :items-length="itemsTotal" :headers="headers" :items="items"></v-data-table-server>
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
                    <template v-for="job in myjobs.data" :key="job.id">
                        <div class="mb-3">
                            {{ job }}
                        </div>
                    </template>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>