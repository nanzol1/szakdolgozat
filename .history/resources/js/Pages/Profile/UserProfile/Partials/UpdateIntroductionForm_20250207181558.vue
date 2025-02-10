<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm  } from '@inertiajs/vue3';

const form = useForm({
    introduction:'',
});

</script>

<template>
    <section class="w-full">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Képzettségek
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="form.post(route('profile.update.education'), {onFinish: () => {
               form.reset(); 
            }})"
            class="mt-6 space-y-6"
            enctype="multipart/form-data"
        >

        
            <div>
                <InputLabel for="education_name" value="education_name" />

                <TextInput
                    id="education_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.education_name"
                    
                    autocomplete="education_name"
                />

                <InputError class="mt-2" :message="form.errors.education_name" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

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
