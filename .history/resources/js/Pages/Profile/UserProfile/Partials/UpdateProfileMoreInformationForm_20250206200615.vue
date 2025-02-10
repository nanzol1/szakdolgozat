<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { watch } from 'vue';

const user = usePage().props.auth.user;

const education_type = ref('');

const form = useForm({
    education_type:'',
    institution_name:'',
    education_name:'',
    date_from:'',
    date_to:'',
    education_place:'',
});

watch(education_type,(newValue) =>{
});

function setAchievedEducationLevel(){
    if(form.edu){

    }
}

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
            @submit.prevent="form.post(route('profile.update'))"
            class="mt-6 space-y-6"
            enctype="multipart/form-data"
        >
            <div>
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
                    
                    autofocus
                    autocomplete="institution_name"
                />

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

                <TextInput
                    id="education_place"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.education_place"
                    autocomplete="education_place"
                />

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
