<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;


const form = useForm({

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
                    disabled
                    autocomplete="education_name"
                />

                <InputError class="mt-2" :message="form.errors.education_name" />
            </div>
            <div>
                <InputLabel for="date_from" value="date_from" />

                <input type="date" v-model="form.date_from" name="date_from" id="date_from">

                <InputError class="mt-2" :message="form.errors.sex" />
            </div>
            <div>
                <InputLabel for="date_to" value="date_to" />

                <input type="date" v-model="form.date_to" name="date_to" id="date_to">

                <InputError class="mt-2" :message="form.errors.birth_date" />
            </div>
            <div>
                <InputLabel for="phone_number" value="phone_number" />

                <TextInput
                    id="phone_number"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.phone_number"
                    autocomplete="phone_number"
                />

                <InputError class="mt-2" :message="form.errors.phone_number" />
            </div>
            <div>
                <InputLabel for="county" value="county" />

                <TextInput
                    id="county"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.county"
                    autocomplete="county"
                />

                <InputError class="mt-2" :message="form.errors.county" />
            </div>
            <div>
                <InputLabel for="zip" value="zip" />

                <TextInput
                    id="zip"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.zip"
                    autocomplete="zip"
                />

                <InputError class="mt-2" :message="form.errors.zip" />
            </div>
            <div>
                <InputLabel for="city" value="city" />

                <TextInput
                    id="city"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.city"
                    autocomplete="city"
                />

                <InputError class="mt-2" :message="form.errors.city" />
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
