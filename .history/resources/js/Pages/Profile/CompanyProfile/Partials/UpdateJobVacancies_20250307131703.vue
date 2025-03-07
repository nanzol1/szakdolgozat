<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref, watch } from 'vue';
import Checkbox from '@/Components/Checkbox.vue';
import _ from 'lodash';
import axios from 'axios';

const user = usePage().props.auth.user;

const props = defineProps({
    categories:{
        type:Object,
    },
    employment_types:{
        type:Object,
    },
    work_schedules:{
        type:Object,
    }
});

const checkboxes = ref([]);
const searchQuery = ref('');
const searchQuerySchedule = ref('');
const employments = ref([]);
const schedules = ref([]);
const isCatShown = ref(false);

const form = useForm({
    name:'',
    active:1,
    position:[],
    description:'',
    requirements:'',
    payment:'',
    created_by: user.id,
    maincateg_id: '',
    subcateg_id: '',
    employment_type: employments.value || [],
    work_schedules: schedules.value || [],
    zip_address: '',
    county_address:'',
    place_address:'',
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
        const response = await axios.get('profile/employment',{
            params: {employment_type: searchQuery.value},
        });
        employments.value = response.data.employmenttype;
    }else{
        employments.value = [];
    }

    if(searchQuerySchedule.value.length >= 3){
        const response = await axios.get('profile/schedules',{
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
onMounted(() => {
    isCatShown.value = props.categories.id;
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
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Munkahirdetés
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Ezen a fülön tud munkát meghirdetni.
            </p>
        </header>
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-form
                        @submit.prevent="form.post(route('cprofile.create.job'),{
                            onSuccess: () => {
                                form.reset();
                            }})"
                        >
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field type="text" v-model="form.name" label="Munka név"
                                    :error="!!form.errors.name" :error-messages="form.errors.name"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
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
                                    auto-grow counter></v-textarea>
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
                </v-col>
            </v-row>
        </v-container>
        <!--<form
            @submit.prevent="form.post(route('cprofile.create.job'),{
                onFinish: () => {
                    form.reset();
                }})"
            class="mt-6 space-y-6"
        >
            <div>
                <InputLabel for="name" value="name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div>
                <InputLabel for="address" value="address" />

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

                <textarea name="description" id="description" v-model="form.description" placeholder="Munka leírása" class="w-full" required></textarea>

                <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <div>
                <InputLabel for="requirements" value="requirements" />

                <textarea name="requirements" id="requirements" v-model="form.requirements" placeholder="Elvárások" class="w-full" required></textarea>

                <InputError class="mt-2" :message="form.errors.requirements" />
            </div>

            <div>
                <InputLabel for="payment" value="payment" />

                <input type="number" name="payment" id="payment" v-model="form.payment" placeholder="0" class="w-full">

                <InputError class="mt-2" :message="form.errors.payment" />
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
        </form>-->
    </section>
</template>
