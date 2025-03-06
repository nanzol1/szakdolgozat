<script setup>
import { Head, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, watch } from 'vue';

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

                                <v-card class="mb-3 pa-3">
                                    <v-card-title>
                                        {{ apps.user.name }}
                                    </v-card-title>
                                    <v-card-subtitle>
                                        <a :href="'mailto:'+apps.user.email">{{ apps.user.email }}</a> / <a :href="'tel:'+apps.user.phone_number"></a>
                                    </v-card-subtitle>
                                    <v-card-actions>
                                        <v-btn @click="showPopUp(apps.user_id)" class="hover:bg-sky-500 hover:text-gray-50">Megtekintem</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </div>
                            <v-overlay v-model="isPopup" class="align-center justify-center">
                                <div class="popup">
                                    <button @click="closePopup()">X</button>
                                    <div>
                                        {{ selectedApplicant.name }}
                                    </div>
                                    <div>
                                        {{ selectedApplicant.email }}
                                    </div>
                                    <div>
                                        {{ selectedApplicant.phone }}
                                    </div>
                                    <template v-for="(sts, index) in statuses" :key="sts.id">
                                        <div v-if="sts.name !== 'Jelentkezett'">
                                            <label :for="sts.id">{{ sts.name }}</label>
                                            <input type="radio" v-model="status" :id="sts.name" :name="sts.name" :value="sts.id" :disabled="index < status.valueOf()">
                                        </div>
                                    </template>
                                </div>
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
.popup{
	position: fixed;
	left: 50%;
	top: 50%;
	transform: translate(-50%,-50%);
	background: blue;
	padding: 1em;
}
</style>