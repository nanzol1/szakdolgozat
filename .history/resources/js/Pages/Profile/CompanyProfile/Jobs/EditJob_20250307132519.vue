<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head, usePage, Link } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';
import Checkbox from '@/Components/Checkbox.vue';
import _ from 'lodash';
import axios from 'axios';


const props = defineProps({
    categories:{
        type:Object,
    },
    employment_types:{
        type:Object,
    },
    work_schedules:{
        type:Object,
    },
    job:{
        type:Object,
    },
    applications:{
        type:Object,
    },
    statuses:{
        type:Object,
    }
});
const page = usePage();
const checkboxes = ref(props.job.position || []);
const searchQuery = ref('');
const searchQuerySchedule = ref('');
const employments = ref([]);
const schedules = ref([]);
const activeCheckbox = ref(false);
const isCatShown = ref(false);


const form = useForm({
    id:props.job.id,
    name:props.job.name,
    active:props.job.active,
    description:props.job.description,
    requirements:props.job.requirements,
    payment:props.job.payment || '0',
    position: checkboxes.value,
    created_by: props.job.created_by,
    maincateg_id: props.job.maincateg_id,
    subcateg_id: props.job.subcateg_id,
    employment_type: props.job.employment_type || [],
    work_schedules: props.job.work_schedules || [],
    zip_address: props.job.address,
    county_address:props.job.address,
    place_address:props.job.address,
});

const checkMainCategory = (category) => {
    const mainCategoryId = 'm' + category.maincateg_id;
    const subCategoryId = 's' + category.id;

    if (checkboxes.value.includes(subCategoryId)) {
        checkboxes.value = checkboxes.value.filter(item => !item.startsWith('s'));

        checkboxes.value.push(subCategoryId);

        checkboxes.value = checkboxes.value.filter(item => !item.startsWith('m'));

        if (!checkboxes.value.includes(mainCategoryId)) {
            checkboxes.value.push(mainCategoryId);
        }
    } else {
        const hasOtherSubcategories = checkboxes.value.some(item => {
            return item.startsWith('s') && item !== subCategoryId && item.split('s')[1] === category.maincateg_id.toString();
        });

        if (!hasOtherSubcategories) {
            checkboxes.value = checkboxes.value.filter(item => item !== mainCategoryId);
        }
    }
};



const debounceSearch = _.debounce(async () => {
    if(searchQuery.value.length >= 3){
        const response = await axios.get('/search-employment',{
            params: {employment_type: searchQuery.value},
        });
        employments.value = response.data.employmenttype;
    }else{
        employments.value = [];
    }

    if(searchQuerySchedule.value.length >= 3){
        const response = await axios.get('/search-schedules',{
            params: {work_schedules: searchQuerySchedule.value},
        });
        schedules.value = response.data.schedules;
    }else{
        schedules.value = [];
    }
},500);

const computeEmployments = computed(() => {
    if(employments.value.length > 0){
        return Object.values(employments.value).filter(ep => ep && ep.id && ep.name).map(ep => ({
            id: ep.id,
            name:ep.name,
        }));
    }else{
        return props.employment_types;
    }
});

const computeAddress = computed(() => {
    let address = props.job.address || '';
    if(address){
        if(address.split(',')[0].length > 0){
            form.zip_address = address.split(',')[0];
        }
        if(address.split(',')[1].length > 0){
            form.county_address = address.split(',')[1];
        }
        if(address.split(',')[2].length > 0){
            form.place_address = address.split(',')[2];
        }
    }
});

const computeSchedules = computed(() => {
    if(schedules.value.length > 0){
        return Object.values(schedules.value).filter(ep => ep && ep.id && ep.name).map(ep => ({
            id: ep.id,
            name:ep.name,
        }));
    }else{
        return props.work_schedules;
    }
});

watch([searchQuery,searchQuerySchedule], (e) => {
    debounceSearch(e);
});

watch(checkboxes,(newVal) => {
    form.position = newVal;
    if(newVal){
        Object.values(newVal).forEach(element => {
            if(element.includes('m')){
                form.maincateg_id = element.replace('m','');
            }
            if(element.includes('s')){
                form.subcateg_id = element.replace('s','');
            }
        });
    }else{
        form.maincateg_id = 0;
        form.subcateg_id = 0;
    }
}, {deep:true});

watch(activeCheckbox,(e) => {
    if(e === true){
        form.active = 1;
    }else{
        form.active = 0;
    }
});
onMounted(() => {
    isCatShown.value = props.job.maincateg_id;
    if(props.job.active === 1){
        activeCheckbox.value = true;
    }else{
        activeCheckbox.value = false;
    }
});



const computeError = computed(() => {
    if(form.work_schedules.length <= 0){
        return "Kötelező választani legalább egyet!";
    }
});
const computeErrorEmployments = computed(() => {
    if(form.employment_type.length <= 0){
        return "Kötelező választani legalább egyet!";
    }
});

</script>

<template>
    <AuthenticatedLayout>
        <Head title="Munka szerkesztés"></Head>
        <v-container fluid class="bg-white px-0 mx-0">
            <v-container class="!max-w-7xl mx-auto">
                <v-row>
                    <v-col>
                        <h2
                            class="text-xl font-semibold leading-tight text-gray-800"
                        >
                            Munka / Szerkesztés
                        </h2>
                        <h3 class="text-lg font-semibold leading-tight text-gray-800 mt-1">Ezen az oldalon tudja megtekinteni és szerkeszteni profilját.</h3>
                        <div class="flex gap-3 md:gap-5 text-md mt-3">
                            <Link :href="route('cprofile.edit')" :class="['hover:text-sky-500 transition']">Alap adataim</Link>
                            <Link :href="route('cprofile.edit.jobs')" class="hover:text-sky-500 transition">Munkahirdetés</Link>
                            <Link :href="route('cprofile.myjobs')" :class="['hover:text-sky-500 transition',{'text-sky-500':$page.url.includes('cmunka')}]">Munkáim - Szerkesztés</Link>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </v-container>
        <div class="flex max-w-7xl mx-auto">
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <section class="bg-white p-4 shadow sm:rounded-lg sm:p-8 sm:py-2 flex justify-between">
                            <v-form
                            @submit.prevent="form.post(route('cprofile.save.job'),{
                                onSuccess: () => {
                                    location.reload();
                                }})"
                            >
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field type="text" v-model="form.name" label="Munka név"
                                        :error="!!form.errors.name" :error-messages="form.errors.name"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-checkbox label="Aktív" v-model="activeCheckbox" 
                                        :id="activeCheckbox" :error="!!form.errors.active" 
                                        :error-messages="form.errors.active"></v-checkbox>
                                    </v-col>
                                    <v-col cols="12">
                                        {{ computeAddress }}
                                        <v-text-field type="text" v-model="form.zip_address" label="Irányítószám"
                                        :error="!!form.errors.zip_address" :error-messages="form.errors.zip_address"
                                        ></v-text-field>
                                        <v-text-field type="text" v-model="form.county_address" label="Város"
                                        :error="!!form.errors.county_address" :error-messages="form.errors.county_address"
                                        ></v-text-field>
                                        <v-text-field type="text" v-model="form.place_address" label="Cím"
                                        :error="!!form.errors.place_address" :error-messages="form.errors.place_address"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <div v-for="(cats, catKey) in props.categories" :key="catKey">
                                            <v-btn variant="text" :append-icon="isCatShown == cats.id ? 'mdi mdi-arrow-up' : 'mdi mdi-arrow-down'"
                                            @click="isCatShown = cats.id" :class="['hover:bg-sky-500 hover:text-gray-50 mb-2',{'!bg-sky-500 !text-gray-50':isCatShown == cats.id}]">{{ cats.name }}</v-btn>
                                            <transition-group tag="div" name="subcats" mode="out-in">
                                                <div v-for="subcats in cats.subcategories" :key="subcats.id" v-if="isCatShown == cats.id" class="pl-10 mb-2">
                                                    <label :for="subcats.name">{{ subcats.name }}</label>
                                                    <Checkbox class="!border-1 !border-solid !border-stone-950 ml-2" :value="'s'+subcats.id" v-model:checked="checkboxes" :id="subcats.name" @change="checkMainCategory(subcats)">{{ subcats.name }}</Checkbox>
                                                </div>
                                            </transition-group>
                                        </div>
                                    </v-col>
                                    <v-col cols="12">
                                        <div class="mb-2">
                                            Foglalkoztatottság:
                                        </div>
                                        <template v-for="types in computeEmployments" :key="types.id"> 
                                            <div class="flex align-center gap-2 mb-2">
                                                <label :for="types.name">{{ types.name }}</label>
                                                <Checkbox class="!border-1 !border-solid !border-stone-950" v-model:checked="form.employment_type" :value="types.id" :id="types.name"></Checkbox>
                                            </div>
                                        </template>
                                        <div class="text-red-500 text-lg">
                                            {{ computeErrorEmployments }}
                                        </div>
                                    </v-col>
                                    <v-col cols="12">
                                        <div class="mb-2">
                                            Munkarend:
                                        </div>
                                        <template v-for="schedules in computeSchedules" :key="schedules.id">
                                            <div class="flex align-center gap-2 mb-2">
                                                <label :for="schedules.name">{{ schedules.name }}</label>
                                                <Checkbox class="!border-1 !border-solid !border-stone-950" v-model:checked="form.work_schedules" :value="schedules.id" :id="schedules.name"
                                                :error="!!form.errors.work_schedules" :error-message="form.errors.work_schedules"></Checkbox>
                                            </div>
                                        </template>
                                        <div class="text-red-500 text-lg">
                                            {{ computeError }}
                                        </div>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-textarea label="Leírás" v-model="form.description"
                                        :error="!!form.errors.description" :error-messages="form.errors.description"
                                        auto-grow></v-textarea>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-textarea label="Követelmények" v-model="form.requirements"
                                        :error="!!form.errors.requirements" :error-messages="form.errors.requirements"
                                        auto-grow></v-textarea>
                                    </v-col>
                                    <v-col cols="12">
                                        <div class="flex align-center">
                                            <v-text-field label="Bérezés (átlag)" v-model="form.payment"
                                                :error="!!form.errors.payment" :error-messages="form.errors.payment"
                                                type="number"></v-text-field>
                                            <div>
                                                HUF
                                            </div>
                                        </div>
                                    </v-col>
                                    <v-col cols="12 mb-3">
                                        <v-btn type="submit" class="hover:bg-sky-500 hover:text-gray-50" :disabled="form.processing">
                                            {{ form.recentlySuccessful ? 'Mentve' : 'Mentés' }}
                                        </v-btn>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </section>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </AuthenticatedLayout>
</template>
<style>
.subcats-enter-active, .subcats-leave-active {
  transition: all 0.3s ease-out;
}
.subcats-enter-from, .subcats-leave-to {
  opacity: 0;
}
</style>