<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link, router } from '@inertiajs/vue3';

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
                    <template v-for="job in listedJobs">
                        <v-col cols="12" xl="6">
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
                                    <v-card-text class="min-h-32 max-h-28 overflow-hidden">
                                        {{ job.description }}
                                    </v-card-text>
                                    <div class="listedjobs-card-actions">
                                        <v-btn class="hover:bg-sky-500 hover:text-gray-50" @click="router.visit(route('cprofile.myjobs.edit',{id:job.id}))">Szerkesztés</v-btn>
                                    </div>
                                </v-card>
                            </div>
                        </v-col>
                    </template>
                </v-row>
            </v-container>
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
    .listedjobs-card::before{
        content: "";
        left: 0;
        bottom: 0;
        width: 100%;
        height: 0;
        position: absolute;
        background-color: rgba(200,200,200,.5);
        opacity: 0;
        transition: all .3s ease;
        z-index: 2;
    }
    .listedjobs-card:hover::before{
        opacity: 1;
        height: 100%;
        transition: all .3s ease;
    }
    .listedjobs-card-actions{
        position: absolute;
        right: 50%;
        top: -20%;
        transform: translate(50%,-50%);
        display: flex;
        gap: 2em;
        z-index: 3;
        opacity: 1;
        transition: .3s all ease;
    }
    .listedjobs-card:hover .listedjobs-card-actions{
        top: 50%;
        opacity: 1;
        transition: .3s all ease;
    }
</style>