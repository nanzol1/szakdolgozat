<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';


const props = defineProps({
    user:{
        type:Array,
    },
});
console.log(props.user[0].id);
const resetPassword = () => {
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