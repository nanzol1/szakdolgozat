<script setup>

import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

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
const category = ref(props.filters.category || '');
const subcategory = ref(props.filters.subcategory || '');

watch([search,category,subcategory], () => {
    router.get('munkak/',{
        search: search.value,
        category: category.value,
        subcategory: subcategory.value,
    }, {preserveState: true, replace:true});
});




</script>

<template>
    <Head title="Teszt"></Head>
    <div class="bock">
        Szűrők
    </div>
    <input type="text" v-model="search" placeholder="Keresés...">
    <select v-model="category">
      <option value="">Összes kategória</option>
      <option v-for="cat in categories" :key="cat.mid" :value="cat.mid">
        {{ cat.name }}
      </option>
    </select>
    <select v-model="subcategory">
        <template v-for="cats in categories" :key="cats.mid">
            <option value="">Összes kategória</option>
            <template v-if="cats.mid == subcat.id">
                <option v-for="subcat in cats.subcategories" :key="subcat.subcat_id" :value="subcat.subcat_id">
                    {{ subcat.subcat_name }}
                </option>
            </template>
        </template>
    </select>
    <div v-for="job in jobs" :key="job.id">
        {{ job.name }}
        {{ job.active }}
        {{ job.position_name }}
        {{ job.description }}
        <Link :href="route('jobs.view',{id:job.id})">Érdeklődöm</Link>
    </div>
</template>