<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
const props = defineProps({
    users:{
        type:Number,
    }
});

const registeredUsers = computed(() => {
    console.log(props.users);
});

</script>

<template>
    <Head title="Dashboard"></Head>
    <AdminLayout>
        <section>
            <div class="block w-full dark:text-white">
                Regisztrált felhasználók: {{ props.users }}
                Az elmúlt 1 órában regisztrált felhasználók: {{ registeredUsers }}
            </div>
        </section>
    </AdminLayout>
</template>