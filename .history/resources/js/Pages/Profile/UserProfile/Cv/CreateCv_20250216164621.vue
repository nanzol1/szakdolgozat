<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { reactive } from 'vue';

const steps = [
    {component: Step1, key: 'step1'},
    {component: Step2, key: 'step2'},
    {component: Step3, key: 'step3'},
];

const currentStepIndex = ref(-1);

const mainFormData = reactive([
    mainField: '',
]);
</script>

<template>
    <Head title="CV Készítő"></Head>
    <AuthenticatedLayout>
        <div class="flex">
            <section class="w-full">
                <div v-if="currentStepIndex === -1">
                    <form @submit.prevent="handleMainFormSubmit">
                        <input v-model="mainFormData.mainField">
                        <button type="submit">Next</button>
                    </form>
                </div>

                <component v-else :is="steps[currentStepIndex].component" :formData="formData" @updateForm="updateForm"></component>
            </section>
            <section class="w-full">
                B
            </section>
        </div>
    </AuthenticatedLayout>
</template>