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
</script>

<template>
    <Head title="Munka szerkesztés"></Head>
    <AuthenticatedLayout>
        <div class="flex max-w-7xl mx-auto">
            <v-container>
                <v-row>
                    <v-col cols="12" lg="6">
                        <section class="bg-white p-4 shadow sm:rounded-lg sm:p-8 sm:py-2 dark:bg-gray-800 flex justify-between">
                            <v-form
                            @submit.prevent="form.post(route('cprofile.save.job'),{
                                onSuccess: () => {
                                    location.reload();
                                }})"
                            class="mt-6 space-y-6"
                            >
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field type="text" v-model="form.name" label="Munka név"
                                    :error="!!form.errors.name" :error-messages="form.errors.name"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field type="text" v-model="form.name" label="Munka név"
                                    :error="!!form.errors.name" :error-messages="form.errors.name"
                                    ></v-text-field>
                                    <Checkbox v-model:checked="activeCheckbox" :id="activeCheckbox"></Checkbox>
                                </v-col>
                            </v-row>
                            <div>
                                <InputLabel for="active" value="active" />
                            
                                <Checkbox v-model:checked="activeCheckbox" :id="activeCheckbox"></Checkbox>
                            
                                <InputError class="mt-2" :message="form.errors.active" />
                            </div>
                            <div>
                                <InputLabel for="address" value="address" />
                                ss: {{ computeAddress }}
                                <TextInput
                                    id="zip_address"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.zip_address"
                                    required

                                    autocomplete="zip_address"
                                    placeholder="Zip"
                                />
                                <InputError class="mt-2" :message="form.errors.zip_address" />
                            
                                <TextInput
                                    id="county_address"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.county_address"
                                    required

                                    autocomplete="county_address"
                                    placeholder="Város"
                                />
                                <InputError class="mt-2" :message="form.errors.county_address" />
                            
                                <TextInput
                                    id="place_address"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.place_address"
                                    required

                                    autocomplete="place_address"
                                    placeholder="Cím"
                                />
                                <InputError class="mt-2" :message="form.errors.place_address" />
                            
                            </div>
                            <div>
                                <InputLabel for="position" value="position" />
                                ss {{ checkboxes }}
                            
                                <div v-for="(cats, catKey) in props.categories" :key="catKey">
                                    <label :for="cats.id">{{ cats.name }}</label>
                                    <Checkbox :value="'m'+cats.id" v-model:checked="checkboxes" :id="cats.id" disabled>{{ cats.name }}</Checkbox>
                                    <div v-for="subcats in cats.subcategories" :key="subcats.id">
                                        <label :for="subcats.name">{{ subcats.name }}</label>
                                        <Checkbox :value="'s'+subcats.id" v-model:checked="checkboxes" :id="subcats.name" @change="checkMainCategory(subcats)">{{ subcats.name }}</Checkbox>
                                    </div>
                                </div>
                            
                                <InputError class="mt-2" :message="form.errors.positions" />
                            </div>
                        
                            <div>
                                <InputLabel for="employment_type" value="employment_type" />
                            
                                <TextInput
                                    id="employment_type"
                                    type="text"
                                    class="mt-1 block w-full"
                                    autocomplete="employment_type"
                                    v-model="searchQuery"
                                />
                            
                                <template v-for="types in computeEmployments" :key="types.id">
                                    <label :for="types.name">{{ types.name }}</label>
                                    <Checkbox v-model:checked="form.employment_type" :value="types.id" :id="types.name"></Checkbox>
                                </template>
                            
                            
                                <InputError class="mt-2" :message="form.errors.schedule" />
                            </div>
                        
                            <div>
                                <InputLabel for="work_schedule" value="work_schedule" />
                            
                                <TextInput
                                    id="work_schedule"
                                    type="text"
                                    class="mt-1 block w-full"
                                    autocomplete="work_schedule"
                                    v-model="searchQuerySchedule"
                                />

                                <template v-for="schedules in computeSchedules" :key="schedules.id">
                                    <label :for="schedules.name">{{ schedules.name }}</label>
                                    <Checkbox v-model:checked="form.work_schedules" :value="schedules.id" :id="schedules.name"></Checkbox>
                                </template>
                            
                            
                                <InputError class="mt-2" :message="form.errors.schedule" />
                            </div>
                        
                            <div>
                                <InputLabel for="description" value="description" />
                            
                                <textarea name="description" id="description" v-model="form.description" placeholder="Munka leírása" class="w-full dark:bg-black" required></textarea>
                            
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>
                        
                            <div>
                                <InputLabel for="requirements" value="requirements" />
                            
                                <textarea name="requirements" id="requirements" v-model="form.requirements" placeholder="Elvárások" class="w-full dark:bg-black" required></textarea>
                            
                                <InputError class="mt-2" :message="form.errors.requirements" />
                            </div>
                        
                            <div>
                                <InputLabel for="payment" value="payment" />
                            
                                <input type="number" name="payment" id="payment" v-model="form.payment" placeholder="0" class="w-full dark:bg-black">
                            
                                <InputError class="mt-2" :message="form.errors.payment" />
                            </div>
                        
                            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                                <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                                    Your email address is unverified.
                                    <Link
                                        :href="route('verification.send')"
                                        method="post"
                                        as="button"
                                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                                    >
                                        Click here to re-send the verification email.
                                    </Link>
                                </p>
                            
                                <div
                                    v-show="status === 'verification-link-sent'"
                                    class="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
                                >
                                    A new verification link has been sent to your email address.
                                </div>
                            </div>
                        
                            <div class="flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing">Mentés</PrimaryButton>
                            
                                <Transition
                                    enter-active-class="transition ease-in-out"
                                    enter-from-class="opacity-0"
                                    leave-active-class="transition ease-in-out"
                                    leave-to-class="opacity-0"
                                >
                                    <p
                                        v-if="form.recentlySuccessful"
                                        class="text-sm text-gray-600 dark:text-gray-400"
                                    >
                                        Mentve.
                                    </p>
                                </Transition>
                            </div>
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
.popup{
	position: fixed;
	left: 50%;
	top: 50%;
	transform: translate(-50%,-50%);
	background: blue;
	padding: 1em;
}
</style>