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
