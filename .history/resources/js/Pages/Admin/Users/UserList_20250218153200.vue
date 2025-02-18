<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
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
const searchResults = ref([]);

const debounceSearch = _.debounce(async () => {
    if(searchQuery.value.length >= 3){
        const response = await axios.get('/admin/users/results',{
            params: {searchResults: searchQuery.value},
        });
        console.log(searchResults);
        searchResults.value = response.data;
    }else{
        searchResults.value = [];
    }
},500);

watch(searchQuery,(e) =>{
    debounceSearch(e);
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