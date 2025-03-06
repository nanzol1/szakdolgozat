<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import UpdateJobVacancies from './Partials/UpdateJobVacancies.vue';
import { computed, onMounted, ref } from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    categories:{
        type:Array,
    },
    employment_types:{
        type:Object,
    },
    work_schedules:{
        type:Object,
    }
});

const notifications = ref(null);

//Jelentkezések notification
const fetchNotifications = async () => {
    try{
        const response = await axios.get(route('cprofile.fetch.notifications'));
        notifications.value = response.data;
    }catch(error){
        console.error('Hiba történt: '+error);
    }
};
onMounted(() => {
    fetchNotifications();
    setInterval(fetchNotifications,60000);
});

</script>

<template>
    <AuthenticatedLayout>
    <Head title="Munkahirdetes" />
        <v-container fluid class="bg-white px-0 mx-0">
            <v-container class="!max-w-7xl mx-auto">
                <v-row>
                    <v-col>
                        <h2
                            class="text-xl font-semibold leading-tight text-gray-800"
                        >
                            Profil / Profilom
                        </h2>
                        <h3 class="text-lg font-semibold leading-tight text-gray-800 mt-1">Ezen az oldalon tudja megtekinteni és szerkeszteni profilját.</h3>
                        <div class="flex gap-3 md:gap-5 text-md mt-3">
                            <Link :href="route('cprofile.edit')" :class="['hover:text-sky-500 transition']">Alap adataim</Link>
                            <Link :href="route('cprofile.edit.jobs')" :class="['hover:text-sky-500 transition',{'text-sky-500':$page.url.includes('hirdetes')}]">Munka hirdetés</Link>
                            <Link :href="route('cprofile.myjobs')" :class="['hover:text-sky-500 transition']">Munkáim</Link>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </v-container>

        <div class="py-12 max-w-7xl mx-auto">
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                                <div
                                class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                                >
                                <UpdateJobVacancies class="max-w-xl" :categories="categories"  :employment_types="employment_types" :work_schedules="work_schedules"/>
                            </div>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </AuthenticatedLayout>
</template>
