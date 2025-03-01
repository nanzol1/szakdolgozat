<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Applied from '../Components/Applied.vue';

const props = defineProps({
    myjobs:{
        type:Object,
    }
});
</script>

<template>
    <AuthenticatedLayout>
    <Head title="Munkáim"></Head>

        <v-container class="py-12">
            <v-row>
                <v-col cols="6">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100 vuetify-container">
                            <Applied :isShort="true" :myjobs="myjobs"></Applied>
                        </div>
                    </div>
                </v-col>
                <v-col cols="6">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100 vuetify-container">
                            <Applied :isShort="true" :myjobs="myjobs"></Applied>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </AuthenticatedLayout>
</template>