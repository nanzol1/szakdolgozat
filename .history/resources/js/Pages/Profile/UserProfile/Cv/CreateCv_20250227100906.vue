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
import html2pdf from "html2pdf.js";
import Vue3Html2pdf from 'vue3-html2pdf';
import html2canvas from 'html2canvas';
import jsPDF from 'jspdf';

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

/*const generatePDF = async () => {
  const element = cvTemplate.value.$el;

  html2canvas(element, {
    scale: 2,
    useCORS: true,
  }).then((canvas) => {
    const imgData = canvas.toDataURL("image/png");
    const pdf = new jsPDF("p", "mm", "a4");

    const imgWidth = 210; // A4 lap szélessége mm-ben
    const imgHeight = (canvas.height * imgWidth) / canvas.width;

    const elem = document.getElementById('my-div');

    // Ellenőrizze, hogy a kép magassága nem haladja-e meg az A4 magasságát
    const pageHeight = 297; // A4 lap magassága mm-ben
    elem.style.height = imgHeight;

    if (imgHeight > pageHeight) {
      // Ha a kép magasabb, mint egy A4 lap, akkor több oldalra osztjuk
      let heightLeft = imgHeight;
      let position = 0;

      while (heightLeft > 0) {
        pdf.addImage(imgData, "PNG", 0, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;
        position -= pageHeight;

        if (heightLeft > 0) {
          pdf.addPage();
        }
      }
    } else {
      // Ha a kép elfér egy oldalon, akkor egyszerűen hozzáadjuk
      pdf.addImage(imgData, "PNG", 0, 0, imgWidth, imgHeight);
    }

    pdf.save(`${formData.firstname}_CV.pdf`);
  }).catch((error) => {
    console.error("Hiba történt a kép generálása közben:", error);
  });
};*/
const cvTemplate = ref(null);
/*const generatePDF = async() => {
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
}*/
const generatePDF = async () => {
  const element = cvTemplate.value.$el;

  html2canvas(element, {
    scale: 2,
    useCORS: true,
    logging: true,
    scrollY: -window.scrollY,
    allowTaint:true,
  }).then((canvas) => {
    const imgData = canvas.toDataURL("image/png");
    const pdf = new jsPDF("p", "mm", "a4");

    const tesztwidth = pdf.internal.pageSize.getWidth();
    const teszthe = pdf.internal.pageSize.getHeight();

    const hratio = teszthe / canvas.height;

    const imgWidth = 210; // A4 szélesség
let imgHeight = (canvas.height * imgWidth) / canvas.width; // Képarány fenntartása

if (imgHeight > 297) {
  // Ha a kép magasabb, mint az A4, akkor csökkentsd a méreteket
  const scaleFactor = 297 / imgHeight;
  pdf.addImage(imgData, "PNG", 0, 0, imgWidth * scaleFactor, 297);
} else {
  pdf.addImage(imgData, "PNG", 0, 0, imgWidth, hratio);
}

    pdf.save(`${formData.firstname}_CV.pdf`);
  }).catch((error) => {
    console.error("Hiba történt a kép generálása közben:", error);
  });
};

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
                        <section class="w-1/2 " style="box-sizing: border-box; min-height: 297mm;">
                            <!--<div v-html="templateContent"></div>-->
                            <component ref="cvTemplate" class="flex flex-col flex-1-1" :is="tempaltes[0].component" :formData="formData" :isAddition="isAddition" style="width: 100%; height: 100%; min-height: 297mm;"></component>
                        </section>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </GuestPageLayout>
</template>
<style>
.vue-html2pdf .layout-container {
    left: 0 !important; /* Visszahelyezi az oldalra */
    position: relative !important; /* Biztosítja, hogy ne legyen elrejtve */
    opacity: 1 !important; /* Ha esetleg el lenne halványítva */
    display: block !important; /* Ha rejtve lenne */
    width: fit-content !important;
    height: 100% !important;
    z-index: 0 !important;
    background: white !important;
}
</style>