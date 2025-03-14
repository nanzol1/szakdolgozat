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
    min_spayment:{
        type:Object,
    },
    max_spayment:{
        type:Object,
    },
    work_schedules:{
        type:Object,
    },
    employment_type:{
        type:Object,
    },
});

const currentPage = ref(props.jobs.current_page);
const totalPages = ref(props.jobs.last_page);
const search = ref(props.filters.search || '');
const category = ref(props.filters.category || []);
const subcategory = ref(props.filters.subcategory || []);
const work_schedules = ref(props.filters.work_schedules || []);
const employment_type = ref(props.filters.employment_type || []);
const payment = ref([props.filters.min_payment || props.min_spayment.payment,props.filters.max_payment || props.max_spayment.payment])
const isFilterShow = ref(false);
const isCategoriesShow = ref(false);
const isWorkSchedulesShown = ref(false);
const isEmploymentTypeShown = ref(false);
const hasSlideUsed = ref(false);


watch([search,category,subcategory,payment,employment_type,work_schedules], () => {
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

const sliderHasBeenUsed = () => {
    return hasSlideUsed.value = true;
};

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

    if(work_schedules.value.length > 0){
        params.work_schedules = work_schedules.value;
    }

    if(employment_type.value.length > 0){
        params.employment_type = employment_type.value;
    }

    if(payment.value.length > 0 && hasSlideUsed.value === true){
        params.min_payment = payment.value[0];
        params.max_payment = payment.value[1];
    }

    try{
        router.get(route('jobs.show'),params, {preserveState: true, replace:true,preserveScroll:true,only: ["jobs"]});
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
    work_schedules.value = [];
    employment_type.value = [];
    currentPage.value = 1;
    payment.value = [props.min_spayment.payment, props.max_spayment.payment]; 
    hasSlideUsed.value = false;


    try{
        router.get(route('jobs.show'), {
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
const getSubCategories = (cats) => {
    return cats.flatMap(a => [{id:a.subcat_id,name:a.subcat_name}]);
};
const handleCategories = () => {
    if(isCategoriesShow.value === false){
        isCategoriesShow.value = true;
    }else{
        isCategoriesShow.value = false;
    }
};
const handleWorkSchedules = () => {
    if(isWorkSchedulesShown.value === false){
        isWorkSchedulesShown.value = true;
    }else{
        isWorkSchedulesShown.value = false;
    }
};

const handleEploymentType = () => {
    if(isEmploymentTypeShown.value === false){
        isEmploymentTypeShown.value = true;
    }else{
        isEmploymentTypeShown.value = false;
    }
};
watch(() => props.jobs, (newJobs) => {
    totalPages.value = newJobs.last_page;
});

</script>

<template>
    <Head title="Aktív állások"></Head>
    <GuestPageLayout>
        <div class="flex flex-col w-full">
            <v-container fluid class="pa-0 shadow-md relative hero_section">
                <v-img position="center" src='storage/images/munkak.jpg' class="!z-10" aspect-ratio="16/9" absolute cover width="100%" height="100%"></v-img>
                <v-container>
                    <v-row>
                        <v-col>
                            <div class="relative !z-20 flex flex-col items-center py-8 h-auto text-stone-950 w-full">
                                <div class="flex flex-col max-w-7xl items-center">
                                    <div class="mx-auto text-h4">
                                        Jelenleg is aktív munkáink
                                    </div>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </v-container>
            <v-container class="pt-6">
            <v-row>
                <v-col xl="12" class="break-words">
                    <div class="flex xl:flex-row flex-col w-full">
                        <div class="w-full xl:w-1/5 mr-5 pr-5">
                            <v-tooltip text="Szűrők megjelenítése/elrejtése" location="top">
                                <template v-slot:activator="{ props }">
                                    <v-btn v-bind="props" variant="tonal" @click="handleFilterShow" :class="['mr-xl-3 !text-stone-50 !bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105 mb-5',{'!bg-[#3A2618]':isFilterShow}]">
                                        Szűrők
                                    </v-btn>
                                </template>
                            </v-tooltip>
                            <v-btn variant="tonal" @click="startFilters" :class="['ml-3 !text-stone-50 !bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105 mb-5']">
                                    Szűrés
                            </v-btn>
                            <v-expand-transition>
                                <div v-if="isFilterShow">
                                    <v-text-field label="Keresés..." v-model="search"></v-text-field>
                                    <v-btn variant="tonal" @click="handleCategories" :class="['mr-3 !text-stone-50 !bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105 mb-5',{'!bg-[#3A2618]':isCategoriesShow}]">
                                        Kategóriák
                                    </v-btn>
                                    <transition-group tag="div" name="categories" mode="out-in">
                                    <div v-for="cats in categories" :key="cats.mid"  class="mb-5 align-center" v-if="isCategoriesShow">
                                        <div class="flex align-center">
                                            <label :for="cats.mid" class="cursor-pointer">{{ cats.name }}</label>
                                            <Checkbox v-model:checked="category" :value="cats.mid" :id="cats.mid" class="ml-2 hidden"></Checkbox>
                                            <label :for="cats.mid" class="mdi mdi-arrow-down cursor-pointer text-xl ml-2"></label>
                                        </div>
                                        <transition-group tag="div" name="checkboxes">
                                            <div v-for="subcat in getSubCategories(cats.subcategories)" :key="subcat.id" class="mt-3" v-if="category.includes(cats.mid)">
                                                <label :for="'s'+subcat.id">{{ subcat.name }}</label>
                                                <Checkbox v-model:checked="subcategory" :value="subcat.id" :id="'s'+subcat.id" class="ml-2"></Checkbox>
                                            </div>
                                        </transition-group>
                                    </div>
                                    </transition-group>
                                    <v-btn variant="tonal" @click="handleWorkSchedules" :class="['mr-3 !text-stone-50 !bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105 mb-5',{'!bg-[#3A2618]':isWorkSchedulesShown}]">
                                        Munkarend
                                    </v-btn>
                                    <transition-group tag="div" name="categories" mode="out-in">
                                    <div v-for="ws in props.work_schedules" :key="ws.id"  class="mb-5" v-if="isWorkSchedulesShown">
                                        <label :for="ws.id">{{ ws.name }}</label>
                                        <Checkbox v-model:checked="work_schedules" :value="ws.id" :id="ws.id" class="ml-2"></Checkbox>
                                    </div>
                                    </transition-group>
                                    <v-btn variant="tonal" @click="handleEploymentType" :class="['mr-3 !text-stone-50 !bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105 mb-5',{'!bg-[#3A2618]':isEmploymentTypeShown}]">
                                        Foglalkoztatottság
                                    </v-btn>
                                    <transition-group tag="div" name="categories" mode="out-in">
                                    <div v-for="et in props.employment_type" :key="et.id"  class="mb-5" v-if="isEmploymentTypeShown">
                                        <label :for="et.id">{{ et.name }}</label>
                                        <Checkbox v-model:checked="employment_type" :value="et.id" :id="et.id" class="ml-2"></Checkbox>
                                    </div>
                                    </transition-group>
                                      <label for="payment-slider">Fizetési tartomány</label>
                                    <v-range-slider
                                      v-model="payment"
                                      :min="props.min_spayment.payment"
                                      :max="props.max_spayment.payment"
                                      step="50"
                                      class="align-center mt-8"
                                      hide-details
                                      thumb-label="always"
                                      elevation="8"
                                      strict
                                      id="payment-slider"
                                      color="#c7a07a"
                                      @update:model-value="sliderHasBeenUsed"
                                    >
                                    </v-range-slider>
                                    <v-tooltip text="Szűrők alaphelyzetbe állítása">
                                        <template v-slot:activator="{ props }">
                                            <v-btn v-bind="props" variant="tonal" @click="resetFilters" :class="['mr-3 !text-stone-50 !bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105 mb-5']">
                                                Alaphelyzet
                                            </v-btn>
                                        </template>
                                    </v-tooltip>
                                </div>
                            </v-expand-transition>    
                        </div>
                        <div class="w-full xl:w-1/2 xl:flex-1 max-xl:mt-5">
                                <div v-for="job in jobs.data" :key="job.id">
                                    <Link :href="route('jobs.view',{id:job.id})">
                                        <v-hover v-slot="{isHovering,props}">
                                        <v-card
                                          :class="['mb-5 !transition-all',{'!bg-[#c7a07a]':isHovering}]"
                                          :subtitle="job.jobs_category.name + ' / ' + job.jobs_subcategory.name"
                                          elevation="2"
                                          v-bind="props"
                                        >
                                            <template v-slot:prepend >
                                                <v-avatar v-if="job.companies.profile_pict" :image="'/storage/uploads/company_profile/'+job.created_by+'/'+job.companies.profile_pict"></v-avatar>
                                                <v-icon v-else icon="mdi-briefcase"></v-icon>
                                            </template>
                                            <template v-slot:append>
                                                <v-icon icon="mdi mdi-open-in-new" color="black"></v-icon>
                                            </template>
                                          <template v-slot:title>
                                            <span class="font-weight-black">{{ job.companies.company_name}}</span>
                                          </template>
                                          <v-card-subtitle class="opacity-100">
                                            <span class="font-weight-black">{{ job.name }}</span>
                                          </v-card-subtitle>
                                          
                                      
                                          <v-card-text :class="['bg-surface-light pt-4 max-h-28 overflow-hidden text-ellipsis']" id="job-desc-1">
                                            {{ job.description }}
                                          </v-card-text>
                                        </v-card>
                                    </v-hover>
                                    </Link>
                                </div>
                            <v-pagination v-if="totalPages > 1" v-model="currentPage" active-color="blue" ellipsis="..." :length="totalPages" @update:model-value="fetchJobs"></v-pagination>
                            <div class="mt-3">
                                <v-btn variant="tonal" @click="startFilters" :class="['mr-3 !text-stone-50 !bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105 mb-5']">
                                    Szűrés
                                </v-btn>
                                <v-tooltip text="Szűrők alaphelyzetbe állítása">
                                    <template v-slot:activator="{ props }">
                                        <v-btn v-bind="props" variant="tonal" @click="resetFilters" :class="['mr-3 !text-stone-50 !bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105 mb-5']">
                                            Alaphelyzet
                                        </v-btn>
                                    </template>
                                </v-tooltip>
                            </div>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </v-container>
        </div>
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
  transform: translateX(-100%);
}
</style>