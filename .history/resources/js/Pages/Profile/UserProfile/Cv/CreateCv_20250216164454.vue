<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { reactive } from 'vue';

const form = reactive([

]);
</script>

<template>
    <Head title="CV Készítő"></Head>
    <AuthenticatedLayout>
        <div class="flex">
            <section class="w-full">
                <div v-if="currentStepIndex === -1">
                    <form @submit.prevent="handleMainFormSubmit">
                        <input type="text">
                        <button type="submit">Next</button>
                    </form>
                </div>

                <component v-else :is="steps[currentStepIndex].component" :formData="formData"></component>
            </section>
            <section class="w-full">
                B
            </section>
        </div>
    </AuthenticatedLayout>
</template>