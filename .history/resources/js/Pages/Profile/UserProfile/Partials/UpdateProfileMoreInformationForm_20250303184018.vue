<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage,router  } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import _ from 'lodash';
import axios from 'axios';

const user = usePage().props.auth.user;

const searchQuery = ref(null);
const institutions = ref([]);
const isPicked = ref(false);

const form = useForm({
    education_type:'0',
    institution_name:'0',
    education_name:'',
    education_level:'0',
    date_from:'',
    date_to:'',
    education_place:'',
});

const debounceSearch = _.debounce(async () => {
    isPicked.value = false;
    if(searchQuery.value){
        if (searchQuery.value.length >= 3) {
        const response = await axios.get('profile/institutions', {
                params: { institution_name: searchQuery.value },
        });
            institutions.value = response.data.institutions;
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
    if(isPicked.value === true){
        searchQuery.value = '';
    }
};

const handlePick = (inst) => {
    if(inst){
        form.institution_name = inst.title;
    }else{
        form.institution_name = null;
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

        <form
            @submit.prevent="form.post(route('profile.update.education'), {onFinish: () => {
               form.reset(); 
            }})"
            class="mt-6 space-y-6"
        >
            <div>
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
            <div>
                <v-combobox
                    clearable
                    label="Intézmény megnevezése"
                    :items="getInstitution"
                    v-model="searchQuery"
                    @update:focused="handleInput"
                    @update:modelValue="handlePick"
                ></v-combobox>

                <InputError class="mt-2" :message="form.errors.institution_name" />
            </div>

            <div>
                <v-text-field label="Végzettség neve" v-model="form.education_name" :error="!!form.errors.education_name" :error-messages="form.errors.education_name"
                type="text"
                ></v-text-field>
            </div>
            <div v-if="form.education_type == 'Egyetem' || form.education_type == 'Főiskola'">
                <InputLabel for="education_level" value="education_level" />


                <select v-model="form.education_level" name="form.education_level" id="form.education_level">
                    <option value="0" selected>Megszerzett fokozat</option>
                    <option value="Bsc - Alapdiploma">Bsc - Alapdiploma</option>
                    <option value="Dimploma (Kiegészítő képzésen)">Dimploma (Kiegészítő képzésen)</option>
                    <option value="Dimploma (Teljes képzés)">Dimploma (Teljes képzés)</option>
                    <option value="Felsőoktatási oktató">Felsőoktatási oktató (prof., adj., stb...)</option>
                    <option value="MBA">MBA</option>
                    <option value="Msc - Mesterfokozat">Msc - Mesterfokozat</option>
                    <option value="PhD">PhD</option>
                </select>

                <InputError class="mt-2" :message="form.errors.education_level" />
            </div>
            <div>
                <InputLabel for="date_from" value="date_from" />

                <input type="date" v-model="form.date_from" name="date_from" id="date_from">

                <InputError class="mt-2" :message="form.errors.date_from" />
            </div>
            <div>
                <InputLabel for="date_to" value="date_to" />

                <input type="date" v-model="form.date_to" name="date_to" id="date_to">

                <InputError class="mt-2" :message="form.errors.date_to" />
            </div>
            <div>
                <InputLabel for="education_place" value="education_place" />

                <select name="education_place" id="education_place" v-model="form.education_place">
                    <option value="Magyarország">Magyarország</option>
                </select>

                <InputError class="mt-2" :message="form.errors.education_place" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

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
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
