<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    myjobs:{
        type:Array,
    }
});
</script>

<template>
    <Head title="Munkáim"></Head>
    <AuthenticatedLayout>
        <div>
            {{myjobs}}
        </div>
    </AuthenticatedLayout>
</template>