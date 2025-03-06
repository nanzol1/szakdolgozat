<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import UpdateProfileMoreInformationForm from './Partials/UpdateProfileMoreInformationForm.vue';
import { computed, ref, watch } from 'vue';
import _ from 'lodash';
import { enUS,NConfigProvider, NDatePicker } from 'naive-ui';
import { hu } from 'date-fns/locale'
import axios from 'axios';

const huLocale = {
  ...enUS,
  DatePicker: {
    ...enUS.DatePicker,
    firstDayOfWeek: 1,
    now: "Mai dátum",
    confirm:'Elfogad',
  }
}

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    category: {
        type: Object,
    },
    jobs_interests:{
        type:Array,
    },
    query:{
        type:String,
    },
    institutions:{
        type:Array,
    },
    educations:{
        type:Object,
    }
});

const newEducation = ref(false);
const selectedEducation = ref(null);
const overlay = ref(false);
const isEditing = ref(false);
const searchQuery = ref(null);
const institutions = ref([]);
const isPicked = ref(false);
const deleteOverlay = ref(false);

const handleSelection = (id) => {
    selectedEducation.value = Object.values(props.educations)?.filter(e => e.id === id)[0];
    overlay.value = true;

    //Setup form
    form.id = selectedEducation.value.id;
    form.education_type = selectedEducation.value.education_type;
    form.institution_name = selectedEducation.value.institution_name;
    form.education_name = selectedEducation.value.education_name;
    searchQuery.value = selectedEducation.value.institution_name;
    form.education_level = selectedEducation.value.education_level;
    form.date_from = selectedEducation.value.date_from;
    form.date_to = selectedEducation.value.date_to;
    form.education_place = selectedEducation.value.education_place;
};

const form = useForm({
    id: null,
    education_type: null,
    institution_name:  null,
    education_name:  null,
    education_level:  null,
    date_from:  null,
    date_to:  null,
    education_place:  null,
});

const deleteEducation = () => {
    axios.post(route('profile.update.education.delete'),{onSuccess: () => {
        isEditing.value = false;
        isPicked.value = false;
        overlay.value = false;
    }},{preserveScroll:true});
};
const getInstitution = computed(() => {
    return Object.values(institutions.value)?.map(inst => ({
        id:inst.id,
        title:inst.institution_name,
    }));
});
const debounceSearch = _.debounce(async () => {
    isPicked.value = false;
    if (searchQuery.value && typeof searchQuery.value === 'string' && searchQuery.value.length >= 2) {
        const response = await axios.get(route('profile.institutions.search'), {
            params: { institution_name: searchQuery.value },
        });
        institutions.value = response.data.institutions;
    } else {
        institutions.value = [];
    }
}, 500);
const handleInput = () => {
    if(isPicked.value === false){
        searchQuery.value = null;
    }
};
watch(searchQuery, (e,oldVal) => {
    if(e !== selectedEducation.value.institution_name){
        debounceSearch();
    }
});
const submit = () => {
    form.post(route('profile.update.education'),{preserveScroll:true,onSuccess: () => {isEditing.value = false}});
};
</script>

<template>
    <AuthenticatedLayout>
    <Head title="Profil - Képzettségek" />
    <v-container fluid class="bg-white px-0 mx-0">
        <v-container class="!max-w-7xl mx-auto">
            <v-row>
                <v-col>
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800"
                    >
                        Profilom / Képzettségek
                    </h2>
                    <h3 class="text-lg font-semibold leading-tight text-gray-800 mt-1">Ezen az oldalon tudja megtekinteni és szerkeszteni profilját.</h3>
                    <div class="flex gap-3 md:gap-5 text-md mt-3">
                        <Link :href="route('profile.edit')" :class="['hover:text-sky-500 transition',{'text-sky-500':$page.url === '/profile'}]">Alap adataim</Link>
                        <Link :href="route('profile.edit.interests')" :class="['hover:text-sky-500 transition',{'text-sky-500':$page.url.includes('/erdekeltsegeim')}]">Érdekeltségeim</Link>
                        <Link :href="route('profile.edit.educations')" :class="['hover:text-sky-500 transition',{'text-sky-500':$page.url.includes('/kepzettsegek')}]">Képzettségek</Link>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </v-container>
    <div class="max-w-7xl mx-auto">
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-btn @click="newEducation = !newEducation" class="hover:bg-sky-500 hover:text-gray-50 mb-3" append-icon="mdi mdi-plus-circle-outline">Új végzettség felvétele</v-btn>
                    <v-expand-transition>
                        <div
                            class="bg-white p-4 shadow sm:rounded-lg sm:p-8 flex flex-row gap-[2em]"
                            v-if="newEducation"
                        >
                            <UpdateProfileMoreInformationForm :educations="educations"/>
                        </div>
                    </v-expand-transition>
                </v-col>
                <v-overlay v-model="overlay" scroll-strategy="reposition" class="align-center justify-center" width="100%">
                    <v-card class="education-card !border-2 !border-solid !border-stone-950 w-[90%] xl:w-[40%] lg:w-[90%] mx-auto" elevation="20">
                        <div class="absolute right-2 top-2">
                            <v-icon icon="mdi mdi-close" @click="overlay = false; isEditing = false"></v-icon>
                        </div>
                        <div class="flex flex-col pa-5">
                            <div v-if="!isEditing">
                                <div>
                                    Végzettség típusa: {{ selectedEducation.education_type }}
                                </div>
                                <div>
                                    Intézmény neve: {{ selectedEducation.institution_name }}
                                </div>
                                <div>
                                    Végzettség megnevezése: {{ selectedEducation.education_name }}
                                </div>
                                <div>
                                    Időtartam: {{ new Date(selectedEducation.date_from).toLocaleDateString('HU') }} - {{ new Date(selectedEducation.date_to).toLocaleDateString('HU') }}
                                </div>
                                <div>
                                    Megszerzés helye: {{ selectedEducation.education_place }}
                                </div>
                            </div>
                            <div v-else>
                                <v-form @submit.prevent="submit">
                                    <div>
                                        Végzettség típusa:                                 
                                        <v-combobox
                                            clearable
                                            label="Végzettség típusa"
                                            :items="['Általános Iskola', 'Szakiskola Szakmunkás képző', 'Középiskola', 'Felsőoktatási szakképesítés', 
                                            'Főiskola', 'Egyetem','Állami rendszerű egyéb képzés','Állami rendszerű, nem diplomás képzés',
                                            'Coaching','OKJ-rendszerű tanfolyam','Tanfolyam','Tréning']"
                                            v-model="form.education_type"
                                            :error="!!form.errors.education_type"
                                            :error-messages="form.errors.education_type"
                                        ></v-combobox>
                                    </div>
                                    <div v-if="form.education_type === 'Egyetem' || form.education_type === 'Főiskola'">
                                        <v-combobox
                                            clearable
                                            label="Megszerzett fokozat"
                                            :items="['Bsc - Alapdiploma', 'Dimploma (Kiegészítő képzésen)', 'Dimploma (Teljes képzés)', 'Felsőoktatási oktató', 
                                            'MBA', 'Msc - Mesterfokozat','PhD']"
                                            v-model="form.education_level"
                                            :error="!!form.errors.education_level"
                                            :error-messages="form.errors.education_level"
                                        ></v-combobox>
                                    </div>
                                    <div>
                                        Intézmény neve: 
                                        <v-combobox
                                            clearable
                                            label="Intézmény megnevezése"
                                            :items="getInstitution"
                                            v-model="searchQuery"
                                            :error-messages="form.errors.institution_name"
                                            :error="!!form.errors.institution_name"
                                            item-title="title"
                                            item-value="id"
                                        ></v-combobox>
                                    </div>
                                    <div>
                                        Végzettség megnevezése: <v-text-field label="Végzettség megnevezése" v-model="form.education_name"
                                        type="text" :error="!!form.errors.education_name" :error-messages="form.errors.education_name"
                                        ></v-text-field>
                                    </div>
                                    <div>
                                        Időtartam: 
                                        <n-config-provider
                                            :locale="huLocale"
                                            :date-locale="hu"
                                            class="mb-3"
                                            >
                                              <n-date-picker 
                                                v-model:value="form.date_from" 
                                                type="month" 
                                                :update-value-on-close="true" 
                                                placeholder="Oktatás ideje (-től)" 
                                                size="large"
                                                :error="!!form.errors.date_from"
                                                :error-messages="!!form.errors.date_from"
                                              />
                                            </n-config-provider>
                                            <n-config-provider
                                                :locale="huLocale"
                                                :date-locale="hu"
                                                class="mb-3"
                                                >
                                                  <n-date-picker 
                                                    v-model:value="form.date_to" 
                                                    type="month" 
                                                    :update-value-on-close="true" 
                                                    placeholder="Oktatás ideje (-ig)" 
                                                    size="large"
                                                  />
                                            </n-config-provider>
                                    </div>
                                    <div>
                                        Megszerzés helye: <v-text-field label="Végzettség megnevezése" v-model="form.education_place"
                                        type="text" :error="!!form.errors.education_place" :error-messages="form.errors.education_place"
                                        ></v-text-field>
                                    </div>
                                </v-form>
                            </div>
                            <div class="flex justify-between flex-wrap mt-2">
                                <div>
                                    <v-btn class="hover:bg-sky-500 hover:text-gray-50" @click="deleteOverlay = true">Törlés</v-btn>
                                </div>
                                <div class="flex gap-2 md:gap-4 flex-wrap">
                                    <v-btn v-if="isEditing" type="submit" @click="submit" class="hover:bg-sky-500 hover:text-gray-50">Mentés</v-btn>
                                    <v-btn @click="isEditing = !isEditing" v-else class="hover:bg-sky-500 hover:text-gray-50">Szerkeszt</v-btn>
                                    <v-btn class="hover:bg-sky-500 hover:text-gray-50" @click="overlay = false; isEditing = false">Mégsem</v-btn>
                                </div>
                            </div>
                        </div>
                    </v-card>
                    <v-overlay v-model="deleteOverlay" class="align-center justify-center">
                        <v-card class="pa-4">
                            <v-card-title>
                                Biztosan törölni szeretnéd?
                            </v-card-title>
                            <div class="mt-2 flex gap-2 md:gap-4">
                                <v-btn class="hover:bg-sky-500 hover:text-gray-50" @click="deleteEducation">Igen</v-btn>
                                <v-btn class="hover:bg-sky-500 hover:text-gray-50" @click="deleteOverlay = false">Nem</v-btn>
                            </div>
                        </v-card>
                    </v-overlay>
                </v-overlay>
                <v-col cols="12" v-for="educ in educations" :key="educ.id">
                        <v-card
                          class="mx-auto"
                          width="100%"
                          append-icon="mdi mdi-file-edit-outline"
                          @click="handleSelection(educ.id)"
                        >
                          <template v-slot:title>
                            <span class="font-weight-black">{{ educ.institution_name }}</span>
                          </template>
                          <template v-slot:subtitle>
                            <span class="font-weight-black">{{ educ.education_type }}</span>
                          </template>
                      
                          <v-card-text class="bg-surface-light pt-4">
                            {{ educ.education_name }}
                          </v-card-text>
                        </v-card>
                </v-col>
            </v-row>
        </v-container>
    </div>  
    </AuthenticatedLayout>
</template>
<style>
    html{
        scroll-behavior: smooth;
    }
</style>
