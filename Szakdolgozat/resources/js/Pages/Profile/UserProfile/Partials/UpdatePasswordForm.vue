<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Jelszó módosítás
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                A biztonság érdekében törekedjen arra, hogy biztonságos jelszót használjon.
            </p>
        </header>
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-form @submit.prevent="updatePassword" class="mt-6 space-y-6">
                        <v-row>
                            <v-col cols="12">
                                <v-text-field label="Jelenlegi jelszó" v-model="form.current_password" :error="!!form.errors.current_password"
                                :error-messages="form.errors.current_password" ref="currentPasswordInput" id="current_password" type="password"></v-text-field>
                            </v-col>
                        
                            <v-col cols="12">
                                <v-text-field label="Új jelszó" v-model="form.password" :error="!!form.errors.password"
                                :error-messages="form.errors.password" ref="passwordInput" id="password" type="password"></v-text-field>
                            </v-col>
                        
                            <v-col cols="12">
                                <v-text-field label="Új jelszó ismét" v-model="form.password_confirmation" :error="!!form.errors.password_confirmation"
                                :error-messages="form.errors.password_confirmation" ref="passwordInput" id="password_confirmation" type="password"></v-text-field>
                            </v-col>
                        
                            <v-col cols="12" class="flex items-center gap-4">
                                <v-btn type="submit" class="!bg-[#A67C52] hover:!bg-[#3A2618] !text-gray-50 hover:text-gray-50" :disabled="form.processing">
                                    {{ form.recentlySuccessful ? 'Mentve' : 'Mentés' }}
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-col>
            </v-row>
        </v-container>
    </section>
</template>
