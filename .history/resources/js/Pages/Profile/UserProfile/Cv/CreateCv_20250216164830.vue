<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { reactive } from 'vue';
import Step1 from './Step1.vue';

const steps = [
    {component: Step1, key: 'step1'},
    /*{component: Step2, key: 'step2'},
    {component: Step3, key: 'step3'},*/
];

const currentStepIndex = ref(-1);

const mainFormData = reactive({
    mainField:'',
});

const formData = reactive({
    main: {...mainFormData},
    step1:{},
    /*step2:{},
    step3:{},*/
});

const form = useForm({
    ...formData,
});

const handleMainFormSubmit = () => {
  formData.main = { ...mainFormData }; // Mentés a formData-ba
  nextStep(); // Ugrás a következő lépésre
};

// Következő lépésre ugrás
const nextStep = () => {
  if (currentStepIndex.value < steps.length - 1) {
    currentStepIndex.value++;
  }
};

// Előző lépésre ugrás
const prevStep = () => {
  if (currentStepIndex.value > -1) {
    currentStepIndex.value--;
  }
};

// Form adatok frissítése
const updateForm = (data) => {
  formData[steps[currentStepIndex.value].key] = data;
};

// Form elküldése
const submitForm = () => {
  form.transform((data) => ({
    ...data,
    ...formData,
  })).post('/submit-form', {
    onSuccess: () => {
      alert('Form submitted successfully!');
    },
  });
};
</script>

<template>
    <Head title="CV Készítő"></Head>
    <AuthenticatedLayout>
        <div class="flex">
            <section class="w-full">
                <div v-if="currentStepIndex === -1">
                    <form @submit.prevent="handleMainFormSubmit">
                        <input v-model="mainFormData.mainField">
                        <button type="submit">Next</button>
                    </form>
                </div>

                <component v-else :is="steps[currentStepIndex].component" :formData="formData" @updateForm="updateForm"></component>
            </section>
            <section class="w-full">
                B
            </section>
        </div>
    </AuthenticatedLayout>
</template>