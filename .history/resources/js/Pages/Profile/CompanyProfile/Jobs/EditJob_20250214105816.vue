<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    
});


</script>

<template>
    <AuthenticatedLayout>
        {{ job }}
    </AuthenticatedLayout>
</template>