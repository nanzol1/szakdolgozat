<script setup>
import { Head, router } from '@inertiajs/vue3';
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
        <div class="max-w-7xl mx-auto">
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <section class="bg-white p-4 shadow sm:rounded-lg sm:p-8 sm:py-2 dark:bg-gray-800 flex justify-between flex-col">
                            Jelentkező(k)
                            <div v-for="apps in applications">
                                {{ apps }}
                                <button @click="showPopUp(apps.user_id)">Megtekintem</button>
                            </div>
                            <Transition>
                                <div v-if="isPopup" class="popup">
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
                            </Transition>
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