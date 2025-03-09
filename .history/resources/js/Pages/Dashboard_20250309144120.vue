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
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Kezelőfelület
            </h2>
            <h3
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Ezen a felületen láthatja profilját és egyéb kiegészítő adatait.
            </h3>
        </template>
        <div class="max-w-7xl mx-auto">
            
        </div>
    </AuthenticatedLayout>
</template>
