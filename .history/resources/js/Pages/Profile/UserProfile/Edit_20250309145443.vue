<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import UpdateProfileMoreInformationForm from './Partials/UpdateProfileMoreInformationForm.vue';
import UpdateIntroductionForm from './Partials/UpdateIntroductionForm.vue';

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
    }
});
</script>

<template>
    <AuthenticatedLayout>
    <Head title="Profil - Alap adatok" />
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
                        <Link :href="route('profile.edit')" :class="['hover:text-[#a67c52] transition',{'text-sky-500':$page.url === '/profile'}]">Alap adataim</Link>
                        <Link :href="route('profile.edit.interests')" class="hover:text-[#a67c52] transition">Érdekeltségeim</Link>
                        <Link :href="route('profile.edit.educations')" class="hover:text-[#a67c52] transition">Képzettségek</Link>
                        <Link :href="route('profile.edit.cv')" :class="['hover:text-[#a67c52] transition',{'text-sky-500':$page.url.includes('/cv')}]">Önéletrajz</Link>
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
                        id="alapadatok"
                    >
                        <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"/>
                    </div>
                </v-col>
                <v-col cols="12">
                    <div
                        class="bg-white p-4 shadow sm:rounded-lg sm:p-8 flex flex-row gap-[2em]"
                    >
                        <UpdateIntroductionForm/>
                    </div>
                </v-col>
                <v-col cols="12">
                    <div
                        class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                    >
                        <UpdatePasswordForm class="max-w-xl" />
                    </div>
                </v-col>
                <v-col cols="12">
                    <div
                        class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                    >
                        <DeleteUserForm class="max-w-xl" />
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
