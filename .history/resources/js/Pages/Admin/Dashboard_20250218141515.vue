<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
const props = defineProps({
    users:{
        type:Number,
    }
});

</script>

<template>
    <Head title="Dashboard"></Head>
    <AdminLayout>
        <section>
            <div class="block w-full dark:text-white">
                Regisztrált felhasználók: {{ props.users }}
            </div>
        </section>
    </AdminLayout>
</template>