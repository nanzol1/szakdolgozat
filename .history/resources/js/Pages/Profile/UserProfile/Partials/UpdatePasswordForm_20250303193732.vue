<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
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
                Update Password
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Ensure your account is using a long, random password to stay
                secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>
                <v-text-field label="Jelenlegi jelszó" v-model="form.current_password" :error="!!form.errors.current_password"
                :error-messages="form.errors.current_password" ref="currentPasswordInput" id="current_password" type="password"></v-text-field>
            </div>

            <div>
                <v-text-field label="Jelenlegi jelszó" v-model="form.password" :error="!!form.errors.password"
                :error-messages="form.errors.password" ref="passwordInput" id="password" type="password"></v-text-field>
            </div>

            <div>
                <v-text-field label="Jelszó ismét" v-model="form.password_confirmation" :error="!!form.errors.password_confirmation"
                :error-messages="form.errors.password_confirmation" ref="passwordInput" id="password_confirmation" type="password"></v-text-field>
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />
            </div>

            <div class="flex items-center gap-4">
                <v-btn type="submit" class="hover:bg-sky-500 hover:text-gray-50" :disabled="form.processing">Save</v-btn>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
