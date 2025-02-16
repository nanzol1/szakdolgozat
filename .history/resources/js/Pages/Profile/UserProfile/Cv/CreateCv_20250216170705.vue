<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';
import Step1 from './Step1.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const steps = [
    {component: Step1, key: 'step1'},
    /*{component: Step2, key: 'step2'},
    {component: Step3, key: 'step3'},*/
];

const currentStepIndex = ref(-1);

const formData = reactive({
    firstname:'',
    lastname:'',
    phone:'',
    email:''
});

const form = useForm({
    ...formData,
});

const handleMainFormSubmit = () => {
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
    Object.assign(formData,data);
};

// Form elküldése
const submitForm = () => {
  form.transform((data) => ({
    ...data,
    ...formData,
  })).post('/profile/cvmaker/store', {
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
                            További
                            <div>
                                
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="dark:text-white">Next</button>
                        </div>
                    </form>
                </div>

                <component v-else :is="steps[currentStepIndex].component" :formData="formData" @updateForm="updateForm"></component>

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
            </section>
        </div>
    </AuthenticatedLayout>
</template>