<script setup>

import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import Checkbox from '@/Components/Checkbox.vue';
import GuestPageLayout from '@/Layouts/GuestPageLayout.vue';
import { useAlertStore } from '@/store';

const alertStore = useAlertStore();

const props = defineProps({
    jobs:{
        type:Object,
    },
    filters:{
        type:Array,
    },
    categories:{
        type:Object,
    },
    subcategories:{
        type:Object,
    },
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

const currentPage = ref(props.jobs.current_page);
const search = ref(props.filters.search || '');
const category = ref([]);
const subcategory = ref([]);

watch([search,category,subcategory], () => {
    currentPage.value = 1;
    if(category.value.length > 0){
        Object.values(category.value).forEach(element => {
            const catValue = parseInt(element);
            if(catValue.toString() === 'NaN'){
                category.value = [];
            }
        });
    }
});


const filteredSubcategories = computed(() => {
    if(category.value === 0){
        return Object.values(props.categories).flatMap(cat => cat.subcategories || []);
    }else{
        return category.value.flatMap(id => props.categories[id]?.subcategories || []);
    }
});

const fetchJobs = () => {
    try{
        router.get('munkak/',{
            search: search.value,
            category: category.value.length > 0 ? category.value : null,
            subcategory: subcategory.value.length > 0 ? subcategory.value : null,
        }, {preserveState: true, replace:true});
        alertStore.showAlert('Sikeres szűrés!','success');
    }catch(error){
        alertStore.showAlert('Sikertelen művelet!','error');
        console.error(error);
    }

};



</script>

<template>
    <Head title="Aktív állások"></Head>
    <GuestPageLayout :canLogin="canLogin" :canRegister="canRegister">
        <div>
            Szűrők
        </div>
        <input type="text" v-model="search" placeholder="Keresés...">
        <template v-for="cats in categories" :key="cats.mid">
            <label :for="cats.mid">{{ cats.name }}</label>
            <Checkbox v-model:checked="category" :value="cats.mid" :id="cats.mid"></Checkbox>
        </template>
        <template v-for="subcat in filteredSubcategories" :key="subcat.subcat_id">
            <label :for="subcat.subcat_keyid">{{ subcat.subcat_name }}</label>
            <Checkbox v-model:checked="subcategory" :value="subcat.subcat_id" :id="subcat.subcat_keyid"></Checkbox>
        </template>
        <div v-for="job in jobs" :key="job.id">
            {{ job.name }}
            {{ job.active }}
            {{ job.position_name }}
            {{ job.description }}
            <Link :href="route('jobs.view',{id:job.id})">Érdeklődöm</Link>
        </div>
        <v-pagination v-model="currentPage" active-color="blue" ellipsis="..." :length="totalPages" @update:model-value="fetchJobs"></v-pagination>

    </GuestPageLayout>
</template>