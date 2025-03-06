<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Advertised from './Profile/CompanyProfile/Components/Advertised.vue';

const props = defineProps({
    myjobs:{
        type:Objectm
    }
});

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Kezelőfelület
            </h2>
            <h3
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Ezen a felületen láthatja profilját és egyéb kiegészítő adatait.
            </h3>
        </template>
        <v-container class="py-12">
            <v-row>
                <v-col cols="12">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100 vuetify-container">
                            <Advertised :myjobs="myjobs"></Advertised>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </AuthenticatedLayout>
</template>
