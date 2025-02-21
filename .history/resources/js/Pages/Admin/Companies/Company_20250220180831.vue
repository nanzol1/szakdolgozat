<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Dialog from 'primevue/dialog';
import { computed, ref } from 'vue';

const props = defineProps({
    company:{
        type:Array,
    },
    advertised_jobs:{
        type:Array,
    },
    applicants:{
        type:Array,
    }
});

const visible = ref(false);
const isPopup = ref(false);
const selectedJob = ref([]);

const resetPassword = () => {
    visible.value = false;
    router.post(route('admin.company.randompassword',{id:props.company[0].id}));
}
const showSelectedJob = (id) => {
    props.advertised_jobs.forEach(element => {
        if(element.id === id){
            selectedJob.value = element;
            isPopup.value = true;
        }
    });
};
const setStatus = () => {
    axios.patch(route('admin.company.setstatus',{id:props.company[0].id}),
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
                <template v-for="data in company" :key="data.id">
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
                    Meghirdetett munkák
                </div>
                <template v-for="jobs in advertised_jobs" :key="jobs.id">
                    <div v-if="jobs">
                        {{ jobs }}
                        <button @click="showSelectedJob(jobs.id)">Megtekintem</button>
                    </div>
                </template>

                <Dialog v-model:visible="isPopup" modal header="Munka adatai" :style="{ width: '25rem', background: 'white' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                    <div class="flex justify-start gap-2 mb-3">
                        <div>
                            {{ selectedJob.id }} | {{ selectedJob.active }} | {{ selectedJob.name }} | {{ selectedJob.address }}
                        </div>
                    </div>
                    <div class="flex justify-start gap-2 mb-3">
                        <div>
                            {{ selectedJob.description }} | {{ selectedJob.requirements }} | {{ selectedJob.payment }}
                        </div>
                    </div>
                    <div class="flex justify-start gap-2 mb-3">
                        <div>
                            Összesen jelentkezők száma: 
                        </div>
                    </div>
                </Dialog>

                <Dialog v-model:visible="visible" modal header="Alaphelyzetbe állítás" :style="{ width: '25rem', background: 'white' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                    <span class="text-surface-500 dark:text-surface-400 block mb-3">Biztosan alaphelyeztbe állítod?.</span>
                    <div class="flex justify-center gap-2 mb-3">
                        <div>
                            Alaphelyzetbe fog kerülni a <span style="text-decoration: underline">{{ props.company[0].name }}</span> jelszava!
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