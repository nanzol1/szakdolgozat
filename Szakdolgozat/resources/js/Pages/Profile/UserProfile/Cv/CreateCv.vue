<script setup>
import { Head } from '@inertiajs/vue3';
import {  computed, reactive, ref, watch } from 'vue';
import CV_step1 from './CV-step1.vue';
import CV_step2 from './CV-step2.vue';
import CV_step3 from './CV-step3.vue';
import CV_step4 from './CV-step4.vue';
import CV_Template1 from './Templates/CV_Template1.vue';
import axios from 'axios';
import GuestPageLayout from '@/Layouts/GuestPageLayout.vue';
import gsap from 'gsap';
import _ from 'lodash';

const props = defineProps({
    canLogin:{
        type:Boolean,
    },
    canRegister:{
        type:Boolean,
    },
    countyName:{
        type:Object,
    },
    postalCodes:{
        type:Object,
    },
    placeNames:{
        type:Object,
    }
});

const tempaltes = [
    {component: CV_Template1},
]
const steps = [
    {component: CV_step1, key: 'step1'},
    {component: CV_step2, key: 'step2'},
    {component: CV_step3, key: 'step3'},
    {component: CV_step4, key: 'step4'},
];

const form = ref(null);
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
    county:null,
    city:null,
    address:'',
    zip:null,
    exps: [],
    educations: [],
    skills: [],
    more_desc:'',
});

const fromZip = ref(false);

watch(() => formData.zip, async (data) => {
    const searchValue = typeof data === 'object' && data !== null && data !== 'null' ? data?.title : data;
    await debounceSearch('zip-'+searchValue);
});


const debounceSearch = _.debounce(async(element) => {
    const newElement = element.split('-');
    if(newElement[1].length > 3 && newElement[1] !== 'null'){
        try{
            const response = await axios.get(route('cv.getcodes',{datas:element}));
            if(response.data.list && response.data.list.length > 0){
                Object.values(response.data.list)?.map(data => {
                    formData.county = data.county;
                    formData.city = data.place_name;
                    formData.zip = data.postal_code;
                });
            }
        }catch (error){
            console.error(error);
            formData.county = null;
            formData.city = null;
        } finally{
            fromZip.value = false;
        }
    }else{
        formData.county = null;
        formData.city = null;
        formData.zip = null;
    }
},1000);


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
    alink.download = `${formData.firstname}_${formData.lastname}_cv.pdf`;
    alink.click();
    window.URL.revokeObjectURL(url);
};

const rules = {
    firstName: [
        v => !!v || 'Kötelező mező',
        v => (v && /^[a-zA-ZáéíóöőúüűÁÉÍÓÖŐÚÜŰ]+( [a-zA-ZáéíóöőúüűÁÉÍÓÖŐÚÜŰ]+)?$/.test(v)) ||'Érvényes vezetéknevet adjon meg!',
    ],
    lastName:[
        v => !!v || 'Kötelező mező',
        v => (v && /^[a-zA-ZáéíóöőúüűÁÉÍÓÖŐÚÜŰ]+( [a-zA-ZáéíóöőúüűÁÉÍÓÖŐÚÜŰ]+)?$/.test(v)) ||'Érvényes keresztnevet adjon meg!',
    ],
    phoneRule:[
        v => !!v || 'Kötelező mező',
        v => (v && /^\+?\d{1,11}$/.test(v)) ||'Érvényes telefonszámot adjon meg!',
    ],
    emailRules:[
        v => !!v || 'Kötelező mező',
        v => (v && /^.{1,}@.+\..+$/.test(v)) ||'Érvényes email címet adjon meg!',
    ],
    countyRules:[
        v => (/^$|^[a-zA-ZáéíóöőúüűÁÉÍÓÖŐÚÜŰ]+([ -][a-zA-ZáéíóöőúüűÁÉÍÓÖŐÚÜŰ]+)*$/.test(v)) ||'Csak betűket tartalmazhat',
    ],
    cityRules:[
        v => (/^$|^[a-zA-ZáéíóöőúüűÁÉÍÓÖŐÚÜŰ]+([ -][a-zA-ZáéíóöőúüűÁÉÍÓÖŐÚÜŰ]+)*$/.test(v)) ||'Csak betűket tartalmazhat',
    ],
    zipRules:[
        v => (/^$|^\d+$/.test(v)) ||'Csak számot tartalmazhat',
    ],
    addressRules:[
        v => (/^$|^(?!.*\s$).+$/.test(v)) ||'Csak betűket tartalmazhat',
    ],
    fileRules: [
        v => (!v || (v instanceof File && /image\/(jpeg|png|jpg)/.test(v.type))) || 'Csak képfájlok (.jpg, .jpeg, .png) engedélyezettek',
    ],
};

const validate = async () => {
    const { valid } = await form.value.validate();

    if(valid){
        handleMainFormSubmit();
    }
};

const computeCountyNames = computed(() => {
    return Object.values(props.countyName?.map(e => ({
        id:e.id,
        title:e.county,
    })));
});
const computePostalCodes = computed(() => {
    return Object.values(props.postalCodes?.map(e => ({
        id:e.id,
        title:e.postal_code,
    })));
});
const computePlaceNames = computed(() => {
    return Object.values(props.placeNames?.map(e => ({
        id:e.id,
        title:e.place_name,
    })));
});

</script>
<template>
    <Head title="CV Készítő"></Head>
    <GuestPageLayout>
        <div class="flex flex-col w-full">
            <v-container fluid class="pa-0  shadow-md relative hero_section">
                <v-img position="center" src='storage/images/resume.jpg' class="!z-10" aspect-ratio="16/9" absolute cover width="100%" height="100%"></v-img>
                <v-container>
                    <v-row>
                        <v-col>
                            <div class="relative !z-20 flex flex-col items-center py-8 h-auto text-stone-950 w-full">
                                <div class="flex flex-col max-w-7xl items-center">
                                    <div class="mx-auto text-h4">
                                        Önéletrajz készítés
                                    </div>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </v-container>
            <v-container class="py-lg-12 py-md-6 py-sm-2 py-0 !h-full">
                <v-row>
                    <v-col>
                        <div class="flex gap-10 h-full">
                            <v-row>
                                <v-col cols="12" md="6">
                                    <section class="w-full w-md-1/2">
                                        <transition
                                        @beforeEnter="beforeEnter"
                                        @enter="enter"
                                        @leave="leave"
                                        :css="false"
                                        >
                                        <div v-if="currentStepIndex === -1" key="initial">
                                            <v-form @submit.prevent="handleMainFormSubmit" enctype="multipart/form-data" ref="form">
                                              <v-container>
                                                <v-row>
                                                    <v-col v-show="false"
                                                    cols="12"
                                                    >
                                                        <v-file-input
                                                          :rules="rules.fileRules"
                                                          accept="image/png, image/jpeg, image/bmp"
                                                          label="Saját kép"
                                                          placeholder="Válassz egy képet"
                                                          prepend-icon="mdi-camera"
                                                          @change="handleImage"
                                                          name="cv_picture"
                                                        ></v-file-input>
                                                    </v-col>
                                                  <v-col
                                                    cols="12"
                                                    md="6"
                                                  >
                                                    <v-text-field
                                                      v-model="formData.firstname"
                                                      :rules="rules.firstName"
                                                      label="Vezetéknév *"
                                                      validate-on="blur"
                                                      placeholder="Példa"
                                                      required
                                                    ></v-text-field>
                                                  </v-col>
                                              
                                                  <v-col
                                                    cols="12"
                                                    md="6"
                                                  >
                                                    <v-text-field
                                                      v-model="formData.lastname"
                                                      :rules="rules.lastName"
                                                      label="Keresztnév *"
                                                      validate-on="blur"
                                                      placeholder="Példa"
                                                      required
                                                    ></v-text-field>
                                                  </v-col>
                                                  <v-col
                                                    cols="12"
                                                    md="6"
                                                  >
                                                    <v-text-field
                                                      v-model="formData.email"
                                                      :rules="rules.emailRules"
                                                      label="E-mail cím *"
                                                      validate-on="blur"
                                                      placeholder="example@example.com"
                                                      required
                                                    ></v-text-field>
                                                  </v-col>
                                                  <v-col
                                                    cols="12"
                                                    md="6"
                                                  >
                                                    <v-text-field
                                                      v-model="formData.phone"
                                                      :rules="rules.phoneRule"
                                                      label="Telefonszám *"
                                                      validate-on="blur"
                                                      placeholder="[+36|06] 30 123 4567"
                                                      required
                                                    ></v-text-field>
                                                  </v-col>
                                                  <v-col cols="12">
                                                    <v-btn @click="setAdditionDatas" class="!bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105 !text-gray-50">További információk</v-btn>
                                                  </v-col>
                                                </v-row>
                                                <v-expand-transition>
                                                    <v-row v-if="isAddition">
                                                        <v-col
                                                        cols="12"
                                                        md="6"
                                                      >
                                                      <v-combobox
                                                            label="Irányítószám"
                                                            placeholder="4032"
                                                            :items="computePostalCodes"
                                                            v-model="formData.zip"
                                                            auto-select-first="exact"
                                                        ></v-combobox>
                                                      </v-col>
                                                      <v-col
                                                        cols="12"
                                                        md="6"
                                                      >
                                                        <v-combobox
                                                            label="Megye"
                                                            placeholder="Hajdú-Bihar Vármegye"
                                                            :items="computeCountyNames"
                                                            v-model="formData.county"
                                                            auto-select-first="exact"
                                                        ></v-combobox>
                                                      </v-col>
                                                      <v-col
                                                        cols="12"
                                                        md="6"
                                                      >
                                                        <v-combobox
                                                            label="Város"
                                                            placeholder="Debrecen"
                                                            :items="computePlaceNames"
                                                            v-model="formData.city"
                                                            auto-select-first="exact"
                                                        ></v-combobox>
                                                      </v-col>
                                                      <v-col
                                                        cols="12"
                                                        md="6"
                                                      >
                                                        <v-text-field
                                                          v-model="formData.address"
                                                          label="Cím"
                                                          :rules="rules.addressRules"
                                                          placeholder="Példa utca 32."
                                                          validate-on="blur"
                                                        ></v-text-field>
                                                      </v-col>
                                                    </v-row>
                                                </v-expand-transition>
                                              </v-container>
                                            </v-form>
                                        </div>
                                        <component v-else :is="steps[currentStepIndex].component" :key="currentStepIndex" :formData="formData" @updateForm="updateForm" @nextStep="nextStep" :prevStep="prevStep" @submitForm="submitForm" :generatePDF="generatePDF"></component>
                                        </transition>
                                        <v-col cols="12">
                                            <v-btn class="!bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105 !text-gray-50" v-if="currentStepIndex === -1" @click="validate">Tovább</v-btn>    
                                        </v-col>
                                    </section>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <section class="w-full w-md-1/2 h-full p-[2.5em] bg-stone-200">
                                        <component ref="cvTemplate" :is="tempaltes[0].component" :imagePreview="imagePreview" :formData="formData" :isAddition="isAddition"></component>
                                    </section>
                                </v-col>
                            </v-row>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </GuestPageLayout>
</template>