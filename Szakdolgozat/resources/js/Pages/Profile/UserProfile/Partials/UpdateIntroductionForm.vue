<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const user = usePage().props.auth.user;

const form = useForm({
    introduction:'',
});

onMounted(() => {
    form.introduction = user.introduction;
});

</script>

<template>
    <section class="w-full">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Bemutatkozás
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Írjon magáról pár szót, hogy személyesebbé válhasson profilja és önéletrajza.
            </p>
        </header>

        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-form
                        @submit.prevent="form.post(route('profile.update.introduction'), {preserveScroll:true})"
                        class="mt-6 space-y-6"
                    >
                        <v-row>   
                            <v-col col="12">
                                <v-textarea label="Bemutatkozás" placeholder="Kezdjen el gépelni" v-model="form.introduction" :error="!!form.errors.introduction"
                                :error-messages="form.errors.introduction" auto-grow counter
                                ></v-textarea>
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
