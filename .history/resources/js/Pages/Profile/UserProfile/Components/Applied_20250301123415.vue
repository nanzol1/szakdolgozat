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
        subcat:job.job_cname,
        status:job.job_status_name,
        active:job.job_active,
    }));
});
const itemsPerPage = ref(props.myjobs.per_page);
const isShort = ref(props.isShort || false);
const itemsTotal = ref(props.myjobs.total);
const loading = ref(false);
const currentPage = ref(props.myjobs.current_page);

const loadItems = async() => {
    loading.value = true;
    try{
        const response = await axios.get(route("applied.jobs.load"),
        {
            params:{
                page: currentPage.value, 
                perPage:itemsPerPage.value,
                sortBy: sortBy.value[0]?.key || 'id',
                sortDesc: sortDesc.value[0]?.order === 'desc' ? 'desc' : 'asc'
            }
        }, 
        {
            preserveState: true,
            preserveScroll: true,
            replace:true,
            only: ["myjobs"],
        });
        props.myjobs.data = response.data.list.data;
        itemsTotal.value = response.data.list.total;
        loading.value = false;
    }catch (error){
        console.error(error);
    }
};

watch([sortBy, sortDesc], () => {
    currentPage.value = 1;
    loadItems();
});
</script>

<template>
    <div v-if="isShort">
        <div class="mb-3">
            Munkák amire jelentkeztem
        </div>
        <v-row>
            <v-col cols="12">
                <v-data-table-server v-model:sort-by="sortBy" v-model:sort-desc="sortDesc" :loading="loading" @update:options="loadItems" v-model:page="currentPage" v-model:items-per-page="itemsPerPage" :items-length="itemsTotal" :headers="headers" :items="items"></v-data-table-server>
            </v-col>
        </v-row>
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