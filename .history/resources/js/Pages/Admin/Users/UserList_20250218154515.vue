<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import _ from 'lodash';
const props = defineProps({
    users:{
        type:Object,
    },
    search:{
        type:Array,
    },
});

const searchQuery = ref(props.search.search || '');

//Késleltetett keresés gyors gépelés ellen
const debounceSearch = _.debounce(async () => {
    router.get('/admin/users/results',{
        search: searchQuery.value,
    },{preserveState:true,replace:true});
},500);

watch(searchQuery,(e) =>{
    debounceSearch();
});

</script>
<template>
    <Head title="Munkavállalók"></Head>
    <AdminLayout>
        <div class="dark:text-white">
            <input type="text" v-model="searchQuery" placeholder="Keresés..." class="dark:text-black">
            <template v-for="user in users">
                <div class="mb-3">
                    {{ user }}
                </div>
            </template>
        </div>
    </AdminLayout>
</template>