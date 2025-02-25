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
import InputError from '@/Components/InputError.vue';
import axios from 'axios';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import GuestPageLayout from '@/Layouts/GuestPageLayout.vue';

defineProps({
    canLogin:{
        type:Boolean,
    },
    canRegister:{
        type:Boolean,
    }
});
const steps = [
    {component: CV_step1, key: 'step1'},
    {component: CV_step2, key: 'step2'},
    {component: CV_step3, key: 'step3'},
    {component: CV_step4, key: 'step4'},
];

const stepper = ref(null);
const currentStepIndex = ref(-1);
const isAddition = ref(false);
const formSent = ref(false);
const imagePreview = ref(null);

const formData = reactive({
    cv_picture: null,
    firstname:'',
    lastname:'',
    phone:'',
    email:'',
    county:'',
    city:'',
    address:'',
    zip:'',
    exps: [],
    educations: [],
    skills: [],
    more_desc:'',
});

const form = useForm({
    ...formData,
});

const handleMainFormSubmit = () => {
  nextStep();
  next();
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

const submitForm = async () => {
    try{
        const formDatas = new FormData();
        formDatas.append('cv_picture',formData.cv_picture);
        formDatas.append('firstname',formData.firstname);
        formDatas.append('lastname',formData.lastname);
        formDatas.append('phone',formData.phone);
        formDatas.append('email',formData.email);
        formDatas.append('county',formData.county);
        formDatas.append('city',formData.city);
        formDatas.append('address',formData.address);
        formDatas.append('zip',formData.zip);
        formDatas.append('exps',JSON.stringify(formData.exps));
        formDatas.append('educations',JSON.stringify(formData.educations));
        formDatas.append('skills',JSON.stringify(formData.skills));
        formDatas.append('more_desc',formData.more_desc);
        const response = await axios.post('/profile/cvmaker/store',formDatas,{
            headers:{
                'Content-Type':'multipart/form-data',
            },
        });

        const cvId = response.data.cv_id;
        window.location.href = route('profile.cv.pdf',{id:cvId});
        formSent.value = true;
    }catch(error){
        console.error('Hiba történt: '+error);
    }
};

const setAdditionDatas = (e) => {
    e.preventDefault();
    isAddition.value = true;
};
const handleImage = (event) => {
    const file = event.target.files[0];
    if(file && file.type.startsWith('image/')){
        formData.cv_picture = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }else{
        imagePreview.value = null;
        formData.cv_picture = null;
    }
};
</script>

<template>
    <Head title="CV Készítő"></Head>
    <GuestPageLayout :canLogin="canLogin" :canRegister="canRegister">
        <div class="flex align-center">
            <section class="w-full">
                {{ currentStepIndex }}
                <v-stepper :items="['s','s']">
                  <template v-slot:item.1 v-if="currentStepIndex === -1">
                    <v-card title="Step One" flat>
                        <div>
                            <form @submit.prevent="handleMainFormSubmit" enctype="multipart/form-data">
                                <div>
                                    <input type="file" @change="handleImage" name="cv_picture"> 
                                </div>
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
                            </form>
                        </div>
                    </v-card>

                  </template>

                  <template v-slot:item.2 v-else>
                    <v-card title="Step Two" flat>
                        <component :is="steps[currentStepIndex].component" :formData="formData" @updateForm="updateForm" @nextStep="nextStep" @submitForm="submitForm"></component>
                    </v-card>
                  </template>
                    <v-stepper-actions
                        @click:prev="prev"
                        @click:next="handleMainFormSubmit"
                    ></v-stepper-actions>
                </v-stepper>


                <div>
                    <button
          @click="prevStep"
          v-if="currentStepIndex > -1"
        >
          Previous
        </button>
                </div>
            </section>
            <section class="w-full">
                ss {{ formData.cv_picture }}
                <div v-if="imagePreview">
                    Kép: <img :src="imagePreview" alt="Image preview">
                </div>
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
    </GuestPageLayout>
</template>