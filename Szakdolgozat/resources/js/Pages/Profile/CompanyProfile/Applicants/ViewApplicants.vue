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
const clickedDiv = ref(null);
let clickTimeout = null;

watch(clickedDiv, (e) => {
    console.log(e);
    if(e){
        if(clickTimeout){
            clearTimeout(clickTimeout);
        }

        clickTimeout = setTimeout(() => {
            clickedDiv.value = null;
        }, 30000);
    }
});
const updateStatus = async (id,applicantId,statusId) => {
    await router.put(route('cprofile.job.updatestatus',{id:id,jelentkezoId:applicantId,statusId:statusId}));
    status.value = statusId;
    selectedApplicant.value.status_id = statusId;
    if(statusId === 4){
        selectedApplicant.value.status_name = 'Felvételt nyert';
    }
    if(statusId === 5){
        selectedApplicant.value.status_name = 'Nem nyert felvételt';
    }
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
        selectedApplicant.value.status_name = 'Megtekintett';
    }else{
        updateStatus(props.job_id,selectedApplicant.value.user_id,newVal);
    }
});
const closePopup = () => {
    isPopup.value = false;
};

const computeAge = computed(() => {
    if(!selectedApplicant) return null;
    if(!selectedApplicant.value.birth_date) return null;

    const today = new Date();
    const birthDate = new Date(selectedApplicant.value.birth_date);

    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    const dayDiff = today.getDate() - birthDate.getDate();

    if(monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)){
        age--;
    }

    return age;
});

const isCVDownloaded = () => {
    updateStatus(props.job_id,selectedApplicant.value.user_id,3);
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Jelentkezők"></Head>
        <v-container fluid class="px-0 mx-0  gradient-bg">
            <v-container class="!max-w-7xl mx-auto">
                <v-row>
                    <v-col>
                        <h2
                            class="text-xl font-semibold leading-tight text-gray-800"
                        >
                            Munka / Jelentkező(k)
                        </h2>
                        <h3 class="text-lg font-semibold leading-tight text-gray-800 mt-1">Ezen az oldalon tudja megtekinteni és szerkeszteni profilját.</h3>
                        <div class="flex gap-3 md:gap-5 text-md mt-3 flex-col md:flex-row">
                            <Link :href="route('cprofile.edit')" :class="['hover:text-[#fff] transition']">Alap adataim</Link>
                            <Link :href="route('cprofile.edit.jobs')" class="hover:text-[#fff] transition">Munkahirdetés</Link>
                            <Link :href="route('cprofile.myjobs')" :class="['hover:text-[#fff] transition',{'text-gray-50':$page.url.includes('cmunka')}]">Munkáim / Jelentkezők</Link>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </v-container>
        <div class="max-w-7xl mx-auto">
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <section v-if="props.applications.length > 0">
                            <div class="mb-3">
                                Jelentkező(k)
                            </div>
                            <v-row>
                                <v-col cols="12" md="6" v-for="apps in applications" :key="apps.id">
                                        <v-card :class="['mb-3 pa-3 listedjobs-card',{'hover-effect':clickedDiv === apps.id}]" @click="clickedDiv = apps.id">
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
                                            <div :class="['listedjobs-card-actions',{'hover-effect-actions':clickedDiv === apps.id}]">
                                                <v-btn @click="showPopUp(apps.user_id)" class="!bg-[#A67C52] hover:!bg-[#3A2618] !text-gray-50 hover:text-gray-50">Megtekintem</v-btn>
                                            </div>
                                        </v-card>
                                </v-col>
                            </v-row>
                            <v-overlay v-model="isPopup" class="align-center justify-center" width="100%">
                                <v-card class="education-card pa-4 !border-2 !border-solid !border-stone-950 w-[90%] xl:w-[50%] lg:w-[90%] mx-auto">
                                    <div class="absolute right-3 top-3">
                                        <v-icon icon="mdi mdi-close" @click="isPopup = false;"></v-icon>
                                    </div>
                                    <v-col cols="12">
                                        Név:
                                        <div>
                                            {{ selectedApplicant.name }}
                                        </div>
                                    </v-col>
                                    <v-col cols="12">
                                        Státusz:
                                        <div>
                                            {{ selectedApplicant.status_name }}
                                        </div>
                                    </v-col>
                                    <v-col cols="12">
                                        Születési dátum:
                                        <div>
                                            {{ selectedApplicant.birth_date }} / ( {{ computeAge }} )
                                        </div>
                                    </v-col>
                                    <v-col cols="12">
                                        E-mail cím:
                                        <div>
                                            <a :href="'mailto:'+selectedApplicant.email">{{ selectedApplicant.email }}</a>
                                        </div>
                                    </v-col>
                                    <v-col cols="12">
                                        Telefonszám:
                                        <div>
                                            <a :href="'tel:'+selectedApplicant.phone">{{ selectedApplicant.phone }}</a>
                                        </div>
                                    </v-col>
                                    <v-col cols="12">
                                        Bemutatkozás:
                                        <div>{{ selectedApplicant.intro }}</div>
                                    </v-col>
                                    <v-col cols="12" v-if="selectedApplicant.cv_file">
                                        <v-btn :href="'/storage/uploads/cv/'+selectedApplicant.cv_file" @click="isCVDownloaded" :download="selectedApplicant.name+'.pdf'" class="!bg-[#A67C52] hover:!bg-[#3A2618] !text-gray-50 hover:text-gray-50" v-if="selectedApplicant.cv_file">Önéletrajz letöltése</v-btn>
                                    </v-col>
                                    <v-col cols="12" class="flex w-full mt-3 justify-end gap-3" v-if="selectedApplicant.status_id >= 4">
                                        <v-btn class="hover:bg-green-500 hover:text-gray-50" @click="updateStatus(props.job_id,selectedApplicant.user_id,4)">Felvételt nyert</v-btn>
                                        <v-btn class="hover:bg-red-500 hover:text-gray-50" @click="updateStatus(props.job_id,selectedApplicant.user_id,5); isPopup = false;">Nem nyert felvételt</v-btn>
                                    </v-col>
                                </v-card>
                            </v-overlay>
                        </section>
                        <section v-else>
                            Erre a munkára jelenleg nincs egyetlen jelentkező sem!
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
    .listedjobs-card:hover::before,
    .hover-effect::before{
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
    .listedjobs-card:hover .listedjobs-card-actions,
    .hover-effect-actions{
        top: 50%;
        opacity: 1;
        transition: .3s all ease;
    }

    @media (max-width:576px){
        .listedjobs-card-actions{
            flex-wrap: wrap;
            gap: 1em;
            justify-content: center;
            top: -30%;
        }
        .listedjobs-card:hover .listedjobs-card-actions,
        .hover-effect-actions{
            top: 50%;
            opacity: 1;
            transition: .3s all ease;
        }
    }
</style>