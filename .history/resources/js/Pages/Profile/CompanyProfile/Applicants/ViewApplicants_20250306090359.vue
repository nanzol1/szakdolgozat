<script setup>
import { Head, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, ref, watch } from 'vue';

const props = defineProps({
    applications:{
        type:Object,
    },
    job_id:{
        type:String,
    },
    statuses:{
        type:Object,
    },
    jobInfos:{
        type:Object,
    }
});

const isPopup = ref(false);
const selectedApplicant = ref(null);
const status = ref('');

const updateStatus = async (id,applicantId,statusId) => {
    await router.put(route('cprofile.job.updatestatus',{id:id,jelentkezoId:applicantId,statusId:statusId}));
    status.value = statusId;
};
const showPopUp = async (applicantId) => {
    try{
        const response = await axios.get(route('cprofile.job.applicant',{id:props.job_id,jelentkezoId:applicantId}));
        selectedApplicant.value = response.data.applicant;
        status.value = response.data.applicant.status_id;
        isPopup.value = true;
    } catch (error){
        console.error('Hiba történt a lekérés közben: ',error);
    }
};
watch(status,(newVal) => {
    if(status.value < 2){
        router.put(route('cprofile.job.updatestatus',{id:props.job_id,jelentkezoId:selectedApplicant.value.user_id,statusId:2}));
        status.value = 2;
    }else{
        updateStatus(props.job_id,selectedApplicant.value.user_id,newVal);
    }
});
const closePopup = () => {
    isPopup.value = false;
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Jelentkezők"></Head>
        <v-container fluid class="bg-white px-0 mx-0">
            <v-container class="!max-w-7xl mx-auto">
                <v-row>
                    <v-col>
                        <h2
                            class="text-xl font-semibold leading-tight text-gray-800"
                        >
                            Munka / Jelentkező(k)
                        </h2>
                        <h3 class="text-lg font-semibold leading-tight text-gray-800 mt-1">Ezen az oldalon tudja megtekinteni és szerkeszteni profilját.</h3>
                        <div class="flex gap-3 md:gap-5 text-md mt-3">
                            <Link :href="route('cprofile.edit')" :class="['hover:text-sky-500 transition']">Alap adataim</Link>
                            <Link :href="route('cprofile.create.job')" class="hover:text-sky-500 transition">Munka hirdetés</Link>
                            <Link :href="route('cprofile.myjobs')" :class="['hover:text-sky-500 transition',{'text-sky-500':$page.url.includes('cmunka')}]">Munkáim</Link>
                        </div>
                        <v-divider class="my-3"></v-divider>
                        <div class="font-medium">
                            Munka megnevezése:
                            <div>
                                {{ jobInfos.name }}
                            </div>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </v-container>
        <div class="max-w-7xl mx-auto">
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <section>
                            Jelentkező(k)
                            <div v-for="apps in applications">
                            {{ apps }}

                                <v-card class="mb-3 pa-3 listedjobs-card">
                                    <v-card-title>
                                        {{ apps.user.name }}
                                        <v-divider></v-divider>
                                    </v-card-title>
                                    <v-card-subtitle>
                                        <a :href="'mailto:'+apps.user.email">{{ apps.user.email }}</a> / <a :href="'tel:'+apps.user.phone_number">{{ apps.user.phone_number }}</a>
                                    </v-card-subtitle>
                                    <v-card-text>
                                        <v-divider></v-divider>
                                        {{ apps.user.introduction }}
                                    </v-card-text>
                                    <div class="listedjobs-card-actions">
                                        <v-btn @click="showPopUp(apps.user_id)" class="hover:bg-sky-500 hover:text-gray-50">Megtekintem</v-btn>
                                        <v-btn :href="'/storage/uploads/cv/'+apps.user.cv_file" :download="apps.user.name+'.pdf'" class="hover:bg-sky-500 hover:text-gray-50" v-if="apps.user.cv_file">Önéletrajz letöltése</v-btn>
                                    </div>
                                </v-card>
                            </div>
                            <v-overlay v-model="isPopup" class="align-center justify-center" width="100%">
                                <v-card class="education-card pa-4 !border-2 !border-solid !border-stone-950 w-[90%] xl:w-[50%] lg:w-[90%] mx-auto">
                                    <div class="absolute right-3 top-3">
                                        <v-icon icon="mdi mdi-close" @click="isPopup = false;"></v-icon>
                                    </div>
                                    <div>
                                        {{ selectedApplicant.name }}
                                    </div>
                                    <div>
                                        {{ selectedApplicant.email }}
                                    </div>
                                    <div>
                                        {{ selectedApplicant.phone }}
                                    </div>
                                    {{ statuses }}
                                    <div class="flex gap-[10em] justify-evenly">
                                        <template v-for="(sts, index) in statuses" :key="sts.id">
                                            <div v-if="sts.name !== 'Jelentkezett'" class="relative">
                                                <input type="radio" v-model="status" :id="sts.id" :name="sts.name" :value="sts.id" 
                                                :class="['!border-1 !border-solid !border-black radio'+index]">
                                                <div :class="['custom-lines custom-line'+index]"></div>
                                                <label :for="sts.id" class="absolute-label">{{ sts.name }}</label>
                                            </div>
                                        </template>
                                        <!--:disabled="index < status.valueOf()"-->
                                    </div>
                                </v-card>
                            </v-overlay>
                        </section>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
.custom-lines{
    width: 150px;
    height: 5px;
    background-color: red;
    position: absolute;
    right: -180px;
    top: 50%;
    border-radius: 20px;
}
.absolute-label{
    position: absolute;
    bottom: -20px;
    left: 50%;
    transform: translate(-50%);
    width: max-content;
}

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
        top: -50%;
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