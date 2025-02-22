<script setup>
import NavBar from '@/Components/NavBar.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';

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
    }
});


const model = ref(null);
const selectedJob = ref([]);
const currentPage = ref(props.jobs.current_page);
const totalPages = ref(props.jobs.last_page);
const filterShow = ref(false);
const search = ref(props.filters.search || '');
const category = ref([]);
const subcategory = ref(props.filters.subcategory || []);

watch([search, category, subcategory], () => {
    currentPage.value = 1;
    fetchJobs();
});

const items = ref([]);

const categoriesArray = computed(() => Object.values(props.categories));

const selectedSubcategories = ref(
    categoriesArray.value.map(() => [])
);

const filteredSubcategories = computed(() => {
    if(category.value.length === 0){
        return Object.values(props.categories).flatMap(cat => cat.subcategories || []);
    }else{
        return category.value.flatMap(id => props.categories[id]?.subcategories || []);
    }
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}

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

    if (selectedSubcategories.value.flat().length > 0) {
        params.subcategory = selectedSubcategories.value.flat();
    }

    router.get(route("jobs.index"), params, {
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

const getSubcategoryItems = (category) => {
  return category.subcategories.map(subcat => ({
    title: subcat.subcat_name,
    value: subcat.subcat_id,
  }));
};

watch(category, (newCategory) => {
  selectedSubcategories.value = categoriesArray.value.map(() => []);
}, { immediate: true });
</script>