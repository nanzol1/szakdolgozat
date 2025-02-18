<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
const props = defineProps({
    users:{
        type:Array,
    }
});

const registeredUsers = computed(() => {
    console.log(props.users?.users.created_at);
});

</script>

<template>
    <Head title="Dashboard"></Head>
    <AdminLayout>
        <section>
            <div class="block w-full dark:text-white">
                Regisztrált felhasználók: {{ props.users.count }}
                Az elmúlt 1 órában regisztrált felhasználók: {{ registeredUsers }}
            </div>
        </section>
    </AdminLayout>
</template>