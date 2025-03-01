<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const asd = ref('');
const states = ref(['California', 'Colorado', 'Florida', 'Georgia', 'Texas', 'Wyoming']);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        
        <template #header>
            <h2
                class="text-h4 font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Dashboard
            </h2>
        </template>
        <v-container class="py-12">
            <v-row>
                <v-col cols="12" lg="4" md="6">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100 vuetify-container">
                            You're logged in!
                        </div>
                    </div>
                </v-col>
                <v-col cols="12" lg="4" md="6">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100 vuetify-container">
                            You're logged in!
                        </div>
                    </div>
                </v-col>
                <v-col cols="12" lg="4" md="6">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100 vuetify-container">
                            You're logged in!
                        </div>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </AuthenticatedLayout>
</template>
