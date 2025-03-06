<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, Head, router, usePage } from '@inertiajs/vue3';
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
const isPopup = ref(false);
const selectedApplicant = ref(null);
const status = ref('');
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
const updateStatus = async (id,applicantId,statusId) => {
    await router.put(route('cprofile.job.updatestatus',{id:id,jelentkezoId:applicantId,statusId:statusId}));
    status.value = statusId;
};
const showPopUp = async (applicantId) => {
    try{
        const response = await axios.get(route('cprofile.job.applicant',{id:props.job.id,jelentkezoId:applicantId}));
        selectedApplicant.value = response.data.applicant;
        status.value = response.data.applicant.status_id;
        isPopup.value = true;
    } catch (error){
        console.error('Hiba történt a lekérés közben: ',error);
    }
};
const closePopup = () => {
    isPopup.value = false;
};
watch(status,(newVal) => {
    if(status.value < 2){
        router.put(route('cprofile.job.updatestatus',{id:props.job.id,jelentkezoId:selectedApplicant.value.user_id,statusId:2}));
        status.value = 2;
    }else{
        updateStatus(props.job.id,selectedApplicant.value.user_id,newVal);
    }
});

const computeError = computed(() => {
    if(form.work_schedules.length <= 0){
        return "Kötelező választani legalább egyet!";
    }
});
</script>

<template>
    <Head title="Munka szerkesztés"></Head>
    <AuthenticatedLayout>
        <div class="flex max-w-7xl mx-auto">
            <v-container>
                <v-row>
                    <v-col cols="12" lg="6">
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
                                        {{ computeError }}
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
                    <v-col cols="12" lg="6">
                        <section class="bg-white p-4 shadow sm:rounded-lg sm:p-8 sm:py-2 dark:bg-gray-800 flex justify-between flex-col">
                            Jelentkező(k)
                            <div v-for="apps in applications">
                                {{ apps }}
                                <button @click="showPopUp(apps.user_id)">Megtekintem</button>
                            </div>
                            <Transition>
                                <div v-if="isPopup" class="popup">
                                    <button @click="closePopup()">X</button>
                                    <div>
                                        {{ selectedApplicant.name }}
                                    </div>
                                    <div>
                                        {{ selectedApplicant.email }}
                                    </div>
                                    <div>
                                        {{ selectedApplicant.phone }}
                                    </div>
                                    <template v-for="(sts, index) in statuses" :key="sts.id">
                                        <div v-if="sts.name !== 'Jelentkezett'">
                                            <label :for="sts.id">{{ sts.name }}</label>
                                            <input type="radio" v-model="status" :id="sts.name" :name="sts.name" :value="sts.id" :disabled="index < status.valueOf()">
                                        </div>
                                    </template>
                                </div>
                            </Transition>
                        </section>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </AuthenticatedLayout>
</template>
<style>
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
.subcats-enter-active, .subcats-leave-active {
  transition: all 0.3s ease-out;
}
.subcats-enter-from, .subcats-leave-to {
  opacity: 0;
}
.popup{
	position: fixed;
	left: 50%;
	top: 50%;
	transform: translate(-50%,-50%);
	background: blue;
	padding: 1em;
}
</style>