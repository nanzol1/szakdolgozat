<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
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
            <input type="text" v-model="searchQuery" placeholder="Keresés..." class="dark:text-black">
            <template v-for="data in user">
                <div class="mb-3">
                    {{ data }}
                </div>
            </template>
        </div>
    </AdminLayout>
</template>