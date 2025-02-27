<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import {  reactive, ref } from 'vue';
import CV_step1 from './CV-step1.vue';
import CV_step2 from './CV-step2.vue';
import CV_step3 from './CV-step3.vue';
import CV_step4 from './CV-step4.vue';
import CV_Template1 from './Templates/CV_Template1.vue';
import CV_Template2 from './Templates/CV_Template2.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import axios from 'axios';
import GuestPageLayout from '@/Layouts/GuestPageLayout.vue';
import gsap from 'gsap';

defineProps({
    canLogin:{
        type:Boolean,
    },
    canRegister:{
        type:Boolean,
    }
});

const tempaltes = [
    {component: CV_Template1},
    {component: CV_Template2},
]
const steps = [
    {component: CV_step1, key: 'step1'},
    {component: CV_step2, key: 'step2'},
    {component: CV_step3, key: 'step3'},
    {component: CV_step4, key: 'step4'},
];

const currentStepIndex = ref(-1);
const isAddition = ref(false);
const formSent = ref(false);
const imagePreview = ref(null);
const validInput = ref(false);

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
};
const nextStep = () => {
    if(validInput.value === true){
        if (currentStepIndex.value < steps.length - 1) {
          currentStepIndex.value++;
        }
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
    /*try{
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
        const response = await axios.post(route('cv.store'),formDatas,{
            headers:{
                'Content-Type':'multipart/form-data',
            },
        });

        const cvId = response.data.cv_id;
        window.location.href = route('cv.store');
        formSent.value = true;
    }catch(error){
        console.error('Hiba történt: '+error);
    }*/
   generatePDF();
};

const setAdditionDatas = (e) => {
    e.preventDefault();
    if(isAddition.value === false){
        isAddition.value = true;
    }else{
        isAddition.value = false;
    }
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

//Animáció
let beforeEnter = (el) => {
    gsap.set(el,{ opacity:0, x:-50});
};

let enter = (el,done) => {
    gsap.to(el,{
        opacity:1,
        x:0,
        duration:0.5,
        delay:0.5,
        onComplete:done,
    });
};

let leave = (el,done) => {
    gsap.to(el,{
        opacity:0,
        x:50,
        duration:0.5,
        onComplete:done,
    });
};
const cvTemplate = ref(null);
const generatePDF = async() => {
    const htmlContent = cvTemplate.value.$el.outerHTML;

    const response = await axios.post(route('cv.pdf.generate'),{
        html: htmlContent, 
        fileName: `${formData.firstname}_CV.pdf`,
    },{
        responseType: 'blob',
    });

    const blob = new Blob([response.data],{type:'application/pdf'});
    const url = window.URL.createObjectURL(blob);
    const alink = document.createElement('a');
    alink.href = url;
    alink.download = `${formData.firstname}_CV.pdf`;
    alink.click();
    window.URL.revokeObjectURL(url);
};

const rules = {
    firstName: [
        (value) => {
            if (value.length > 0) return true;
            return 'Legalább 3 karakter kell legyen!';
        },
    ],
    lastName:[
        (value) => {
            if (value.length > 0) return true;
            return 'Legalább 3 karakter kell legyen!';
        },
    ],
    phoneRule:[
        v => !!v || 'Kötelező mező',
        v => (v && /^\+?\d{1,11}$/.test(v)) ||'Csak számot tartalmazhat!',
    ],
    emailRules:[
        (value) => {
            if (value.length > 0) return true;
            return 'Kötelező mező!';
        },
    ],
};

</script>
<template>
    <Head title="CV Készítő"></Head>
    <GuestPageLayout :canLogin="canLogin" :canRegister="canRegister">
        <v-container>
            <v-row>
                <v-col>
                    <div class="flex gap-10 h-full">
                        <section class="w-1/2">
                            <transition
                            @beforeEnter="beforeEnter"
                            @enter="enter"
                            @leave="leave"
                            :css="false"
                            >
                            <div v-if="currentStepIndex === -1" key="initial">
                                <v-form @submit.prevent="handleMainFormSubmit" enctype="multipart/form-data">
                                  <v-container>
                                    <v-row>
                                        <v-col
                                        cols="12"
                                        md="4"
                                        >
                                            <v-file-input
                                              :rules="fileRules"
                                              accept="image/png, image/jpeg, image/bmp"
                                              label="Kép"
                                              placeholder="Válassz egy képet"
                                              prepend-icon="mdi-camera"
                                              @change="handleImage"
                                              name="cv_picture"
                                            ></v-file-input>
                                        </v-col>
                                      <v-col
                                        cols="12"
                                        md="4"
                                      >
                                        <v-text-field
                                          v-model="formData.firstname"
                                          :counter="10"
                                          :rules="rules.firstName"
                                          label="Vezetéknév"
                                          required
                                        ></v-text-field>
                                      </v-col>
                                  
                                      <v-col
                                        cols="12"
                                        md="4"
                                      >
                                        <v-text-field
                                          v-model="formData.lastname"
                                          :counter="10"
                                          :rules="rules.lastName"
                                          label="Keresztnév"
                                          required
                                        ></v-text-field>
                                      </v-col>
                                  
                                      <v-col
                                        cols="12"
                                        md="4"
                                      >
                                        <v-text-field
                                          v-model="formData.phone"
                                          :rules="rules.phoneRule"
                                          label="Telefonszám"
                                          required
                                        ></v-text-field>
                                      </v-col>
                                      <v-col
                                        cols="12"
                                        md="4"
                                      >
                                        <v-text-field
                                          v-model="formData.email"
                                          :rules="emailRules"
                                          label="E-mail cím"
                                          required
                                        ></v-text-field>
                                      </v-col>
                                      <v-col cols="12" md="4">
                                        <v-btn @click="setAdditionDatas" variant="tonal">További</v-btn>
                                      </v-col>
                                    </v-row>
                                    <v-row v-if="isAddition">
                                        <v-col
                                        cols="12"
                                        md="4"
                                      >
                                        <v-text-field
                                          v-model="formData.county"
                                          label="Megye"
                                        ></v-text-field>
                                      </v-col>
                                      <v-col
                                        cols="12"
                                        md="4"
                                      >
                                        <v-text-field
                                          v-model="formData.city"
                                          label="Város"
                                        ></v-text-field>
                                      </v-col>
                                      <v-col
                                        cols="12"
                                        md="4"
                                      >
                                        <v-text-field
                                          v-model="formData.zip"
                                          label="Irányítószám"
                                        ></v-text-field>
                                      </v-col>
                                      <v-col
                                        cols="12"
                                        md="4"
                                      >
                                        <v-text-field
                                          v-model="formData.address"
                                          label="Cím"
                                        ></v-text-field>
                                      </v-col>
                                    </v-row>
                                  </v-container>
                                </v-form>
                            </div>
                            <component v-else :is="steps[currentStepIndex].component" :key="currentStepIndex" :formData="formData" @updateForm="updateForm" @nextStep="nextStep" :prevStep="prevStep" @submitForm="submitForm" :generatePDF="generatePDF"></component>
                            </transition>
                            <div>
                                <button type="submit" class="dark:text-white" v-if="currentStepIndex === -1" @click="handleMainFormSubmit">Next</button>
                            </div>
                        </section>
                        <section class="w-1/2 h-[100%] p-[2.5em] bg-stone-200">
                            <component ref="cvTemplate" :is="tempaltes[0].component" :imagePreview="imagePreview" :formData="formData" :isAddition="isAddition"></component>
                        </section>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </GuestPageLayout>
</template>