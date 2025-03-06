<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.post(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Felhasználó inaktiválása
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Inaktiválás után nem fogja tudni használni fiókját viszont minden adat és tevékenység megmarad.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">Fiók inaktiválása</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2
                    class="text-lg font-medium text-gray-900 dark:text-gray-100"
                >
                    Biztosan inaktiválni szeretné a fiókját?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Inaktiválás után nem fogja tudni használni fiókját viszont minden adat és tevékenység megmarad.
                </p>

                <div class="mt-6">
                    <v-text-field label="Jelszó" v-model="form.password" :error="!!form.errors.password"
                    :error-messages="form.errors.password" ref="passwordInput" id="password" type="password" @keyup.enter="deleteUser"></v-text-field>
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">
                        Mégsem
                    </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Fiók inaktiválása
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
