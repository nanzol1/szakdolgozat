<script setup>
import axios from 'axios';
import { computed, ref, watch, defineEmits, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

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
    {title:'Aktív',align:'start',sortable:true,key:'active'},
    {title:'Művelet',align:'start',sortable:false,key:'action'},
]);


const items = computed(() => {
    return Object.values(props.myjobs.data || {}).map(job => (console.log(job),{
        id:job.id,
        name:job.name,
        subcat:job.jobs_subcategory.name ?? '',
        active:job.active ? 'Aktív' : 'Inaktív',
    }));
});
const itemsPerPage = ref(props.myjobs.per_page);
const isShort = ref(props.isShort || false);
const itemsTotal = ref(props.myjobs.total);
const loading = ref(false);
const currentPage = ref(props.myjobs.current_page);
const sortBy = ref([]);
const sortDesc = ref([]);
const selectedJob = ref(null);

const loadItems = async() => {
    loading.value = true;
    try{
        const response = await axios.get(route("advertised.jobs.load"),
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

onMounted(() => {
    const query = window.location.search;
    const URL = new URLSearchParams(query);
    if(URL){
        selectedJob.value = props.myjobs.data.find(element => element.job_id === Number(URL.get('id')));
    }
});
</script>

<template>
    <div v-if="isShort">
        <div class="mb-3">
            Munkák amire jelentkeztem
        </div>
        <v-row>
            <v-col cols="12">
                <v-data-table-server v-model:sort-by="sortBy" v-model:sort-desc="sortDesc" :loading="loading" @update:options="loadItems" v-model:page="currentPage" v-model:items-per-page="itemsPerPage" :items-length="itemsTotal" :headers="headers" :items="items">
                    <template #item.active="{ item }">
                        <v-chip :color="item.active === 'Aktív' ? 'green' : 'gray'">
                            {{ item.active }}
                        </v-chip>
                    </template>
                    <template #item.action="{ item }">
                        <v-btn
                            @click="router.visit(route('profile.myjobs', { id: item.id }))"
                            color="success"
                            size="small"
                            variant="outlined"
                            prepend-icon="mdi-eye"
                        >
                            Megtekintés
                        </v-btn>
                    </template>
                </v-data-table-server>
            </v-col>
        </v-row>
    </div>
    <div v-else>
        <v-row>
            <v-col cols="12">
                <v-data-table-server v-model:sort-by="sortBy" v-model:sort-desc="sortDesc" :loading="loading" @update:options="loadItems" v-model:page="currentPage" v-model:items-per-page="itemsPerPage" :items-length="itemsTotal" :headers="headers" :items="items">
                    <template #item.active="{ item }">
                        <v-chip :color="item.active === 'Aktív' ? 'green' : 'gray'">
                            {{ item.active }}
                        </v-chip>
                    </template>
                    <template #item.action="{ item }">
                        <v-btn
                            @click="showSelectedJob(item.id)"
                            size="small"
                            variant="outlined"
                        >
                            {{selectedJob && selectedJob.job_id === item.id ? 'Kiválasztva' : 'Megtekintés'}}
                        </v-btn>
                    </template>
                </v-data-table-server>
            </v-col>
        </v-row>
    </div>
</template>