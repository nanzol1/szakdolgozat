<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Advertised from './Profile/CompanyProfile/Components/Advertised.vue';

const props = defineProps({
    myjobs:{
        type:Object,
    },
    jobs_count:{
        type:String,
    },
    sum_applicants:{
        type:String,
    }
});

</script>

<template>
    <AuthenticatedLayout>
        <Head title="Kezelőfelület" />
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
                <v-col cols="12" lg="6" v-if="false">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100 vuetify-container">
                            Összesen meghirdetett munka: {{ jobs_count }} / Összes jelentkező a munkákra: {{ sum_applicants }}
                        </div>
                    </div>
                </v-col>
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
