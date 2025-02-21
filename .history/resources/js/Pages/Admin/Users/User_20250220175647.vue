<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import Dialog from 'primevue/dialog';
import { ref } from 'vue';

const props = defineProps({
    user:{
        type:Array,
    },
    appliactions:{
        type:Object,
    }
});

const visible = ref(false);
const isPopup = ref(false);
const selectedJob = ref([]);

const resetPassword = () => {
    visible.value = false;
    router.post(route('admin.user.randompassword',{id:props.user[0].id}));
};

const showSelectedJob = (id) => {
    props.appliactions.forEach(element => {
        if(element.id === id){
            selectedJob.value = element;
            isPopup.value = true;
        }
    });
};
const setStatus = () => {
    axios.patch(route('admin.user.setstatus',{id:props.user[0].id}),
    {}).then((result) => {
        console.log(result.data);
    }).catch((err) => {
        console.error(err);
    });
};
</script>
<template>
    <Head title="Munkavállaló"></Head>
    <AdminLayout>
            <div class="dark:text-white">
                <template v-for="data in user">
                    <div class="mb-3">
                        {{ data }}
                    </div>
                    <div>
                        <button label="Show" @click="visible = true">Jelszó alaphelyzetbe</button>
                    </div>
                    <div>
                        <button @click="setStatus">Fiók inaktiválása</button>
                    </div>
                </template>

                <div class="mt-5">
                    Munkákra jelentkezett
                </div>
                <template v-for="apps in appliactions">
                    <div class="mb-3">
                        {{ apps }}
                        <button @click="showSelectedJob(apps.id)">Megtekint</button>
                    </div>
                </template>

                <Dialog v-model:visible="isPopup" modal header="Munka adatai" :style="{ width: '25rem', background: 'white' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                    <div class="flex justify-start gap-2 mb-3">
                        <div>
                            {{ selectedJob.id }} | {{ selectedJob.job_id }} | {{ selectedJob.active ? 'Aktív' : 'Inaktív' }} | {{ selectedJob.status }}
                        </div>
                    </div>
                    <div class="flex justify-start gap-2 mb-3">
                        <div>
                            {{ selectedJob.job_name }} | <Link :href="route('admin.company.show',{id:selectedJob.job_createdby})">{{ selectedJob.job_createdby }}</Link>
                        </div>
                    </div>
                </Dialog>

                <Dialog v-model:visible="visible" modal header="Alaphelyzetbe állítás" :style="{ width: '25rem', background: 'white' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                    <span class="text-surface-500 dark:text-surface-400 block mb-3">Biztosan alaphelyeztbe állítod?.</span>
                    <div class="flex justify-center gap-2 mb-3">
                        <div>
                            Alaphelyzetbe fog kerülni a <span style="text-decoration: underline">{{ props.user[0].name }}</span> jelszava!
                        </div>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" label="Save" @click="resetPassword">Igen</button>
                        <button type="button" label="Cancel" severity="secondary" @click="visible = false">Mégsem</button>
                    </div>
                </Dialog>
            </div>
    </AdminLayout>
</template>
<style>

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>