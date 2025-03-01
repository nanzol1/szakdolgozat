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
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Dashboard
            </h2>
        </template>

    </AuthenticatedLayout>
</template>
