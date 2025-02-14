<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';

    const props = defineProps({
        listedJobs: {
            type:Array,
        }
    });
</script>

<template>
    <Head title="Munkák"></Head>
    <AuthenticatedLayout>
        <template v-for="job in listedJobs">
            <div class="dark:text-white mb-5">
                {{ job }}
                <Link :href="route('cprofile.myjobs.edit',id:job.id)">Szerkesztés</Link>
            </div>
        </template>
    </AuthenticatedLayout>
</template>