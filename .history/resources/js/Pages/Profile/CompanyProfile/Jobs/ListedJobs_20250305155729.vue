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
                                <v-card class="listedjobs-card pa-3 relative">
                                    <div class="absolute right-3 top-3">
                                        <v-icon icon="mdi mdi-open-in-new"></v-icon>
                                    </div>
                                    <v-card-title>
                                        {{ job.name }}
                                    </v-card-title>
                                    <v-card-subtitle>
                                        {{ job.jobs_category.name }} / {{ job.jobs_subcategory.name }}
                                    </v-card-subtitle>
                                    <v-card-text>
                                        {{ job.description }}
                                    </v-card-text>
                                    <v-card-actions class="listedjobs-card-actions">
                                        <Link :href="route('cprofile.myjobs.edit',{id:job.id})">Szerkesztés</Link>
                                        <Link :href="route('cprofile.myjobs.edit',{id:job.id})">Szerkesztés</Link>
                                    </v-card-actions>
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
<style scoped>
    .listedjobs-card::before{
        content: "";
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        position: absolute;
        background-color: rgba(200,200,200,.5);
        opacity: 0;
        transition: all .3s ease;
        z-index: 2;
    }
    .listedjobs-card:hover::before{
        opacity: 1;
        transition: all .3s ease;
    }
    .listedjobs-card-actions{
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        gap: 2em;
    }
</style>