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
</script>
<template>
    <Head :title="job.name"></Head>
    <GuestPageLayout :canLogin="canLogin" :canRegister="canRegister">
        <v-container class="flex-col pa-0" fluid>
            <v-container class="pa-0 bg-gray-500/20" fluid>
                <div class="w-full lg:max-w-7xl mx-auto">
                    <v-container>
                        <v-row>
                            <v-col>
                                {{ job }}
                                <div class="text-lg font-weight-black mb-2">
                                    <Link class="hover:text-sky-500 transition" :href="'/munkak?category[0]='+job.maincateg_id">{{ job.maincateg_name }}</Link> / <Link class="hover:text-sky-500 transition" :href="'/munkak?subcategory[0]='+job.subcateg_id">{{ job.subcateg_name }}</Link>
                                </div>
                                <div class="d-flex">
                                    <div class="flex sm:gap-10 flex-[100%] flex-1-0">
                                        <v-sheet :elevation="5" :height="200" :width="200">
                                            <v-img :src="'/storage/uploads/company_profile/'+job.created_by+'/'+job.profile_pict" cover></v-img>
                                        </v-sheet>
                                        <div class="flex flex-col flex-1 gap-3">
                                            <div>
                                                <div class="text-xl font-weight-black mb-2">
                                                    {{ job.name }}
                                                </div>
                                                <div class="text-lg font-weight-black flex gap-2">
                                                    <v-icon icon="mdi-briefcase"></v-icon>{{ job.company_name }}
                                                </div>
                                                <div class="text-xl font-weight-black">Havi bérezés</div>
                                                <div class="text-xl font-weight-black items-center flex gap-2">
                                                    <v-icon icon="mdi mdi-cash"></v-icon>{{ payment }}
                                                </div>
                                            </div>
                                            <div :class="['flex flex-row w-fit',{'h-full align-center':!user}]">
                                                <v-btn variant="tonal" @click="sendApplication" :disabled="isApplied" v-if="isJobActive" :class="['mr-3 !text-stone-50 !bg-gray-500 hover:!bg-sky-500 hover:scale-105']">
                                                    {{ computeApplied }}
                                                </v-btn>
                                                <v-btn variant="tonal" @click="revokeApplication" :disabled="!isApplied" v-if="isJobActive && user" :class="['mr-3 !text-stone-50 !bg-gray-500 hover:!bg-sky-500 hover:scale-105']">
                                                    Mégsem
                                                </v-btn>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex sm:gap-10 flex-[50%]">
                                        <v-expansion-panels>
                                          <v-expansion-panel
                                            title="Title"
                                            text="Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, ratione debitis quis est labore voluptatibus! Eaque cupiditate minima"
                                          >
                                          </v-expansion-panel>
                                        </v-expansion-panels>
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
                        <v-col sm="12">
                            <div class="text-lg font-weight-black mb-1" v-if="job.description">
                                Munka leírása:
                            </div>
                            <div>
                                {{ job.description }}
                            </div>
                        </v-col>
                        <v-col sm="12">
                            <div class="text-lg font-weight-black mb-1" v-if="job.requirements">
                                Elvárások:
                            </div>
                            <div>
                                {{ job.requirements }}
                            </div>
                        </v-col>
                    </v-row>
                </div>
            </v-container>
        </v-container>
    </GuestPageLayout>
</template>