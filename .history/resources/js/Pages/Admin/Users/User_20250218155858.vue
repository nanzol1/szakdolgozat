<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import _ from 'lodash';
const props = defineProps({
    user:{
        type:Object,
    },
});


</script>
<template>
    <Head title="Munkavállalók"></Head>
    <AdminLayout>
        <div class="dark:text-white">
            <template v-for="data in user">
                <div class="mb-3">
                    {{ data }}
                </div>
            </template>
        </div>
    </AdminLayout>
</template>