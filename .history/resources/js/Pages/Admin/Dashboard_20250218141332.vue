<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
const props = defineProps({
    users:{
        type:Object,
    }
});

</script>

<template>
    <Head title="Dashboard"></Head>
    <AdminLayout>
        <div class="block">

        </div>
    </AdminLayout>
</template>