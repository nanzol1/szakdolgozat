<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import UpdateProfileMoreInformationForm from './Partials/UpdateProfileMoreInformationForm.vue';
import { ref } from 'vue';

defineProps({
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
                    <v-btn @click="newEducation = !newEducation" class="hover:bg-sky-500 hover:text-gray-50 mb-3">Új végzettség felvétele</v-btn>
                    <v-expand-transition>
                        <div
                            class="bg-white p-4 shadow sm:rounded-lg sm:p-8 flex flex-row gap-[2em]"
                            v-if="newEducation"
                        >
                            <UpdateProfileMoreInformationForm :educations="educations"/>
                        </div>
                    </v-expand-transition>
                </v-col>
                <v-col cols="12" v-for="educ in educations" :key="educ.id">
                    <div
                        class="bg-white p-4 shadow sm:rounded-lg sm:p-8 flex flex-row gap-[2em]"
                    >
                        {{ educ }}
                    </div>
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
