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
        <div class="max-w-7xl mx-auto break-words">
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <template v-for="job in listedJobs">
                            <div class="mb-5">
                                <v-card append-icon="mdi mdi-open-in-new">
                                    <v-card-title>
                                        {{ job.name }}
                                    </v-card-title>
                                    <v-card-subtitle>
                                        {{ job.jobs_category.name }} / {{ job.jobs_subcategory.name }}
                                    </v-card-subtitle>
                                    <v-card-text>
                                        {{ job.description }}
                                    </v-card-text>
                                </v-card>
                                {{ job }}
                                <Link :href="route('cprofile.myjobs.edit',{id:job.id})">Szerkesztés</Link>
                            </div>
                        </template>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </AuthenticatedLayout>
</template>