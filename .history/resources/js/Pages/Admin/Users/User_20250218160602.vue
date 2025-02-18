<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';


const props = defineProps({
    user:{
        type:Object,
    },
});

const resetPassword = () => {
    let result = '';
    result += Math.random().toString(36).slice(2);
    router.post(route('admin.user.randompassword',{id:props.user.id}));
}
</script>
<template>
    <Head title="Munkavállalók"></Head>
    <AdminLayout>
        <div class="dark:text-white">
            <template v-for="data in user">
                <div class="mb-3">
                    {{ data }}
                </div>
                <div>
                    <button @click="resetPassword">Jelszó alaphelyzetbe</button>
                </div>
            </template>
        </div>
    </AdminLayout>
</template>