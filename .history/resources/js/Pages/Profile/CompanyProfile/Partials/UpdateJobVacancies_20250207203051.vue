<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Checkbox from '@/Components/Checkbox.vue';


const user = usePage().props.auth.user;

const props = defineProps({
    categories:{
        type:Array,
    } 
});

const checkboxes = ref([]);

const form = useForm({
    name:'',
    position:[],
    description:'',
    requirements:'',
    payment:'',
    created_by: user.id,
});

const checkMainCategory = (category) => {
    console.log(checkboxes.value.indexOf('m'+category.maincateg_id));

    if(!checkboxes.value.includes('m'+category.maincateg_id)){
        checkboxes.value.push('m'+category.maincateg_id);
    }else{
        const index = checkboxes.value.indexOf('m'+category.maincateg_id);
        if(index >= 0){
            checkboxes.value.splice(index,1);
        }
    }
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Munka meghírdetése
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="form.post(route('cprofile.create.job'),{
                onFinish: () => {
                    form.reset();
                }})"
            class="mt-6 space-y-6"
        >
            <div>
                <InputLabel for="name" value="name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div>
                <InputLabel for="position" value="position" />
                ss {{ checkboxes }}

                <div v-for="cats in categories" :key="cats.id">
                    <label :for="cats.id">{{ cats.name }}</label>
                    <Checkbox :value="'m'+cats.id" v-model:checked="checkboxes" :id="cats.id" disabled>{{ cats.name }}</Checkbox>
                    <div v-for="subcats in cats.subcategories" :key="subcats.id">
                        <label :for="subcats.name">{{ subcats.name }}</label>
                        <Checkbox :value="'s'+subcats.id" v-model:checked="checkboxes" :id="subcats.name" @change="checkMainCategory(subcats)">{{ subcats.name }}</Checkbox>
                    </div>
                </div>

                <InputError class="mt-2" :message="form.errors.positions" />
            </div>

            <div>
                <InputLabel for="description" value="description" />

                <textarea name="description" id="description" v-model="form.description" placeholder="Munka leírása" class="w-full" required></textarea>

                <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <div>
                <InputLabel for="requirements" value="requirements" />

                <textarea name="requirements" id="requirements" v-model="form.requirements" placeholder="Elvárások" class="w-full" required></textarea>

                <InputError class="mt-2" :message="form.errors.requirements" />
            </div>

            <div>
                <InputLabel for="payment" value="payment" />

                <textarea name="payment" id="payment" v-model="form.payment" placeholder="Fizetés" class="w-full"></textarea>

                <InputError class="mt-2" :message="form.errors.payment" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Mentés</PrimaryButton>

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
                        Mentve.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
