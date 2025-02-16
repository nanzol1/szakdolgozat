<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';
import CV_step1 from './CV-step1.vue';
import CV_step2 from './CV-step2.vue';
import CV_step3 from './CV-step3.vue';
import CV_step4 from './CV-step4.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const steps = [
    {component: CV_step1, key: 'step1'},
    {component: CV_step2, key: 'step2'},
    {component: CV_step3, key: 'step3'},
    {component: CV_step4, key: 'step4'},
];

const currentStepIndex = ref(-1);
const isAddition = ref(false);

const formData = reactive({
    firstname:'',
    lastname:'',
    phone:'',
    email:'',
    county:'',
    city:'',
    address:'',
    zip:'',
    exps: {},
    educations: {},
    skills: {},
    more_desc:'',
});

const form = useForm({
    ...formData,
});

const handleMainFormSubmit = () => {
  nextStep();
};

const nextStep = () => {
  if (currentStepIndex.value < steps.length - 1) {
    currentStepIndex.value++;
  }
};

const prevStep = () => {
  if (currentStepIndex.value > -1) {
    currentStepIndex.value--;
  }
};

const updateForm = (data) => {
    Object.assign(formData,data);
};

const submitForm = () => {
  form.transform((data) => ({
    ...data,
    ...formData,
  })).post('/profile/cvmaker/store', {
    onSuccess: (response) => {
     const cvId = response.props.cv_id;
     location.href = route('profile.cv.pdf',id:cv_id);
    },
  });
};

const setAdditionDatas = (e) => {
    e.preventDefault();
    isAddition.value = true;
};
</script>

<template>
    <Head title="CV Készítő"></Head>
    <AuthenticatedLayout>
        <div class="flex">
            <section class="w-full">
                <div v-if="currentStepIndex === -1">
                    <form @submit.prevent="handleMainFormSubmit">
                        <div>
                            <InputLabel for="firstname" value="Vezetéknév"></InputLabel>
                            <TextInput id="firstname" type="text" v-model="formData.firstname" autocomplete="firstname"></TextInput>

                            <InputLabel for="lastname" value="Keresztnév"></InputLabel>
                            <TextInput id="lastname" type="text" v-model="formData.lastname" autocomplete="lastname"></TextInput>

                            <InputLabel for="phone" value="Telefonszám"></InputLabel>
                            <TextInput id="phone" type="text" v-model="formData.phone" autocomplete="phone"></TextInput>

                            <InputLabel for="email" value="E-mail cím"></InputLabel>
                            <TextInput id="email" type="email" v-model="formData.email" autocomplete="email"></TextInput>
                        </div>
                        <div>
                            <button @click="setAdditionDatas" type="button">További</button>
                            <div v-if="isAddition">
                                <InputLabel for="county" value="Megye"></InputLabel>
                                <TextInput id="county" type="text" v-model="formData.county" autocomplete="county"></TextInput>

                                <InputLabel for="city" value="Város"></InputLabel>
                                <TextInput id="city" type="text" v-model="formData.city" autocomplete="city"></TextInput>

                                <InputLabel for="zip" value="Irányítószám"></InputLabel>
                                <TextInput id="zip" type="text" v-model="formData.zip" autocomplete="zip"></TextInput>

                                <InputLabel for="address" value="Cím"></InputLabel>
                                <TextInput id="address" type="text" v-model="formData.address" autocomplete="address"></TextInput>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="dark:text-white">Next</button>
                        </div>
                    </form>
                </div>

                <component v-else :is="steps[currentStepIndex].component" :formData="formData" @updateForm="updateForm" @nextStep="nextStep" @submitForm="submitForm"></component>

                <div>
                    <button
          @click="prevStep"
          v-if="currentStepIndex > -1"
        >
          Previous
        </button>
        <button
          @click="nextStep"
          v-if="currentStepIndex < steps.length - 1 && currentStepIndex !== -1"
        >
          Next
        </button>
        <button
          @click="submitForm"
          v-if="currentStepIndex === steps.length - 1"
        >
          Submit
        </button>
                </div>
            </section>
            <section class="w-full">
                <div>
                    Vezetéknév: {{ formData.firstname }}
                </div>
                <div>
                    Keresztnév: {{ formData.lastname }}
                </div>
                <div>
                    Telefonszám: {{ formData.phone }}
                </div>
                <div>
                    E-mail: {{ formData.email }}
                </div>
                <template v-if="isAddition">
                    <div>
                        Megye: {{ formData.county }}
                    </div>
                    <div>
                        Város: {{ formData.city }}
                    </div>
                    <div>
                        Irányítószám: {{ formData.zip }}
                    </div>
                    <div>
                        Cím: {{ formData.address }}
                    </div>
                </template>
                <template v-if="formData.exps">
                    <div v-for="exp in formData.exps">
                        {{ exp }}
                    </div>
                </template>
                <template v-if="formData.educations">
                    <div v-for="edc in formData.educations">
                        {{ edc }}
                    </div>
                </template>
                <template v-if="formData.skills">
                    <div v-for="skill in formData.skills">
                        {{ skill }}
                    </div>
                </template>
                <template v-if="formData.plain_text">
                    <div>
                        {{ formData.plain_text }}
                    </div>
                </template>
            </section>
        </div>
    </AuthenticatedLayout>
</template>