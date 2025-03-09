<script setup>

import GuestPageLayout from '@/Layouts/GuestPageLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import { NCarousel, NCard } from 'naive-ui'


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
    jobs:{
        type:Object,
    }
});

const user = usePage().props.auth.user;
const isApplied = ref(false);
const isAppliedText = ref('Jelentkezem');
const availWidth = ref(window.innerWidth);

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
    window.addEventListener('resize', updateWidth);

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
    const diffDays = Math.floor(diff / (1000 * 60 * 60 *24));
    const daysText = diffDays === 0 ? ' Mai napon' : ` ${diffDays} nappal ezelőtt`;
    return posted.toLocaleDateString('hu-HU') + daysText;
});

const computePostDates = computed(() => {
    return Object.values(props.jobs || {})?.map(e => {
    const posted = new Date(e.created_at);
    const today = new Date();
    
    today.setHours(0, 0, 0, 0);
    posted.setHours(0, 0, 0, 0);

    const diffMs = today - posted;
    const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));

    const formattedDate = posted.toLocaleDateString('hu-HU', {
      year: 'numeric',
      month: '2-digit',
      day: '2-digit'
    });

        return `${formattedDate} (${diffDays} nappal ezelőtt)`;
    });
});

const updateWidth = () => {
    availWidth.value = window.innerWidth;
};

const computedSlidesPerView = computed(() => {
    if(availWidth.value <= 576){
        return 1;
    }else if(availWidth.value > 576 && availWidth.value <= 768){
        return 2;
    }else{
        return 3;
    }
});
</script>
<template>
    <Head :title="job.name"></Head>
    <GuestPageLayout :canLogin="canLogin" :canRegister="canRegister">
        <v-container class="flex-col pa-0" fluid>
            <v-container class="pa-0 gradient-bg" fluid>
                <div class="w-full lg:max-w-7xl mx-auto">
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <div class="text-lg font-weight-black mb-2">
                                    <Link class="hover:text-sky-500 transition" :href="'/munkak?category[0]='+job.jobs_category.id">{{ job.jobs_category.name }}</Link> / <Link class="hover:text-sky-500 transition" :href="'/munkak?subcategory[0]='+job.jobs_subcategory.id">{{ job.jobs_subcategory.name }}</Link>
                                </div>
                                <div class="flex sm:gap-10">
                                    <v-sheet :elevation="5" :height="200" :width="200" class="!hidden sm:!block" v-if="job.companies.profile_pict">
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
                                    <v-sheet :elevation="5" :height="200" :width="200" class="!hidden sm:!block" v-else>
                                        <v-img :lazy-src="'/storage/images/image_nofound.png'" :src="'/storage/images/image_nofound.png'" cover>
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
                                            <v-btn variant="tonal" @click="sendApplication" :disabled="isApplied" v-if="isJobActive" :class="['mr-3 max-sm:!h-full max-sm:!py-3 !text-stone-50 !bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105']">
                                                {{ computeApplied }}
                                            </v-btn>
                                            <v-btn variant="tonal" @click="revokeApplication" :disabled="!isApplied" v-if="isJobActive && user" :class="['mr-3 !text-stone-50 !bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105']">
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
                        <v-col sm="8" class="break-words">
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
                            <v-expansion-panels variant="popout" model-value="0">
                                    <v-expansion-panel
                                        title="Munka adatai"
                                        ripple
                                        collapse-icon="mdi-minus" 
                                        expand-icon="mdi-plus"
                                    >
                                        <v-expansion-panel-text>
                                            <v-row>
                                                <v-col>
                                                    <div>Munkarend:</div>
                                                    <div v-for="ws in job.work_schedules" :key="ws.id">
                                                        {{ws.name}}
                                                    </div>
                                                </v-col>
                                            </v-row>
                                            <v-row>
                                                <v-col>
                                                    <div>Foglalkoztatottság:</div>
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
                    <v-row class="sm:!mt-10">
                        <v-col>
                            <n-carousel :slides-per-view="computedSlidesPerView" :space-between="20" :loop="false"
                            show-arrow draggable
                            >
                                <Link v-for="(job, index) in jobs" :key="job.id" :href="route('jobs.view',{id:job.id})">
                                    <n-card
                                      :segmented="{
                                        content: true,
                                        footer: 'soft',
                                      }"
                                      size="medium"
                                      footer-class="max-h-32 min-h-32 text-ellipsis overflow-hidden"
                                      action-class="text-center"
                                    >
                                      {{job.name}}
                                      <template #footer>
                                        {{job.description}}
                                      </template>
                                      <template #action>
                                        <div class="text-center">{{computePostDates[index]}}</div>
                                      </template>
                                    </n-card>
                                </Link>
                                <template #arrow="{ prev, next }">
                                  <div class="custom-arrow">
                                    <button type="button" class="custom-arrow--left hover:!bg-sky-500" @click="prev">
                                      <v-icon icon="mdi mdi-chevron-left" size="30px"></v-icon>
                                    </button>
                                    <button type="button" class="custom-arrow--right hover:!bg-sky-500" @click="next">
                                      <v-icon icon="mdi mdi-chevron-right" size="30px"></v-icon>
                                    </button>
                                  </div>
                                </template>
                            </n-carousel>
                        </v-col>
                    </v-row>
                </div>
            </v-container>
        </v-container>
    </GuestPageLayout>
</template>
<style scoped>

.custom-arrow {
	display: flex;
	position: absolute;
	bottom: 0;
	right: 0;
	justify-content: space-between;
	gap: 2em;
	width: 100%;
	align-items: center;
    pointer-events: none;
}
.custom-arrow button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 50px;
  height: 50px;
  color: black;
  background-color: rgba(255, 255, 255, 0.1);
  border-width: 0;
  border-radius: 8px;
  transition: background-color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  cursor: pointer;
  pointer-events: all;
}


.custom-arrow button:active {
  transform: scale(0.95);
  transform-origin: center;
}

.gradient-bg{
    background: linear-gradient(to bottom, #c7a07a97, #E4C6A2);
}
</style>