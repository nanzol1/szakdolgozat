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
        <v-container class="py-12">
            <v-row>
                <v-col cols="12" :xl="selectedJob ? 6 : 12" lg="12" md="12">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100 vuetify-container">
                            <Applied :isShort="false" :myjobs="myjobs" @showSelectedJob="handleSelect"></Applied>
                        </div>
                    </div>
                </v-col>
                <v-expand-transition>
                    <v-col cols="12" xl="6" lg="12" md="12" v-if="selectedJob">
                        <v-card>
                            <v-card-title :class="[{'bg-gray-300':selectedJob.job_active === 'Inaktív'},{'bg-green-500':selectedJob.job_active === 'Aktív'}]">
                                <div class="flex flex-col">
                                    <span class="font-weight-black">{{selectedJob.job_name}}</span>
                                    <span class="font-weight-medium">{{ selectedJob.job_cname }}</span>
                                </div>
                            </v-card-title>
                            <v-card-subtitle class="mt-3">
                                <div class="flex justify-between mb-1">
                                    <span>{{selectedJob.job_catname}} / {{ selectedJob.job_scatname }}</span>
                                    <span class="font-weight-black">{{divideAddress}}</span>
                                </div>
                                <v-divider class="border-opacity-100" :thickness="2"></v-divider>
                            </v-card-subtitle>
                            <v-card-text>
                                <div class="flex flex-col mb-2">
                                    <span class="font-weight-bold text-subtitle-2">Fizetés:</span>
                                    <div class="flex align-center gap-1">
                                        <span :class="['hidden sm:block',{'!hidden':selectedJob.job_payment <= 0}]"><v-icon icon="mdi mdi-cash"></v-icon></span><span>{{payment}}</span>
                                    </div>
                                    <v-divider class="border-opacity-100" :thickness="2" :class="['my-2']"></v-divider>
                                    <span class="font-weight-bold text-subtitle-2">Leírás:</span>
                                    <span>{{selectedJob.job_description}}</span>
                                    <v-divider class="border-opacity-100" :thickness="2" :class="['my-2']"></v-divider>
                                    <span class="font-weight-bold text-subtitle-2">Elvárások:</span>
                                    <span>{{selectedJob.job_requirements}}</span>
                                    <div v-if="selectedJob.work_schedules?.length > 0">
                                        <v-divider class="border-opacity-100" :thickness="2" :class="['my-2']"></v-divider>
                                        <span class="font-weight-bold text-subtitle-2">Munkarend:</span>
                                        <div class="flex gap-2 md:gap-3">
                                            <div v-for="schedules in selectedJob.work_schedules">
                                                <span>{{schedules.name}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="selectedJob.employment_types?.length > 0">
                                        <v-divider class="border-opacity-100" :thickness="2" :class="['my-2']"></v-divider>
                                        <span class="font-weight-bold text-subtitle-2">Foglalkoztatottság:</span>
                                        <div class="flex gap-2 md:gap-3">
                                            <div v-for="employments in selectedJob.employment_types">
                                                <span>{{employments.name}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-expand-transition>
            </v-row>
        </v-container>
        <template v-for="job in listedJobs">
            <div class="dark:text-white mb-5">
                {{ job }}
                <Link :href="route('cprofile.myjobs.edit',{id:job.id})">Szerkesztés</Link>
            </div>
        </template>
    </AuthenticatedLayout>
</template>