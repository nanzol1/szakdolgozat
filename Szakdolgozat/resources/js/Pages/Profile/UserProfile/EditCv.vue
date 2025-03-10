<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import UpdateProfileCV from './Partials/UpdateProfileCV.vue';
import { onMounted, reactive, ref, watch } from 'vue';
import { useAlertStore } from '@/store';

const alertStore = useAlertStore();
const user = usePage().props.auth.user;

const overlay = ref(false);
const pdfViewer = ref(null);
const canvasRef = ref(null);
const isBlurred = ref(true);
const blurMessage = ref('');
const isPin = ref(user.cv_pin);
const attempt = ref(3);
const trigger = ref(false);
const timer = ref(0);
const hasCV = ref(user.cv_file);
const deleteOverlay = ref(false);
const pinError = ref(null);

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = reactive({
    pin_code: null,
    cv_file:user.cv_file || null,
});

onMounted(() => {
    displayCV();
});

function displayCV() {
    if(user.cv_file) {
        var url = "../storage/uploads/cv/"+user.cv_file;
        pdfjsLib.getDocument(url).promise.then(function(pdf) {
            pdf.getPage(1).then(function(page) {
                var defaultViewport = page.getViewport({ scale: 1 });
                var container = pdfViewer.value;
                
                var containerWidth = container.clientWidth;
                var clampedWidth = Math.min(
                    Math.max(containerWidth, 800),
                    900
                );
                var scale = clampedWidth / defaultViewport.width;
                
                var viewport = page.getViewport({ scale: scale });

                var canvas = document.createElement('canvas');
                var context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                var renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };

                page.render(renderContext).promise.then(() => {
                    canvasRef.value = canvas;
                    if(isBlurred.value) generateBlur();
                });

                pdfViewer.value.innerHTML = '';
                pdfViewer.value.appendChild(canvas);
            });
        });
    }
}

const removeCV = () => {
    if(pdfViewer.value){
        pdfViewer.value.innerHTML = '';
    }
    try{
        router.post(route('profile.destroycv'));
        deleteOverlay.value = false;
        hasCV.value = null;
    }catch(error){
        console.error(error);
    }
}; 



function generateBlur(){
    if(canvasRef.value){
        const ctx = canvasRef.value.getContext("2d");
        ctx.globalAlpha = 1;
        ctx.filter = "blur(8px)";
        ctx.drawImage(canvasRef.value,0,0); 
        isBlurred.value = true;
    }
}

function removeBlur(){
    if(canvasRef.value){
        const ctx = canvasRef.value.getContext("2d");
        ctx.filter = "none";
        isBlurred.value = false;
        trigger.value = true;
        displayCV(); 
    }
}

const hasPin = () => {
    if(isPin.value === null){
        overlay.value = true;
    }else{
        overlay.value = true;
        form.pin_code = null;
    }
};

watch(trigger,() => {
      timer.value = 60;
      blurMessage.value = 'A blur sikeresen eltávolítva: '+timer.value+' másodpercig';
      const interval = setInterval(() =>{
          timer.value--;
          blurMessage.value = 'A blur sikeresen eltávolítva: '+timer.value+' másodpercig';
          if(timer.value <= 0){
              clearInterval(interval);
              blurMessage.value = '';
              generateBlur();
          }
      },1000);
});
const createPinCode = async() => {
    if(form.pin_code.length === 4){
        await router.post(route('profile.cv.pincode.create'),{pin_code:form.pin_code},{onSuccess: () => {
            overlay.value = false;
            isPin.value = true;
            form.pin_code = null;
            pinError.value = null;
        },preserveScroll:true});
    }else{
        pinError.value = 'Minimum 4 számot kell használni!';
    }
};

const validatePinCode = async() => {
    if(form.pin_code.length === 4){
        try{
            const response = await axios.post(route('profile.cv.pincode.validate'),{pin_code:form.pin_code},{preserveScroll:true});
            if(response.data.status === 'success'){
                removeBlur();
                alertStore.showAlert(response.data.pin,response.data.status);
                overlay.value = false;
            }else{
                alertStore.showAlert(response.data.pin,response.data.status);
                attempt.value -= 1;
            }
        } catch(error){
            console.error(error);
        }
    }
};

const rules = {
    cvRules:[
        v => (!!v) || 'Kötelező mező!',
        v => (v && v.length < 4) || 'Minimum 4 szám kell legyen!'
    ],
};
</script>

<template>
    <AuthenticatedLayout>
    <Head title="Profil - Képzettségek" />
    <v-container fluid class="px-0 mx-0  gradient-bg">
        <v-container class="!max-w-7xl mx-auto">
            <v-row>
                <v-col>
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800"
                    >
                        Profilom / Alap adatok
                    </h2>
                    <h3 class="text-lg font-semibold leading-tight text-gray-800 mt-1">Ezen az oldalon tudja megtekinteni és szerkeszteni profilját.</h3>
                    <div class="flex gap-3 md:gap-5 text-md mt-3 flex-col md:flex-row">
                        <Link :href="route('profile.edit')" :class="['hover:text-[#fff] transition']">Alap adataim</Link>
                        <Link :href="route('profile.edit.interests')" :class="['hover:text-[#fff] transition']">Érdekeltségeim</Link>
                        <Link :href="route('profile.edit.educations')" :class="['hover:text-[#fff] transition']">Képzettségek</Link>
                        <Link :href="route('profile.edit.cv')" :class="['hover:text-[#fff] transition',{'text-gray-50':$page.url.includes('cv')}]">Önéletrajz</Link>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </v-container>
    <div class="max-w-7xl mx-auto">
        <v-container>
            <v-row>
                <v-col cols="12" md="12">
                    <v-expand-transition>
                        <div
                            class="bg-white p-4 shadow sm:rounded-lg sm:p-8 flex flex-row gap-[2em]"
                        >
                            <UpdateProfileCV/>
                        </div>
                    </v-expand-transition>
                </v-col>
                <v-col cols="12" v-if="hasCV !== null">
                    <div class="w-full bg-white p-4 shadow sm:rounded-lg sm:p-8 flex flex-col gap-[2em]">
                        <p class="text-black mb-3">Feltöltött Önéletrajz</p>
                        <div ref="pdfViewer" class="mx-auto elevation-4 cv-viewer"></div>
                        <div class="mx-auto">{{ blurMessage }}</div>
                        <div v-if="hasCV !== null" class="flex gap-2 md:gap-4">
                            <v-btn @click="deleteOverlay = true" class="!bg-[#A67C52] hover:!bg-[#3A2618] !text-gray-50 hover:text-gray-50">Törlés</v-btn>
                            <v-btn
                                v-if="isBlurred && hasCV !== null"
                                @click="hasPin"
                                class="!bg-[#A67C52] hover:!bg-[#3A2618] !text-gray-50 hover:text-gray-50"
                            >
                                Blur eltávolítása
                            </v-btn>
                        </div>
                    </div>
                </v-col>
                <v-overlay v-model="overlay" scroll-strategy="reposition" class="align-center justify-center" width="100%">
                    <v-card class="!border-2 !border-solid !border-stone-950 w-[90%] xl:w-[40%] lg:w-[90%] mx-auto" elevation="20">
                        <div class="absolute right-2 top-2">
                            <v-icon icon="mdi mdi-close" @click="overlay = false;"></v-icon>
                        </div>
                        <div class="flex flex-col pa-5" v-if="isPin === null">
                            A fiókján még nincs beállítva biztonsági PIN, ahhoz hogy eltávolíthassa a BLUR effektet!
                            A beállításhoz adjon meg egy 4 jegyű pin kódot (Csak számokat tartalmazhat)
                            <v-otp-input :length="4" v-model="form.pin_code" :rules="rules.cvRules"></v-otp-input>
                            <div>
                                <p v-if="pinError" class="text-red mb-3 text-center">{{ pinError }}</p>
                            </div>
                            <div class="flex gap-2 md:gap-4">
                                <v-btn @click="overlay = false" class="hover:bg-red-500 hover:text-gray-50">Mégse</v-btn>
                                <v-btn class="!bg-[#A67C52] hover:!bg-[#3A2618] !text-gray-50 hover:text-gray-50" @click="createPinCode">Mentés</v-btn>
                            </div>
                        </div>
                        <div v-else class="flex flex-col pa-5">
                            Adja meg a beállított biztonsági pin kódot!
                            Próbálkozások száma: {{ attempt }}
                            <v-otp-input :length="4" v-model="form.pin_code" :rules="rules.cvRules"></v-otp-input>
                            <div>
                                <p v-if="pinError">{{ pinError }}</p>
                            </div>
                            <div class="flex gap-2 md:gap-4">
                                <v-btn @click="overlay = false" class="hover:bg-red-500 hover:text-gray-50">Mégse</v-btn>
                                <v-btn class="!bg-[#A67C52] hover:!bg-[#3A2618] !text-gray-50 hover:text-gray-50" @click="validatePinCode" v-if="attempt !== 0">Elfogad</v-btn>
                            </div>
                        </div>
                    </v-card>
                </v-overlay>
                <v-overlay v-model="deleteOverlay" class="align-center justify-center">
                    <v-card class="pa-4" elevation="20">
                        <v-card-title>
                            Biztosan törölni szeretné?
                        </v-card-title>
                        <div class="mt-2 flex gap-2 md:gap-4 justify-center">
                            <v-btn class="!bg-[#A67C52] hover:!bg-[#3A2618] !text-gray-50 hover:text-gray-50" @click="deleteOverlay = false">Nem</v-btn>
                            <v-btn class="!bg-[#A67C52] hover:!bg-[#3A2618] !text-gray-50 hover:text-gray-50" @click="removeCV">Igen</v-btn>
                        </div>
                    </v-card>
                </v-overlay>
            </v-row>
        </v-container>
    </div>  
    </AuthenticatedLayout>
</template>
<style scoped>
.pdf-viewer {
    overflow-x: auto;
    min-width: 800px;
    max-width: 900px;
    margin: 0 auto;
}
@media (max-width:900px) {
    .cv-viewer > *{
        width: 100% !important;
    }
}
</style>
