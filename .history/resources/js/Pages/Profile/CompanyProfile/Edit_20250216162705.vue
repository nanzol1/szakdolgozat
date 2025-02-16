<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import UpdateJobVacancies from './Partials/UpdateJobVacancies.vue';
import { onMounted, ref } from 'vue';

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
        console.log("Most lett lekérve");
    }catch(error){
        console.error('Hiba történt: '+error);
    }
};

onMounted(() => {
    fetchNotifications();
    setInterval(fetchNotifications,6000000);
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Profile
            </h2>
            <Link :href="route('cprofile.myjobs')" class="dark:text-white">Hirdetett munkák</Link>
            Új jelentkező érkezett {{ notifications }}
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800 flex justify-between"
                >
                    <UpdateProfileInformationForm
                        class="max-w-xl"
                    />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <UpdateJobVacancies class="max-w-xl" :categories="categories"  :employment_types="employment_types" :work_schedules="work_schedules"/>
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
