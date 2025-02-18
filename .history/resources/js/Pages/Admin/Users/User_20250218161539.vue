<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';


const props = defineProps({
    user:{
        type:Array,
    },
});

const resetPassword = () => {
    router.post('randomPassword');
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
                <form>
                    <div>
                    <button @click="resetPassword">Jelszó alaphelyzetbe</button>
                </div>
                </form>
            </template>
        </div>
    </AdminLayout>
</template>