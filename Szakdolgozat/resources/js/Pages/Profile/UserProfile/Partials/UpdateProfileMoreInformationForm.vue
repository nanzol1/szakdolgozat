<script setup>
import { useForm, usePage  } from '@inertiajs/vue3';
import { computed, ref, watch  } from 'vue';
import _ from 'lodash';
import axios from 'axios';
import { enUS,NConfigProvider, NDatePicker } from 'naive-ui';
import { hu } from 'date-fns/locale'

const huLocale = {
  ...enUS,
  DatePicker: {
    ...enUS.DatePicker,
    firstDayOfWeek: 1,
    now: "Mai dátum",
    confirm:'Elfogad',
  }
}

const user = usePage().props.auth.user;

const searchQuery = ref(null);
const institutions = ref([]);
const isPicked = ref(false);

const form = useForm({
    education_type: null,
    institution_name: null,
    education_name:null,
    education_level: null,
    date_from: null,
    date_to: null,
    education_place:null,
});

const debounceSearch = _.debounce(async () => {
    isPicked.value = false;
    if(searchQuery.value){
        if (searchQuery.value.length >= 3) {
            const response = await axios.get(route('profile.institutions.search'), {
                params: { institution_name: searchQuery.value },
            });
            if(response.data.institutions.length > 0){
                institutions.value = response.data.institutions;
            }
        } else {
            isPicked.value = false;
            institutions.value = [];
        }
    }
},500);

const getInstitution = computed(() => {
    return Object.values(institutions.value)?.map(inst => ({
        id:inst.id,
        title:inst.institution_name,
    }));
});
watch(searchQuery, (e) => {
    debounceSearch();
});
const handleInput = () => {
    if(isPicked.value === false){
        searchQuery.value = null;
    }
};

const handlePick = (inst) => {
    if(inst){
        form.institution_name = inst.title;
        isPicked.value = true;
    }else{
        form.institution_name = null;
        searchQuery.value = null;
    }
};


</script>

<template>
    <section class="w-full">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Képzettségek
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Ezen a fülön tudja frissíteni tanulmányait.
            </p>
        </header>
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-form
                        @submit.prevent="form.post(route('profile.update.education'), {onSuccess: () => {
                            form.reset();
                            institutions.value = [];
                            searchQuery = null;
                        }})"
                        class="mt-6 space-y-6"
                    >
                        <v-row>
                            <v-col cols="12">
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
                            </v-col>
                            <v-col cols="12" v-if="form.education_type == 'Egyetem' || form.education_type == 'Főiskola'">
                                <v-combobox
                                    clearable
                                    label="Megszerzett fokozat"
                                    :items="['Bsc - Alapdiploma', 'Dimploma (Kiegészítő képzésen)', 'Dimploma (Teljes képzés)', 'Felsőoktatási oktató', 
                                    'MBA', 'Msc - Mesterfokozat','PhD']"
                                    v-model="form.education_level"
                                    :error="!!form.errors.education_level"
                                    :error-messages="form.errors.education_level"
                                ></v-combobox>
                            </v-col>
                            <v-col cols="12">
                                <v-combobox
                                    clearable
                                    label="Intézmény megnevezése"
                                    :items="getInstitution"
                                    v-model="searchQuery"
                                    @update:focused="handleInput"
                                    @update:modelValue="handlePick"
                                    :error-messages="form.errors.institution_name"
                                    :error="!!form.errors.institution_name"
                                ></v-combobox>
                            </v-col>
                    
                            <v-col cols="12">
                                <v-text-field label="Végzettség neve" v-model="form.education_name" :error="!!form.errors.education_name" :error-messages="form.errors.education_name"
                                type="text"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6" class="h-[56px] flex align-center">
                                <n-config-provider
                                :locale="huLocale"
                                :date-locale="hu"
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
                            </v-col>
                            <v-col cols="12" md="6" class="h-[56px] flex align-center">
                                <n-config-provider
                                :locale="huLocale"
                                :date-locale="hu"
                                >
                                  <n-date-picker 
                                    v-model:value="form.date_to" 
                                    type="month" 
                                    :update-value-on-close="true" 
                                    placeholder="Oktatás ideje (-ig)" 
                                    size="large"
                                  />
                                </n-config-provider>
                            </v-col>
                            <v-col  cols="12">
                                <v-combobox
                                    clearable
                                    label="Megszerzés helye"
                                    :items="['Magyarország']"
                                    v-model="form.education_place"
                                    :error="!!form.errors.education_place"
                                    :error-messages="form.errors.education_place"
                                ></v-combobox>
                            </v-col>
                    
                            <v-col cols="12" class="flex items-center gap-4">
                                <v-btn type="submit" class="!bg-[#A67C52] hover:!bg-[#3A2618] !text-gray-50 hover:text-gray-50" :disabled="form.processing">
                                    {{ form.recentlySuccessful ? 'Mentve' : 'Mentés' }}
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-col>
            </v-row>
        </v-container>
    </section>
</template>
