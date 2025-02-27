<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, reactive, ref, watch } from 'vue';
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
  if (currentStepIndex.value < steps.length - 1) {
    currentStepIndex.value++;
  }
  console.log(currentStepIndex.value);
};

const prevStep = () => {
  if (currentStepIndex.value > -1) {
    currentStepIndex.value--;
  }
  console.log(currentStepIndex.value);

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

/*const templateContent = ref(null);
const loadTemplate = async () => {
    const data = Object.values(formData).map(e => e);

    const response = await fetch(`/cvmaker/previewCV?cv=${data}`);

    const html = await response.text();

    templateContent.value = html;
};

watch(formData, () => {
    loadTemplate();
});

onMounted(() => {
    loadTemplate();
});*/
const cvTemplate = ref(null);
const generatePDF = async() => {
    //const htmlContent = cvTemplate.value.$el.outerHTML;

    const htmlContent = `
  <html>
    <head>
      <style>
        body { font-family: Arial, sans-serif; }
        h1 { color: black; }
      </style>
    </head>
    <body>
      <h1>Hello, World!</h1>
      <p>This is a test PDF.</p>
    </body>
  </html>
`;

    const response = await axios.post(route('cv.pdf.generate'),{
        html: htmlContent, 
        fileName: `${formData.firstname}_CV.pdf`,
    },{
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]'),
        },
    });
    /*const blob = new Blob([response.data],{type:'application/pdf'});
    const url = window.URL.createObjectURL(blob);
    const alink = document.createElement('a');
    alink.href = url;
    alink.download = `${formData.firstname}_CV.pdf`;
    alink.click();
    window.URL.revokeObjectURL(url);*/
}

</script>

<template>
    <Head title="CV Készítő"></Head>
    <GuestPageLayout :canLogin="canLogin" :canRegister="canRegister">
        <v-container>
            <v-row>
                <v-col>
                    <div class="flex">
                        <section class="w-1/2">
                            <transition
                            @beforeEnter="beforeEnter"
                            @enter="enter"
                            @leave="leave"
                            :css="false"
                            >
                            <div v-if="currentStepIndex === -1" key="initial">
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
                        
                            <component v-else :is="steps[currentStepIndex].component" :key="currentStepIndex" :formData="formData" @updateForm="updateForm" @nextStep="nextStep" :prevStep="prevStep" @submitForm="submitForm" :generatePDF="generatePDF"></component>
                            </transition>

                            <div>
                                <button type="submit" class="dark:text-white" v-if="currentStepIndex === -1" @click="handleMainFormSubmit">Next</button>
                            </div>
                        </section>
                        <section class="w-1/2">
                            <!--<div v-html="templateContent"></div>-->
                            <component  ref="cvTemplate" :is="tempaltes[0].component" :formData="formData" :isAddition="isAddition"></component>
                        </section>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </GuestPageLayout>
</template>