<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
const props = defineProps({
    users:{
        type:Object,
    }
});

</script>

<template>
    <AdminLayout>
        ss: {{ props.users }}
    </AdminLayout>
</template>