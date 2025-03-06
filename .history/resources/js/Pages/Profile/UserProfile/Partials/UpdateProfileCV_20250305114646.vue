<script setup>
import { useForm } from '@inertiajs/vue3';

const submit = () =>{
    form.post(route('profile.save.cvfile'),{
        onSuccess: () => location.reload(),
    });
};

const form = useForm({
    cv_file: null,
});

</script>

<template>
    <section class="w-full">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Önéletrajz
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Ezen az oldalon tudja feltölteni / törölni önéletrajzát
            </p>
        </header>
        <v-form
            @submit.prevent="submit"
            class="mt-6 space-y-6"
            enctype="multipart/form-data"
        >
            <v-row>
                <v-col cols="12">
                    <v-file-input label="Önéletrajz feltöltés" name="cv_file" @change="form.cv_file = $event.target.files[0]"
                    :error="!!form.errors.cv_file" :error-messages="form.errors.cv_file"></v-file-input>
                </v-col>

                <v-col cols="12" class="flex items-center gap-4">
                    <v-btn :disabled="form.processing" class="hover:bg-sky-500 hover:text-gray-50">
                        Feltöltés
                    </v-btn>

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
                            Feltöltve.
                        </p>
                    </Transition>
                </v-col>
            </v-row>

        </v-form>
    </section>
    <section class="w-full">
        <p class="text-white mb-3">Profilkép</p>
    </section>
</template>
