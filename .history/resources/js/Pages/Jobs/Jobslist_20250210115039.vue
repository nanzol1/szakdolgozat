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




</script>

<template>
    <Head title="Teszt"></Head>
    <div>
        Szűrők
    </div>
    <input type="text" v-model="search" placeholder="Keresés...">
    <template v-for="cats in categories" :key="cats.mid">
        <Checkbox v-model:checked="category" :value="cats.mid"></Checkbox>
    </template>
    <template v-for="subcat in subcategories" :key="subcat.subcat_id">
        <Checkbox v-model:checked="subcategory" :value="subcat.subcat_id"></Checkbox>
    </template>
    <div v-for="job in jobs" :key="job.id">
        {{ job.name }}
        {{ job.active }}
        {{ job.position_name }}
        {{ job.description }}
        <Link :href="route('jobs.view',{id:job.id})">Érdeklődöm</Link>
    </div>
</template>