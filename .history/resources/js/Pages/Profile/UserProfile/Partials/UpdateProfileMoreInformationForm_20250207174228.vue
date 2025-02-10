<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage,router  } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import _ from 'lodash';
import axios from 'axios';

const user = usePage().props.auth.user;

const searchQuery = ref('');
const institutions = ref([]);

const form = useForm({
    education_type:'',
    institution_name:'',
    education_name:'',
    education_level:'',
    date_from:'',
    date_to:'',
    education_place:'',
});

const debounceSearch = _.debounce(async () => {
  if (searchQuery.value.length >= 3) {
    const response = await axios.get('profile/institutions', {
      params: { institution_name: searchQuery.value },
    });
    institutions.value = response.data.institutions;
  } else {
    institutions.value = [];
  }
},500);

watch(() => form.institution_name, (e) => {
    searchQuery.value = e;
    debounceSearch();
});




</script>

<template>
    <section class="w-full">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Képzettségek
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="form.post(route('profile.update.education'), {onFinish: () => {
               form.reset(); 
            }})"
            class="mt-6 space-y-6"
            enctype="multipart/form-data"
        >
            <div>
                ss {{ form.education_type }}
                <InputLabel for="education_type" value="education_type" />

                <select v-model="form.education_type" name="education_type" id="education_type">
                    <option value="Végzettség típusa" selected>Végzettség típusa</option>
                    <option value="Általános-Iskola">Általános Iskola</option>
                    <option value="Szakiskola-Szakmunkás-képző">Szakiskola/Szakmunkás képző</option>
                    <option value="Középiskola">Középiskola</option>
                    <option value="Felsőoktatási szakképesítés">Felsőoktatási szakképesítés</option>
                    <option value="Főiskola">Főiskola</option>
                    <option value="Egyetem">Egyetem</option>
                    <option value="Állami rendszerű egyéb képzés">Állami rendszerű egyéb képzés</option>
                    <option value="Állami rendszerű, nem diplomás képzés">Állami rendszerű, nem diplomás képzés</option>
                    <option value="Coaching">Coaching</option>
                    <option value="OKJ-rendszerű tanfolyam">OKJ-rendszerű tanfolyam</option>
                    <option value="Tanfolyam">Tanfolyam</option>
                    <option value="Tréning">Tréning</option>
                </select>

                <InputError class="mt-2" :message="form.errors.username" />
            </div>
            <div>
                <InputLabel for="institution_name" value="institution_name" />

                <TextInput
                    id="institution_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.institution_name"
                    @input="debounceSearch"
                    
                    autofocus
                    autocomplete="institution_name"
                />
                <select v-model="institution_name" name="institution_name" id="institution_name">
                    <div v-for="institution in institutions" :key="institution.id">
                            <option :value="institution.institution_name">{{ institution.institution_name }}</option>
                    </div>
                </select>
                <div v-if="searchQuery.length < 1 && !institutions.value">Nincs találat</div>

                <InputError class="mt-2" :message="form.errors.institution_name" />
            </div>

            <div>
                <InputLabel for="education_name" value="education_name" />

                <TextInput
                    id="education_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.education_name"
                    
                    autocomplete="education_name"
                />

                <InputError class="mt-2" :message="form.errors.education_name" />
            </div>
            <div v-if="form.education_type == 'Egyetem' || form.education_type == 'Főiskola'">
                <InputLabel for="education_level" value="education_level" />


                <select v-model="form.education_level" name="form.education_level" id="form.education_level">
                    <option value="Megszerzett fokozat" selected>Megszerzett fokozat</option>
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
