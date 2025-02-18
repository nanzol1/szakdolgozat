<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import _ from 'lodash';

const searchQuery = ref('');

const props = defineProps({
    users:{
        type:Object,
    }
});

const debounceSearch = _.debounce(() => {

},500);

</script>
<template>
    <Head title="Munkavállalók"></Head>
    <AdminLayout>
        <div class="dark:text-white">
            <template v-for="user in users">
                <div class="mb-3">
                    {{ user }}
                </div>
            </template>
        </div>
    </AdminLayout>
</template>