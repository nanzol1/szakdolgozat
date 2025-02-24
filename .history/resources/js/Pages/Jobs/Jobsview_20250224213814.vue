<script setup>

import GuestPageLayout from '@/Layouts/GuestPageLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
    canLogin:{
        type:Boolean
    },
    canRegister:{
        type:Boolean
    },
    job:{
        type:Object,
    },
    application:{
        type:Object,
    },
    flexCol:{
        type:String
    }
});

const user = usePage().props.auth.user;

const isApplied = ref(false);
const isAppliedText = ref('Jelentkezem');

const payment = computed(() => {
    if(props.job.payment === null){
        return 'Nincs megadva havi bér';
    }else{
        let price = 0;
        price = props.job.payment.toLocaleString('hu-HU',{
            style:'currency',
            currency:'Huf',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        });
        return price;
    }
});

onMounted(() => {
    const isAppliedArray = Object.values(props.application);
    isAppliedArray.forEach(element => {
        if((element.job_id === props.job.id || element.user_id === user.id) && element.is_deleted !== 1){
            isApplied.value = true;
        }else{
            isApplied.value = false;
        }
    });

});



const computeApplied = computed(() => {
    if(user){
        if(isApplied.value){
            return isAppliedText.value = "Már jelentkeztem";
        }else{
            return isAppliedText.value = "Jelentkezem";
        }
    }else{
        return isAppliedText.value = "A jelentkezéshez regisztráció szükséges";
    }
});

const isJobActive = computed(() => {
    if(props.job.active === 1){
        return true;
    }else{
        return false;
    }
});

const sendApplication = () => {
    if(user){
        router.post(route('jobs.apply',{id:props.job.id}),{
        job_id: props.job.id,
        },{
            onSuccess: () => {
                isApplied.value = true;
            },
            onError: (error) => {
                console.log(error);
            }
        });
    }else{
        router.get(route('register',{type:'munkavallalo'}));
    }
};

const revokeApplication = () => {
    router.post(route('jobs.apply.revoke',{id:props.job.id}),{
        job_id: props.job.id,
    },{
        onSuccess: () => {
            isApplied.value = false;
        },
        onError: (error) => {
            console.log(error);
        }
    });
};

const computePostDate = computed(() => {
    let posted = new Date(props.job.created_at);
    let today = new Date();
    let diff = Math.abs(today - posted);
    return posted.toLocaleDateString('hu-HU') + ' (' + Math.floor(diff / (1000 * 60 * 60 *24)) + ' nappal ezelőtt)';
});
</script>
<template>
    <Head :title="job.name"></Head>
    <GuestPageLayout :canLogin="canLogin" :canRegister="canRegister">
        <v-container class="flex-col pa-0" fluid>
            <v-container class="pa-0 bg-gray-500/20" fluid>
                <div class="w-full lg:max-w-7xl mx-auto">
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <div class="text-lg font-weight-black mb-2">
                                    <Link class="hover:text-sky-500 transition" :href="'/munkak?category[0]='+job.jobs_category.id">{{ job.jobs_category.name }}</Link> / <Link class="hover:text-sky-500 transition" :href="'/munkak?subcategory[0]='+job.jobs_subcategory.id">{{ job.jobs_subcategory.name }}</Link>
                                </div>
                                <div class="flex sm:gap-10">
                                    <v-sheet :elevation="5" :height="200" :width="200" class="!hidden sm:!block">
                                        <v-img :lazy-src="'/storage/images/image_nofound.png'" :src="'/storage/uploads/company_profile/'+job.created_by+'/'+job.companies.profile_pict" cover>
                                            <template v-slot:placeholder>
                                              <div class="d-flex align-center justify-center fill-height">
                                                <v-progress-circular
                                                  color="grey-lighten-4"
                                                  indeterminate
                                                ></v-progress-circular>
                                              </div>
                                            </template>
                                        </v-img>
                                    </v-sheet>
                                    <div class="flex flex-col flex-1 gap-3">
                                        <div>
                                            <div class="text-xl font-weight-black mb-2">
                                                {{ job.name }}
                                            </div>
                                            <div class="text-lg font-weight-black flex gap-2">
                                                <v-icon icon="mdi-briefcase"></v-icon>{{ job.companies.company_name }}
                                            </div>
                                            <div class="text-xl font-weight-black">Havi bérezés</div>
                                            <div class="text-xl font-weight-black items-center flex gap-2">
                                                <v-icon icon="mdi mdi-cash"></v-icon>{{ payment }}
                                            </div>
                                        </div>
                                        <div :class="['flex flex-row w-full sm:w-fit',{'h-full align-center':!user}]">
                                            <v-btn variant="tonal" @click="sendApplication" :disabled="isApplied" v-if="isJobActive" :class="['mr-3 max-sm:!h-full max-sm:!py-3 !text-stone-50 !bg-gray-500 hover:!bg-sky-500 hover:scale-105']">
                                                {{ computeApplied }}
                                            </v-btn>
                                            <v-btn variant="tonal" @click="revokeApplication" :disabled="!isApplied" v-if="isJobActive && user" :class="['mr-3 !text-stone-50 !bg-gray-500 hover:!bg-sky-500 hover:scale-105']">
                                                Mégsem
                                            </v-btn>
                                        </div>
                                    </div>
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>
                </div>
            </v-container>
            <v-container>
                <div class="w-full md:max-w-7xl mx-auto">
                    <v-row>
                        <v-col sm="8">
                            <div v-if="job.description">
                                <div class="text-lg font-weight-black mb-1">
                                    Munka leírása:
                                </div>
                                <div>
                                    {{ job.description }}
                                </div>
                            </div>

                            <div v-if="job.requirements">
                                <div class="text-lg font-weight-black mb-1 mt-5">
                                    Elvárások:
                                </div>
                                <div>
                                    {{ job.requirements }}
                                </div>
                            </div>
                        </v-col>
                        <v-col md="4" cols="12">
                            <v-expansion-panels variant="popout">
                                    <v-expansion-panel
                                        title="Munka adatai"
                                        ripple
                                    >
                                        <v-expansion-panel-text>
                                            <v-row>
                                                <v-col>
                                                    <div v-for="ws in job.work_schedules" :key="ws.id">
                                                        {{ws.name}}
                                                    </div>
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <v-col>
                                                    <div v-for="et in job.employment_type" :key="et.id">
                                                        {{et.name}}
                                                    </div>
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <v-col>
                                                    <div>Havi bérezés</div>
                                                    <div>{{ payment }}</div>
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <v-col>
                                                    <div>Közzé téve:</div>
                                                    <div>{{ computePostDate }}</div>
                                                </v-col>
                                            </v-row>
                                        </v-expansion-panel-text>
                                    </v-expansion-panel>
                            </v-expansion-panels>
                        </v-col>
                    </v-row>
                </div>
            </v-container>
        </v-container>
    </GuestPageLayout>
</template>