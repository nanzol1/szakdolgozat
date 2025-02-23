<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import Checkbox from '@/Components/Checkbox.vue';
import GuestPageLayout from '@/Layouts/GuestPageLayout.vue';
import { useAlertStore } from '@/store';

const alertStore = useAlertStore();

const props = defineProps({
    jobs: Object,
    filters: Object,
    categories: Object,
    subcategories: Object,
    canLogin: Boolean,
    canRegister: Boolean,
    min_spayment: Object,
    max_spayment: Object,
});

const currentPage = ref(props.jobs.current_page);
const totalPages = ref(props.jobs.total);
const search = ref(props.filters.search || '');
const category = ref(props.filters.category || []);
const subcategory = ref(props.filters.subcategory || []);
const payment = ref([props.filters.min_payment || props.min_spayment.payment, props.filters.max_payment || props.max_spayment.payment]);
const isFilterShow = ref(false);

watch([search, category, subcategory, payment], () => {
    currentPage.value = 1;
    if (category.value.length > 0) {
        category.value = category.value.filter(val => !isNaN(parseInt(val)));
    }
}, { deep: true });

const filteredSubcategories = computed(() => {
    return category.value.length === 0
        ? Object.values(props.categories).flatMap(cat => cat.subcategories || [])
        : category.value.flatMap(id => props.categories[id]?.subcategories || []);
});

const fetchJobs = async () => {
    const params = {
        page: currentPage.value,
        search: search.value || undefined,
        category: category.value.length > 0 ? category.value : undefined,
        subcategory: subcategory.value.length > 0 ? subcategory.value : undefined,
        min_payment: payment.value[0],
        max_payment: payment.value[1],
    };

    try {
        router.get('munkak/', params, { preserveState: true, replace: true, preserveScroll: true, only: ["jobs"] });
        alertStore.showAlert('Sikeres szűrés!', 'success');
    } catch (error) {
        alertStore.showAlert('Sikertelen művelet!', 'error');
        console.error(error);
    }
};

const resetFilters = () => {
    search.value = '';
    category.value = [];
    subcategory.value = [];
    currentPage.value = 1;
    payment.value = [props.min_spayment.payment, props.max_spayment.payment];

    try {
        router.get('munkak/', { page: currentPage.value }, { preserveState: true, preserveScroll: true, only: ["jobs"] });
        alertStore.showAlert('Szűrők sikeresen alaphelyzetbe állítva', 'success');
    } catch (error) {
        alertStore.showAlert('Sikertelen művelet', 'error');
    }
};

const toggleFilterShow = () => {
    isFilterShow.value = !isFilterShow.value;
};
</script>

<template>
    <Head title="Aktív állások" />
    <GuestPageLayout :canLogin="canLogin" :canRegister="canRegister">
        <div class="flex gap-6 p-4">
            <!-- Szűrők -->
            <div class="relative">
                <button @click="toggleFilterShow" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                    Szűrők
                </button>

                <transition name="fade">
                    <div v-if="isFilterShow" class="absolute left-0 mt-2 w-80 p-4 bg-white shadow-lg rounded-lg">
                        <v-text-field label="Keresés..." v-model="search" class="mb-3"></v-text-field>

                        <!-- Kategóriák -->
                        <div>
                            <h3 class="font-semibold">Kategóriák</h3>
                            <template v-for="cats in categories" :key="cats.mid">
                                <label :for="cats.mid" class="flex items-center gap-2">
                                    <Checkbox v-model:checked="category" :value="cats.mid" :id="cats.mid" />
                                    {{ cats.name }}
                                </label>
                            </template>
                        </div>

                        <!-- Alkategóriák -->
                        <div class="mt-3">
                            <h3 class="font-semibold">Alkategóriák</h3>
                            <template v-for="subcat in filteredSubcategories" :key="subcat.subcat_id">
                                <label :for="subcat.subcat_keyid" class="flex items-center gap-2">
                                    <Checkbox v-model:checked="subcategory" :value="subcat.subcat_id" :id="subcat.subcat_keyid" />
                                    {{ subcat.subcat_name }}
                                </label>
                            </template>
                        </div>

                        <!-- Fizetési tartomány -->
                        <div class="mt-3">
                            <v-range-slider
                                v-model="payment"
                                :max="props.max_spayment.payment"
                                :min="props.min_spayment.payment"
                                step="1"
                                label="Fizetési tartomány"
                                class="align-center"
                                hide-details
                                thumb-label="always"
                            />
                        </div>

                        <button @click="fetchJobs" class="mt-3 w-full bg-green-500 text-white py-2 rounded-md">
                            Szűrés
                        </button>
                        <button @click="resetFilters" class="mt-2 w-full bg-gray-300 py-2 rounded-md">
                            Alaphelyzet
                        </button>
                    </div>
                </transition>
            </div>

            <!-- Állások -->
            <div class="flex-1">
                <div v-for="job in jobs" :key="job.id" class="p-4 border-b">
                    <h2 class="font-bold text-lg">{{ job.name }}</h2>
                    <p class="text-gray-600">{{ job.position_name }}</p>
                    <p class="break-words whitespace-normal overflow-hidden">{{ job.description }}</p>
                    <Link :href="route('jobs.view', { id: job.id })" class="text-blue-500 underline">
                        Érdeklődöm
                    </Link>
                </div>

                <!-- Lapozás -->
                <v-pagination 
                    v-model="currentPage" 
                    :length="totalPages" 
                    @update:model-value="fetchJobs" 
                    class="mt-4"
                />
            </div>
        </div>
    </GuestPageLayout>
</template>

<style>
/* Szűrő panel animáció */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}
</style>
