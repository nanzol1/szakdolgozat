<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Applied from './Profile/UserProfile/Components/Applied.vue';

const props = defineProps({
    myjobs:{
        type:Object,
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
                Kezelőfelület
            </h3>
        </template>
        <v-container class="py-12">
            <v-row>
                <v-col cols="12">
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
