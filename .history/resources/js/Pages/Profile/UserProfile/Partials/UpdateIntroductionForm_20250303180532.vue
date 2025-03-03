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

        <form
            @submit.prevent="form.post(route('profile.update.introduction'), {onFinish: () => {
               form.reset(); 
            }})"
            class="mt-6 space-y-6"
        >

        
            <div>
                <v-textarea label="Bemutatkozás" placeholder="Kezdjen el gépelni" v-model="form.introduction" :error="!!form.errors.introduction"
                :error-messages="form.errors.introduction" auto-grow counter
                ></v-textarea>
            </div>

            <div class="flex items-center gap-4">
                <v-btn class="hover:bg-sky-500 hover:text-gray-50" :disabled="form.processing">
                    {{ form.processing ? 'Mentés' : 'Mentve' }}
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
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
