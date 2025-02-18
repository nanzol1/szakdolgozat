<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Dialog from 'primevue/dialog';
import { ref } from 'vue';

const props = defineProps({
    user:{
        type:Array,
    },
});

const visible = ref(false);

const resetPassword = () => {
    visible.value = false;
    router.post(route('admin.user.randompassword',{id:props.user[0].id}));
}
</script>
<template>
    <Head title="Munkavállalók"></Head>
    <AdminLayout>
        <div>
            <div class="dark:text-white">
                <template v-for="data in user">
            <Transition>
                    <div class="mb-3">
                        {{ data }}
                    </div>
        </Transition>   
                    <div>
                        <button label="Show" @click="visible = true">Jelszó alaphelyzetbe</button>
                    </div>
                </template>


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
        </div>
    </AdminLayout>
</template>
<style>
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}</style>