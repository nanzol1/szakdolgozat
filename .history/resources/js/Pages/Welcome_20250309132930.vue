<script setup>
import Footer from '@/Components/Footer.vue';
import NavBar from '@/Components/NavBar.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';
import { useAlertStore } from '@/store';
import { NCarousel, NCard } from 'naive-ui'


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
    most_viewed:{
        type:Object,
    },
    new_jobs:{
        type:Object,
    }
});

const currentPage = ref(props.jobs.current_page);
const totalPages = ref(props.jobs.last_page);
const filterShow = ref(false);
const search = ref(props.filters.search || '');
const category = ref(props.filters.category || []);
const payment = ref([props.filters.min_payment || props.min_spayment.payment,props.filters.max_payment || props.max_spayment.payment])

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

const computePostDates = computed(() => {
    return Object.values(props.jobs.data || {})?.map(e => {
    const posted = new Date(e.created_at);
    const today = new Date();
    
    today.setHours(0, 0, 0, 0);
    posted.setHours(0, 0, 0, 0);

    const diffMs = today - posted;
    const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));

    const formattedDate = posted.toLocaleDateString('hu-HU', {
      year: 'numeric',
      month: '2-digit',
      day: '2-digit'
    });

        return `${formattedDate} (${diffDays} nappal ezelőtt)`;
    });
});

const availWidth = ref(window.innerWidth);
const updateWidth = () => {
    availWidth.value = window.innerWidth;
};

console.log(props.jobs.data.length);
const computedSlidesPerView = computed(() => {
    if(availWidth.value <= 576){
        return 1;
    }else if(availWidth.value > 577 && availWidth.value <= 768 || props.jobs.data.length <= 2){
        return 2;
    }else if(availWidth.value > 768 && availWidth.value <= 1200 || props.jobs.data.length <= 2){
        return 2;
    }else{
        return 3;
    }
});

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
    if(props.filters.min_payment){
        payment.value = [props.filters.min_payment,props.max_spayment];
    }else if(props.filters.max_payment){
        payment.value = [0,props.filters.max_payment];
    }else if(props.filters.min_payment && props.filters.max_payment){
        payment.value = [props.filters.min_payment,props.filters.max_payment];
    }
    window.addEventListener('resize', updateWidth);

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
            <v-container fluid class="pa-0  shadow-md relative hero_section">
                <v-img position="center" src='storage/images/home_bg.jpg' class="!z-10" aspect-ratio="16/9" absolute cover width="100%" height="100%"></v-img>
                <v-container>
                    <v-row>
                        <v-col>
                            <div class="relative !z-20 flex flex-col items-center py-8 h-auto text-stone-950 w-full">
                                <div class="flex flex-col max-w-7xl items-center">
                                    <div class="mx-auto text-h6">
                                        Találd meg álmaid munkáját nálunk! Álláskeresőknek és munkaadóknak biztosítunk modern és hatékony platformot.
                                    </div>
                                    <div class="lg:mt-4 text-center">
                                        <div class="text-body-1">
                                            Hírdess nálunk és találd meg a következő kiemelkedő munkavállalód!
                                        </div>
                                        <v-btn variant="elevated" @click="router.visit(route('register',{type:'munkaltato'}))" class="!text-stone-50 !bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105 text-button mt-2">
                                            Munkahirdetés
                                        </v-btn>
                                    </div>
                                    <div class="lg:mt-5 text-center">
                                        <div class="text-body-1">
                                            Vagy esetleg munkát keresel? Akkor a legjobb helyen jársz!
                                        </div>
                                        <v-btn variant="elevated" @click="router.visit(route('jobs.show'))" class="!text-stone-50 !bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105 text-button mt-2">
                                            Munkák böngészése
                                        </v-btn>
                                    </div>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </v-container>
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <main class="mt-6">
                    <v-container>
                        <v-row>
                            <v-col>
                                <div class="lg:grid flex flex-col  grid-cols-1">
                                    <div class="lg:text-xl w-full mb-2 mt-4 text-h5">
                                        Ajánlott munkák
                                    </div>
                                    <div class="relative w-full overflow-hidden text-wrap md:mb-12 w-full" >
                                        <n-carousel :slides-per-view="computedSlidesPerView" :space-between="20" :loop="false"
                                            show-arrow draggable
                                            >
                                                <Link v-for="(job, index) in jobs.data" :key="job.id" :href="route('jobs.view',{id:job.id})">
                                                    <n-card
                                                      :segmented="{
                                                        content: true,
                                                        footer: 'soft',
                                                      }"
                                                      size="medium"
                                                      footer-class="max-h-32 min-h-32 text-ellipsis overflow-hidden"
                                                      action-class="text-center"
                                                    >
                                                      {{job.name}}
                                                      <template #footer>
                                                        {{job.description}}
                                                      </template>
                                                      <template #action>
                                                        <div class="text-center">{{computePostDates[index]}}</div>
                                                      </template>
                                                    </n-card>
                                                </Link>
                                                <template #arrow="{ prev, next }">
                                                  <div class="custom-arrow">
                                                    <button type="button" class="custom-arrow--left hover:!bg-[#A67C52]" @click="prev">
                                                      <v-icon icon="mdi mdi-chevron-left" size="30px"></v-icon>
                                                    </button>
                                                    <button type="button" class="custom-arrow--right hover:!bg-[#A67C52]" @click="next">
                                                      <v-icon icon="mdi mdi-chevron-right" size="30px"></v-icon>
                                                    </button>
                                                  </div>
                                                </template>
                                            </n-carousel>
                                    </div>
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                    <v-container>
                        <v-row>
                            <v-col>
                                <div class="mb-3 lg:text-xl text-h5">
                                    Jelenlegi munkák
                                </div>
                                <div>
                                    További munkákért, és részletesebb szűrési lehetőségekért!
                                </div>
                                <div class="my-3">
                                    <v-tooltip text="Szűrők megjelenítése/elrejtése" location="top">
                                        <template v-slot:activator="{ props }">
                                            <v-btn v-bind="props" variant="tonal" @click="handleFilterShow" :class="['mr-3 !text-stone-50 !bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105',{'!bg-sky-500':isFilterShow}]">
                                                Szűrők
                                            </v-btn>
                                        </template>
                                    </v-tooltip>
                                    <v-btn class="!text-stone-50 !bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105 ml-3" @click="router.visit(route('jobs.show'))">Még több munka</v-btn>
                                </div>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col>
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
                                        <v-pagination v-if="totalPages > 1" v-model="currentPage" active-color="blue" ellipsis="..." :length="totalPages" @update:model-value="fetchJobs"></v-pagination>
                                    </div>
                                    <v-slide-x-reverse-transition>
                                        <div v-if="filterShow" class="w-full lg:w-1/2 max-lg:my-3 lg:ml-10">
                                            <v-text-field label="Keresés..." v-model="search"></v-text-field>
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
                                              class="align-center sm:mt-4"
                                              hide-details
                                              thumb-label="always"
                                              color="#c7a07a"
                                            >
                                            </v-range-slider>
                                            <div class="mt-3">
                                                <v-btn variant="tonal" @click="startFilters" class="mr-3 !text-stone-50 !bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105">Szűrés</v-btn>
                                                <v-tooltip text="Szűrők alaphelyzetbe állítása">
                                                    <template v-slot:activator="{ props }">
                                                        <v-btn v-bind="props" variant="tonal" @click="setFiltersDefault" :class="['!text-stone-50 !bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105']">
                                                            Alaphelyzet
                                                        </v-btn>
                                                    </template>
                                                </v-tooltip>
                                            </div>
                                        </div>
                                    </v-slide-x-reverse-transition>
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                    <v-lazy   
                    :options="{'threshold':1}"
                    transition="fade-transition">
                        <v-container>
                            <v-row>
                                <v-col>
                                    <div class="lg:grid flex flex-col  grid-cols-1">
                                        <div class="lg:text-xl w-full mb-2 mt-4 text-h5">
                                            Népszerű munkák
                                        </div>
                                        <div class="relative w-full overflow-hidden text-wrap md:mb-12 w-full" >
                                            <n-carousel :slides-per-view="computedSlidesPerView" :space-between="20" :loop="false"
                                                show-arrow draggable
                                                >
                                                    <Link v-for="(mjob, index) in most_viewed" :key="mjob.id" :href="route('jobs.view',{id:mjob.id})">
                                                        <n-card
                                                          :segmented="{
                                                            content: true,
                                                            footer: 'soft',
                                                          }"
                                                          size="medium"
                                                          footer-class="max-h-32 min-h-32 text-ellipsis overflow-hidden"
                                                          action-class="text-center"
                                                        >
                                                          {{mjob.name}}
                                                          <template #footer>
                                                            {{mjob.description}}
                                                          </template>
                                                          <template #action>
                                                            <div class="text-center">{{computePostDates[index]}}</div>
                                                          </template>
                                                        </n-card>
                                                    </Link>
                                                    <template #arrow="{ prev, next }">
                                                      <div class="custom-arrow">
                                                        <button type="button" class="custom-arrow--left hover:!bg-[#A67C52]" @click="prev">
                                                          <v-icon icon="mdi mdi-chevron-left" size="30px"></v-icon>
                                                        </button>
                                                        <button type="button" class="custom-arrow--right hover:!bg-[#A67C52]" @click="next">
                                                          <v-icon icon="mdi mdi-chevron-right" size="30px"></v-icon>
                                                        </button>
                                                      </div>
                                                    </template>
                                                </n-carousel>
                                        </div>
                                    </div>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-lazy>
                    <v-lazy   
                    :options="{'threshold':1}"
                    transition="fade-transition">
                        <v-container>
                            <v-row>
                                <v-col>
                                    <div class="lg:grid flex flex-col  grid-cols-1">
                                        <div class="lg:text-xl w-full mb-2 mt-4 text-h5">
                                            Legfrissebb munkáink
                                        </div>
                                        <div class="relative w-full overflow-hidden text-wrap md:mb-12 w-full" >
                                            <n-carousel :slides-per-view="computedSlidesPerView" :space-between="20" :loop="false"
                                                show-arrow draggable
                                                >
                                                    <Link v-for="(njob, index) in new_jobs" :key="njob.id" :href="route('jobs.view',{id:njob.id})">
                                                        <n-card
                                                          :segmented="{
                                                            content: true,
                                                            footer: 'soft',
                                                          }"
                                                          size="medium"
                                                          footer-class="max-h-32 min-h-32 text-ellipsis overflow-hidden"
                                                          action-class="text-center"
                                                        >
                                                          {{njob.name}}
                                                          <template #footer>
                                                            {{njob.description}}
                                                          </template>
                                                          <template #action>
                                                            <div class="text-center">{{computePostDates[index]}}</div>
                                                          </template>
                                                        </n-card>
                                                    </Link>
                                                    <template #arrow="{ prev, next }">
                                                      <div class="custom-arrow">
                                                        <button type="button" class="custom-arrow--left hover:!bg-[#A67C52]" @click="prev">
                                                          <v-icon icon="mdi mdi-chevron-left" size="30px"></v-icon>
                                                        </button>
                                                        <button type="button" class="custom-arrow--right hover:!bg-[#A67C52]" @click="next">
                                                          <v-icon icon="mdi mdi-chevron-right" size="30px"></v-icon>
                                                        </button>
                                                      </div>
                                                    </template>
                                                </n-carousel>
                                        </div>
                                    </div>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-lazy>
                </main>
            </div>
        </div>
    </div>
    <Footer></Footer>
</template>
<style scoped>

.custom-arrow {
	display: flex;
	position: absolute;
	bottom: 0;
	right: 0;
	justify-content: space-between;
	gap: 2em;
	width: 100%;
	align-items: center;
    pointer-events: none;
}
.custom-arrow button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 50px;
  height: 50px;
  color: black;
  background-color: rgba(255, 255, 255, 0.1);
  border-width: 0;
  border-radius: 8px;
  transition: background-color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  cursor: pointer;
  pointer-events: all;
}


.custom-arrow button:active {
  transform: scale(0.95);
  transform-origin: center;
}

.gradient-bg{
    background: linear-gradient(to bottom, #C7A07A, #E4C6A2);
}
</style>
