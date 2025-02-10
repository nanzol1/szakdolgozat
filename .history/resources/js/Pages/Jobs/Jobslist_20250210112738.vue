<script setup>

import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import Checkbox from '@/Components/Checkbox.vue';

const props = defineProps({
    jobs:{
        type:Object,
    },
    filters:{
        type:String,
    },
    categories:{
        type:Object,
    },
    subcategories:{
        type:Object,
    }
});

const search = ref(props.filters.search || '');
const category = ref(props.filters.category || []);
const subcategory = ref(props.filters.subcategory || '');

watch([search,category,subcategory], () => {
    router.get('munkak/',{
        search: search.value,
        category: category.value,
        subcategory: subcategory.value,
    }, {preserveState: true, replace:true});
});

watch(category, () => {
    subcategory.value = '';
});

const filteredSubcategories = computed(() => {
    if(category.value === ''){
        return Object.values(props.categories).flatMap(cat => cat.subcategories || []);
    }else{
        return props.categories[category.value]?.subcategories || [];
    }
});



</script>

<template>
    <Head title="Teszt"></Head>
    <div>
        Szűrők
    </div>
    <input type="text" v-model="search" placeholder="Keresés...">
    <Checkbox v-model="category" v-for="cats in categories"> {{ cat.name }}</Checkbox>
    <select v-model="subcategory">
        <option value="">Összes kategória</option>
        <option v-for="subcat in filteredSubcategories" :key="subcat.subcat_id" :value="subcat.subcat_id">
            {{ subcat.subcat_name }}
        </option>
    </select>
    <div v-for="job in jobs" :key="job.id">
        {{ job.name }}
        {{ job.active }}
        {{ job.position_name }}
        {{ job.description }}
        <Link :href="route('jobs.view',{id:job.id})">Érdeklődöm</Link>
    </div>
</template>