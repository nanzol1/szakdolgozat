<script setup>
import Footer from '@/Components/Footer.vue';
import NavBar from '@/Components/NavBar.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';
import { useAlertStore } from '@/store';

const alertStore = useAlertStore();

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    featured_jobs:{
        type:Object,
    },
    jobs:{
        type:Object,
    },
    filters:{
        type:Object,
    },
    categories:{
        type:Object,
    },
    min_spayment:{
        type:Object,
    },
    max_spayment:{
        type:Object,
    },
});

const model = ref(null);
const selectedJob = ref([]);
const currentPage = ref(props.jobs.current_page);
const totalPages = ref(props.jobs.last_page);
const filterShow = ref(false);
const search = ref(props.filters.search || '');
const category = ref(props.filters.category || []);
const payment = ref([props.filters.min_payment || props.min_spayment.payment,props.filters.max_payment || props.max_spayment.payment])
//const subcategory = ref(props.filters.subcategory || []);

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
watch([search, category,payment], () => {
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


const fetchJobs = async () => {
    const params = {
        page: currentPage.value,
    };

    if (search.value) {
        params.search = search.value;
    }
    if (category.value.length > 0) {
        params.category = category.value;
    }

    if(payment.value.length > 0){
        params.min_payment = payment.value[0];
        params.max_payment = payment.value[1];
    }

    await router.get(route("jobs.index"), params, {
        preserveState: true,
        preserveScroll: true,
        only: ["jobs"],
    });
};


const handleFilterShow = () => {
    filterShow.value = !filterShow.value;
};

watch(() => props.jobs, (newJobs) => {
    totalPages.value = newJobs.last_page;
});
const getCategoryItems = computed(() => {
  return Object.values(props.categories).map(cat => ({
    id: cat.mid,
    name: cat.name
  }));
});

onMounted(() => {
    const ids = props.filters.category?.map(id => parseInt(id));
    if(ids){
        category.value = ids;
    }
    if(props.filters.category?.some(item => item === 'NaN')){
        category.value = [];
    }

});
const setFiltersDefault = () => {
    search.value = "";
    category.value = []; 
    payment.value = [props.min_spayment.payment, props.max_spayment.payment]; 
    currentPage.value = 1; 

    try{
        router.get(route("jobs.index"), {
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
};
const startFilters = () => {
    try{
        fetchJobs();
        alertStore.showAlert('Sikeres szűrés','success');
    }catch(error){
        alertStore.showAlert('Sikertelen művelet','error');
    }
};

</script>

<template>
    <Head title="Kezdőlap" />
    <div class="bg-gray-50 text-black/100 dark:bg-black dark:text-white/50 pb-10">

        <NavBar :canLogin="canLogin" :canRegister="canRegister"></NavBar>

        <div
            class="relative flex min-h-screen flex-col items-center selection:bg-[#FF2D20] selection:text-white"
        >
            <div class="relative flex flex-col items-center py-8 h-auto bg-gray-100 dark:bg-gray-600 dark:text-gray-50 text-stone-950 w-full">
                <div class="flex flex-col max-w-7xl items-center">
                    <div class="mx-auto">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel justo sit amet leo interdum semper. Nulla vulputate sollicitudin augue, quis viverra tellus venenatis a. Duis sed lorem sit amet nisl iaculis tempor non et arcu. Curabitur pellentesque eu lacus ac efficitur. Etiam euismod enim at tincidunt fermentum.
                    </div>
                    <div class="lg:mt-4">
                        <v-btn variant="elevated" @click="router.visit(route('register',{type:'munkaltato'}))" class="!text-stone-50 !bg-gray-500 hover:!bg-sky-500 hover:scale-105">
                            Munkahirdetés
                        </v-btn>
                    </div>
                    <div class="lg:mt-5">
                        Esetleg munkát keresel?
                        <v-btn variant="elevated" @click="router.visit(route('jobs.show'))" class="!text-stone-50 !bg-gray-500 hover:!bg-sky-500 hover:scale-105">
                            Munkák böngészése
                        </v-btn>
                    </div>
                </div>
            </div>
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <main class="mt-6">
                    <div class="lg:grid flex flex-col">
                        <div class="lg:text-xl w-full mb-2 mt-4">
                            Ajánlott munkák
                        </div>
                        <div class="relative w-full overflow-hidden text-wrap md:mb-12 w-full" >
                            <v-sheet
                              elevation="8"
                              width="100%"
                              class="!bg-gray-50/0"
                            >
                              <v-slide-group
                                v-model="model"
                                class="pa-4"
                                show-arrows
                                center-active
                              >
                                <v-slide-group-item
                                  v-for="(featured, index) in featured_jobs"
                                  :key="featured.id"
                                  v-slot="{ isSelected, toggle, selectedClass }"
                                >
                                  <v-card
                                    :class="['ma-4 !bg-gray-300 lg:w-[335px] w-full md:w-[460px] hover:scale-105 transition']"
                                    height="200"
                                    @click="toggle(); selectedJob = featured;"
                                  >
                                    <Link :href="route('jobs.view',{id:featured.id})">
                                        <div class="relative flex flex-col h-full overflow-hidden bg-gray-50 max-md:w-[350px] max-sm:w-[200px]">
                                            <div class="featured-card-title flex-col bg-green-400 flex justify-between flex-wrap"><span>{{ featured.name }}</span><span>{{ featured.jobs_subcategory.name }}</span></div>
                                            <div class="mb-1 ml-2">Rövid leírás:</div>
                                            <div class="mb-3 ml-2 break-all text-wrap truncate h-12 max-sm:h-auto">{{ featured.description }}</div>
                                            <div class="absolute bottom-2 left-2 max-sm:hidden"><div v-if="featured.companies.profile_pict" class="w-8 h-8 rounded-xl overflow-hidden absolute left-0 bottom-0 max-sm:hidden"><img class="w-[100%] h-[100%] object-cover" :src="'storage/uploads/company_profile/'+featured.companies.id+'/'+featured.companies.profile_pict" alt="Profile picture"></div><div :class="['relative left-10 max-sm:left-0',{'left-0':!featured.companies.profile_pict}]">{{ featured.companies.company_name }}</div></div>
                                        </div>
                                    </Link>
                                  </v-card>
                                </v-slide-group-item>
                              </v-slide-group>
                            </v-sheet>
                        </div>
                    </div>
                    <div class="mb-3 lg:text-xl">
                        Jelenlegi munkák
                    </div>
                    <div>
                        További munkákért, és részletesebb szűrési lehetőségekért!
                    </div>
                    <div class="my-3">
                        <v-btn class="!text-stone-50 !bg-gray-500 hover:!bg-sky-500 hover:scale-105" @click="handleFilterShow">Szűrők</v-btn>
                        <v-btn class="!text-stone-50 !bg-gray-500 hover:!bg-sky-500 hover:scale-105 ml-3" @click="router.visit(route('jobs.show'))">Még több munka</v-btn>
                    </div>
                    <div class="flex max-lg:flex-col-reverse">
                        <div class="flex flex-col gap-6 sm:grid w-full lg:gap-2 sm:grid-cols-1">
                            <div class="w-full row-span-2" v-for="job in jobs.data" :key="job.id">
                                <Link :href="route('jobs.view',{id:job.id})">
                                    <v-card
                                        append-icon="mdi-open-in-new"
                                        class="mx-auto hover:bg-gray-300"
                                        width="100%"
                                        prepend-icon="mdi-briefcase"
                                        :subtitle="job.jobs_category.name + ' / ' + job.jobs_subcategory.name"
                                        :title="job.name"
                                        :text="job.description"
                                        link
                                    >
                                    </v-card>
                                </Link>
                            </div>
                            <v-pagination v-model="currentPage" :total-visible="5" :length="totalPages" @update:model-value="fetchJobs"></v-pagination>
                        </div>
                    <v-slide-x-reverse-transition>
                        <div v-if="filterShow" class="w-full lg:w-1/2 max-lg:my-3 lg:ml-10">
                            <v-text-field label="Név alapján" v-model="search"></v-text-field>
                            <v-combobox
                              v-model="category"
                              label="Válassz kategóriát"
                              :items="getCategoryItems"
                              clearable
                              chips
                              multiple
                              item-title="name"
                              item-value="id"
                              :return-object="false"
                            ></v-combobox>
                            <v-range-slider
                              v-model="payment"
                              :max="props.max_spayment.payment"
                              :min="props.min_spayment.payment"
                              step="1"
                              label="Fizetési tartomány"
                              class="align-center"
                              hide-details
                              thumb-label="always"
                            >
                            </v-range-slider>
                            <div class="mt-3">
                                <v-btn variant="tonal" @click="startFilters" class="mr-3 !text-stone-50 !bg-gray-500 hover:!bg-sky-500 hover:scale-105">Szűrés</v-btn>
                                <v-btn variant="tonal" @click="setFiltersDefault" class="!text-stone-50 !bg-gray-500 hover:!bg-sky-500 hover:scale-105">Alaphelyzetbe</v-btn>
                            </div>
                        </div>
                    </v-slide-x-reverse-transition>
                    </div>
                </main>
            </div>
        </div>
    </div>
    <Footer></Footer>
</template>
