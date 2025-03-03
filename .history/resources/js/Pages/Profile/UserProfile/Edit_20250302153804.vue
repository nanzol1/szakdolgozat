<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import UpdateProfileInterestsForm from './Partials/UpdateProfileInterestsForm.vue';
import UpdateProfileMoreInformationForm from './Partials/UpdateProfileMoreInformationForm.vue';
import UpdateIntroductionForm from './Partials/UpdateIntroductionForm.vue';
import UpdateProfilePicture from './Partials/UpdateProfilePicture.vue';
import { computed } from 'vue';

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

const getParameter = computed(() => {
    const query = window.location.hash.substring(1);
    return query;
});
</script>

<template>
    <AuthenticatedLayout>
    <Head title="Profile" />
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Profilom
            </h2>
            <h3 class="text-lg font-semibold leading-tight text-gray-800 mt-1">Ezen az oldalon tudja megtekinteni és szerkeszteni profilját.</h3>
            <div class="flex gap-3 md:gap-5 text-md mt-3">
                <Link href="#alapadatok" :class="['hover:text-sky-500 transition',{'pop':getParameter === '#alapadatok'}]">Alap adataim</Link>
                <Link href="#erdekeltsegeim" class="hover:text-sky-500 transition">Érdekeltségeim</Link>
            </div>
        </template>
        <v-container>
            <v-row>
                <!--<v-col cols="12" md="6">
                    <div
                        class="bg-white p-4 shadow sm:rounded-lg sm:p-8 flex flex-row gap-[2em]"
                    >
                        <UpdateProfilePicture/>
                    </div>
                </v-col>-->
                <v-col cols="12" md="6">
                    <div
                        class="bg-white p-4 shadow sm:rounded-lg sm:p-8 flex flex-row gap-[2em]"
                        id="alapadatok"
                    >
                        <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"/>
                    </div>
                </v-col>
                <v-col cols="12" md="6">
                    <div
                        class="bg-white p-4 shadow sm:rounded-lg sm:p-8 flex flex-row gap-[2em]"
                        id="erdekeltsegeim"
                    >
                        <UpdateProfileInterestsForm :category="category" :jobs_interests="jobs_interests"/>
                    </div>
                </v-col>
            </v-row>
        </v-container>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">


                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 flex flex-row gap-[2em]">

                    <UpdateIntroductionForm/>

                </div>

                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 flex flex-row gap-[2em]">

                    <UpdateProfileMoreInformationForm/>

                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style>
    html{
        scroll-behavior: smooth;
    }
    .pop{
        animation: pop .5s linear;
    }
    @keyframes pop {
        0%{
            transform: scale(1);
        }
        100%{
            transform: scale(1.1);
        }
    }
</style>
