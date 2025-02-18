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
    router.post(route('admin.user.randompassword',{id:props.user[0].id}));
}
</script>
<template>
    <Head title="Munkavállalók"></Head>
    <AdminLayout>
        <div class="dark:text-white">
            <template v-for="data in user">
                <div class="mb-3">
                    {{ data }}
                </div>
                <div>
                    <button label="Show" @click="resetPassword">Jelszó alaphelyzetbe</button>
                </div>
                <button label="Show" @click="visible = true">asd</button>

                <Dialog v-model:visible="visible" modal header="Biztosan alaphelyeztbe állítod?" :style="{ width: '25rem', background: 'white' }">
                    <span class="text-surface-500 dark:text-surface-400 block mb-8">Update your information.</span>
                    <div class="flex items-center gap-4 mb-4">
                        <label for="username" class="font-semibold w-24">Username</label>
                        <InputText id="username" class="flex-auto" autocomplete="off" />
                    </div>
                    <div class="flex items-center gap-4 mb-8">
                        <label for="email" class="font-semibold w-24">Email</label>
                        <InputText id="email" class="flex-auto" autocomplete="off" />
                    </div>
                    <div class="flex justify-end gap-2">
                        <Button type="button" label="Cancel" severity="secondary" @click="visible = false"></Button>
                        <Button type="button" label="Save" @click="visible = false"></Button>
                    </div>
                </Dialog>
            </template>
        </div>
    </AdminLayout>
</template>