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
        type:Object,
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
    min_spayment:{
        type:Object,
    },
    max_spayment:{
        type:Object,
    },
});

const currentPage = ref(props.jobs.current_page);
const totalPages = ref(props.jobs.total);
const search = ref(props.filters.search || '');
const category = ref(props.filters.category || []);
const subcategory = ref(props.filters.subcategory || []);
const payment = ref([props.filters.min_payment || props.min_spayment.payment,props.filters.max_payment || props.max_spayment.payment])
const isFilterShow = ref(false);
const isCategoriesShow = ref(false);


watch([search,category,subcategory,payment], () => {
    currentPage.value = 1;
    if(category.value.length > 0){
        Object.values(category.value).forEach(element => {
            const catValue = parseInt(element);
            if(catValue.toString() === 'NaN'){
                category.value = [];
            }
        });
    }
},{deep:true});

const filteredSubcategories = computed(() => {
    if(category.value === 0){
        return Object.values(props.categories).flatMap(cat => cat.subcategories || []);
    }else{
        return category.value.flatMap(id => props.categories[id]?.subcategories || []);
    }
});

const fetchJobs = async () => {
    const params = {
        page: currentPage.value,
    };

    if(search.value){
        params.search = search.value;
    }
    if(category.value.length > 0){
        params.category = category.value;
    }

    if(subcategory.value.length > 0){
        params.subcategory = subcategory.value;
    }

    if(payment.value.length > 0){
        params.min_payment = payment.value[0];
        params.max_payment = payment.value[1];
    }

    try{
        router.get('munkak/',params, {preserveState: true, replace:true,preserveScroll:true,only: ["jobs"]});
        alertStore.showAlert('Sikeres szűrés!','success');
    }catch(error){
        alertStore.showAlert('Sikertelen művelet!','error');
        console.error(error);
    }

};

const startFilters = () => {
    fetchJobs();
}
const resetFilters = () => {
    search.value = '';
    category.value = [];
    subcategory.value = [];
    currentPage.value = 1;
    payment.value = [props.min_spayment.payment, props.max_spayment.payment]; 


    try{
        router.get('munkak/', {
            page: currentPage.value,
            }, {
            preserveState: true,
            preserveScroll: true,
            only: ["jobs"],
        });
        alertStore.showAlert('Szűrők sikeresen alaphelyzetbe állítva','success');  
    }catch(error){
        alertStore.showAlert('Sikertelen művelet','error');  

    }
}
const handleFilterShow = () => {
    if(isFilterShow.value === false){
        isFilterShow.value = true;
    }else{
        isFilterShow.value = false;
    }
}
const asd = (asd) => {
    return asd.flatMap(a => [{id:a.subcat_id,name:a.subcat_name}]);
};
const handleCategories = () => {
    if(isCategoriesShow.value === false){
        isCategoriesShow.value = true;
    }else{
        isCategoriesShow.value = false;
    }
};

</script>

<template>
    <Head title="Aktív állások"></Head>
    <GuestPageLayout :canLogin="canLogin" :canRegister="canRegister">
            <v-col xl="12" class="break-words">
                <div class="flex w-full">
                    <div class="w-1/5 mr-5 pr-5">
                        <v-btn variant="tonal" @click="handleFilterShow" class="mr-3 !text-stone-50 !bg-gray-500 hover:!bg-sky-500 hover:scale-105 mb-3">
                            Szűrők Megjelenítése
                        </v-btn>
                        <v-expand-transition>
                            <div v-if="isFilterShow">
                                <v-text-field label="Keresés..." v-model="search"></v-text-field>
                                <v-btn variant="tonal" @click="handleCategories" class="mr-3 !text-stone-50 !bg-gray-500 hover:!bg-sky-500 hover:scale-105 mb-5">
                                    Kategóriák
                                </v-btn>
                                <transition-group tag="div" name="categories" mode="out-in">
                                <div v-for="cats in categories" :key="cats.mid"  class="mb-5" v-if="isCategoriesShow">
                                    <label :for="cats.mid">{{ cats.name }}</label>
                                    <Checkbox v-model:checked="category" :value="cats.mid" :id="cats.mid"></Checkbox>
                                    <transition-group tag="div" name="checkboxes">
                                        <div v-for="subcat in asd(cats.subcategories)" :key="subcat.id" v-if="category.includes(cats.mid)">
                                            <label :for="'s'+subcat.id">{{ subcat.name }}</label>
                                            <Checkbox v-model:checked="subcategory" :value="subcat.id" :id="'s'+subcat.id"></Checkbox>
                                        </div>
                                    </transition-group>
                                </div>
                                </transition-group>
                                <v-range-slider
                                  v-model="payment"
                                  :max="props.max_spayment.payment"
                                  :min="props.min_spayment.payment"
                                  step="1000"
                                  class="align-center"
                                  hide-details
                                  thumb-label="always"
                                  elevation="8"
                                  thumb-color="blue"
                                  track-color="black"
                                  track-fill-color="blue"
                                  width="100%"
                                >
                                </v-range-slider>
                            </div>
                        </v-expand-transition>    
                    </div>
                    <div class="w-1/2 flex-1">
                        <div v-for="job in jobs" :key="job.id" class="">
                           {{ job.name }}
                           {{ job.active }}
                           {{ job.position_name }}
                            <p>{{ job.description }}</p>
                            <Link :href="route('jobs.view',{id:job.id})">Érdeklődöm</Link>
                        </div>
                        <v-pagination v-model="currentPage" active-color="blue" ellipsis="..." :length="totalPages" @update:model-value="fetchJobs"></v-pagination>
                        <button @click="startFilters">Szűrés</button>
                        <button @click="resetFilters">Alaphelyzet</button>
                    </div>
                </div>
            </v-col>
    </GuestPageLayout>
</template>
<style scoped>
.checkboxes-enter-active, .checkboxes-leave-active {
  transition: all 0.5s ease;
}
.checkboxes-enter-from, .checkboxes-leave-to {
  opacity: 0;
}

.categories-enter-active, .categories-leave-active {
  transition: all 0.5s ease;
}
.categories-enter-from, .categories-leave-to {
  opacity: 0;
  transform: translateY(-100%);
}
</style>