<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import UpdateProfileMoreInformationForm from './Partials/UpdateProfileMoreInformationForm.vue';
import { ref } from 'vue';

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

const handleSelection = (id) => {
    selectedEducation.value = Object.values(props.educations)?.filter(e => e.id === id);
    overlay.value = true;
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
                <v-overlay v-model="overlay" width="50%" scroll-strategy="reposition" class="align-center justify-center">
                    <v-card class="pr-6">
                        <div class="absolute right-0">
                            <v-icon icon="mdi mdi-close" @click="overlay = false"></v-icon>
                        </div>
                        <div v-for="seduc in selectedEducation" :key="seduc.id" class="flex flex-col">
                            <div>
                                Végzettség típusa: {{ seduc.education_type }}
                            </div>
                            <div>
                                Intézmény neve: {{ seduc.institution_name }}
                            </div>
                            <div>
                                Végzettség megnevezése: {{ seduc.education_name }}
                            </div>
                            <div>
                                Kezdési idő: {{ seduc.date_from }}
                            </div>
                            <br>
                            {{ seduc }}
                        </div>
                    </v-card>
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
