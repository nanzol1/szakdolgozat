<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import UpdateProfileInterestsForm from './Partials/UpdateProfileInterestsForm.vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    category:{
        type:Object,
    },
    jobs_interests:{
        type:Array,
    },
});
</script>

<template>
    <AuthenticatedLayout>
    <Head title="Profil - Érdekeltségeim" />
    <v-container fluid class="px-0 mx-0  gradient-bg">
        <v-container class="!max-w-7xl mx-auto">
            <v-row>
                <v-col>
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800"
                    >
                        Profilom / Alap adatok
                    </h2>
                    <h3 class="text-lg font-semibold leading-tight text-gray-800 mt-1">Ezen az oldalon tudja megtekinteni és szerkeszteni profilját.</h3>
                    <div class="flex gap-3 md:gap-5 text-md mt-3">
                        <Link :href="route('profile.edit')" :class="['hover:text-[#a67c52] transition',{'text-gray-50':$page.url === '/profile'}]">Alap adataim</Link>
                        <Link :href="route('profile.edit.interests')" class="hover:text-[#a67c52] transition">Érdekeltségeim</Link>
                        <Link :href="route('profile.edit.educations')" class="hover:text-[#a67c52] transition">Képzettségek</Link>
                        <Link :href="route('profile.edit.cv')" :class="['hover:text-[#a67c52] transition']">Önéletrajz</Link>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </v-container>
    <div class="max-w-7xl mx-auto">
        <v-container>
            <v-row>
                <v-col cols="12">
                    <div
                        class="bg-white p-4 shadow sm:rounded-lg sm:p-8 flex flex-row gap-[2em]"
                        id="erdekeltsegeim"
                    >
                        <UpdateProfileInterestsForm :category="category" :jobs_interests="jobs_interests"/>
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
