<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import _ from 'lodash';

const searchQuery = ref('');
const searchResults = ref([]);

const props = defineProps({
    users:{
        type:Object,
    },
    search:{
        type:String,
    },
});

const debounceSearch = _.debounce(async () => {
    if(searchQuery.value.length >= 3){
        const response = await axios.get(route('admin.users.search'),{
            params: {search_name: searchQuery.value},
        });
        searchResults.value = response.data;
    }else{
        searchResults.value = [];
    }
},500);

</script>
<template>
    <Head title="Munkavállalók"></Head>
    <AdminLayout>
        <div class="dark:text-white">
            <template v-for="user in users">
                <div class="mb-3">
                    {{ user }}
                </div>
            </template>
        </div>
    </AdminLayout>
</template>