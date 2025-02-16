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
                <component :is="steps[currentStepIndex].component" :formData="formData"></component>
            </section>
            <section class="w-full">
                B
            </section>
        </div>
    </AuthenticatedLayout>
</template>