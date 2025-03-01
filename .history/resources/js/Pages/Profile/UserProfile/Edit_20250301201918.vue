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
    <Head title="Profile" />
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Profilom
            </h2>
            <h3 class="text-xl font-semibold leading-tight">Ezen az oldalon tudja megtekinteni, szerkeszteni profilját.</h3>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800  flex flex-row gap-[2em]"
                >
                    <UpdateProfilePicture/>
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800  flex flex-row gap-[2em]"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"/>
                </div>


                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800  flex flex-row gap-[2em]">

                    <UpdateProfileInterestsForm class="max-w-xl" :category="category" :jobs_interests="jobs_interests"/>
                    
                </div>

                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800  flex flex-row gap-[2em]">

                    <UpdateIntroductionForm/>

                </div>

                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800  flex flex-row gap-[2em]">

                    <UpdateProfileMoreInformationForm/>

                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
