<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    job:{
        type:Object,
    }
});


</script>

<template>
    <AuthenticatedLayout>
        {{ job }}
    </AuthenticatedLayout>
</template>