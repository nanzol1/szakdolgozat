<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import _ from 'lodash';
const props = defineProps({
    companies:{
        type:Object,
    },
    search:{
        type:Array,
    },
});

const searchQuery = ref(props.search.search || '');

//Késleltetett keresés gyors gépelés ellen
const debounceSearch = _.debounce(async () => {
    router.get('/admin/companies/results',{
        search: searchQuery.value,
    },{preserveState:true,replace:true});
},500);

watch(searchQuery,() =>{
    debounceSearch();
});

</script>
<template>
    <Head title="Munkavállalók"></Head>
    <AdminLayout>
        <div class="dark:text-white">
            <input type="text" v-model="searchQuery" placeholder="Keresés..." class="dark:text-black">
            <template v-for="company in companies">
                <div class="mb-3">
                    {{ company }}
                    <Link :href="route('admin.company.show',{id:company.id})">Megnézem</Link>
                </div>
            </template>
        </div>
    </AdminLayout>
</template>