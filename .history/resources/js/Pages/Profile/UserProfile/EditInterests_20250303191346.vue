<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import UpdateProfileInterestsForm from './Partials/UpdateProfileInterestsForm.vue';
import UpdateProfileMoreInformationForm from './Partials/UpdateProfileMoreInformationForm.vue';
import UpdateIntroductionForm from './Partials/UpdateIntroductionForm.vue';

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
    <Head title="Profile" />
    <v-container fluid class="bg-white px-0 mx-0">
        <v-container class="!max-w-7xl mx-auto">
            <v-row>
                <v-col>
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800"
                    >
                        Érdekeltségeim
                    </h2>
                    <h3 class="text-lg font-semibold leading-tight text-gray-800 mt-1">Ezen az oldalon tudja megtekinteni és szerkeszteni profilját.</h3>
                    <div class="flex gap-3 md:gap-5 text-md mt-3">
                        <Link href="#alapadatok" class="hover:text-sky-500 transition">Alap adataim</Link>
                        <Link href="#erdekeltsegeim" class="hover:text-sky-500 transition">Érdekeltségeim</Link>
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
