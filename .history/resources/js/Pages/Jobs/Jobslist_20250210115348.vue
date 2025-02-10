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
const category = ref([]);
const subcategory = ref([]);

watch([search,category,subcategory], () => {
    router.get('munkak/',{
        search: search.value,
        category: category.value.length > 0 ? category.value : null,
        subcategory: subcategory.value.length > 0 ? subcategory.value : null,
    }, {preserveState: true, replace:true});
});

watch(category, () => {
    subcategory.value = '';
});

const filteredSubcategories = computed(() => {
    if(category.value === 0){
        return Object.values(props.categories).flatMap(cat => cat.subcategories || []);
    }else{
        return category.value.flatMap(id => props.categories[id]?.subcategories || []);
    }
});



</script>

<template>
    <Head title="Teszt"></Head>
    <div>
        Szűrők
    </div>
    <input type="text" v-model="search" placeholder="Keresés...">
    <template v-for="cats in categories" :key="cats.mid">
        <label :for="cats.mid">{{ cats.name }}</label>
        <Checkbox v-model:checked="category" :value="cats.mid" :id="cats.mid"></Checkbox>
    </template>
    <template v-for="subcat in filteredSubcategories" :key="subcat.subcat_id">
        <label :for="subcat.id">{{ subcat.name }}</label>
        <Checkbox v-model:checked="subcategory" :value="subcat.subcat_id" :id="subcat.subcat_id"></Checkbox>
    </template>
    ss {{ Object.values(props.categories).flatMap(cat => cat) }}
    <div v-for="job in jobs" :key="job.id">
        {{ job.name }}
        {{ job.active }}
        {{ job.position_name }}
        {{ job.description }}
        <Link :href="route('jobs.view',{id:job.id})">Érdeklődöm</Link>
    </div>
</template>