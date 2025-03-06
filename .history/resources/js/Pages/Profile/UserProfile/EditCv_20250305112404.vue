<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import UpdateProfileCV from './Partials/UpdateProfileCV.vue';
import { onMounted, reactive, ref } from 'vue';

const user = usePage().props.auth.user;
const overlay = ref(false);
const pdfViewer = ref(null);
const canvasRef = ref(null);
const isBlurred = ref(true);
const blurMessage = ref('');
const isPin = ref(user.cv_pin);

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
});

onMounted(() => {
    displayCV();
});
function displayCV() {
    if(user.cv_file){
        var url = "../storage/uploads/cv/"+user.cv_file;
            pdfjsLib.getDocument(url).promise.then(function(pdf) {
            pdf.getPage(1).then(function(page) {
            var scale = 1.0;
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
                
                if(isBlurred.value){
                    generateBlur();
                }

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
    form.post(route('profile.destroycv'),{
        onFinish: () => {location.reload();}
    });
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
    }
};

const createPinCode = async() => {
    await router.post(route('profile.cv.pincode.create'),{pin_code:form.pin_code},{onSuccess: () => {
        overlay.value = false;
    },preserveScroll:true});
};

const validatePinCode = async() => {
    const response = await axios.post(route('profile.cv.pincode.validate'),{pin_code:form.pin_code},{preserveScroll:true});
    if(response.data.status === 'success'){
        removeBlur();
        
    }
};
</script>

<template>
    <AuthenticatedLayout>
    <Head title="Profil - Képzettségek" />
    <v-container fluid class="bg-white px-0 mx-0">
        <v-container class="!max-w-7xl mx-auto">
            <v-row>
                <v-col>
                    <h2
                        class="text-xl font-semibold leading-tight text-gray-800"
                    >
                        Profilom / Önéletrajz
                    </h2>
                    <h3 class="text-lg font-semibold leading-tight text-gray-800 mt-1">Ezen az oldalon tudja megtekinteni és szerkeszteni profilját.</h3>
                    <div class="flex gap-3 md:gap-5 text-md mt-3">
                        <Link :href="route('profile.edit')" :class="['hover:text-sky-500 transition',{'text-sky-500':$page.url === '/profile'}]">Alap adataim</Link>
                        <Link :href="route('profile.edit.interests')" :class="['hover:text-sky-500 transition',{'text-sky-500':$page.url.includes('/erdekeltsegeim')}]">Érdekeltségeim</Link>
                        <Link :href="route('profile.edit.educations')" :class="['hover:text-sky-500 transition',{'text-sky-500':$page.url.includes('/kepzettsegek')}]">Képzettségek</Link>
                        <Link :href="route('profile.edit.cv')" :class="['hover:text-sky-500 transition',{'text-sky-500':$page.url.includes('/cv')}]">Önéletrajz</Link>
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
                <v-col cols="12">
                    <div class="w-full bg-white p-4 shadow sm:rounded-lg sm:p-8 flex flex-col gap-[2em]">
                        <p class="text-black mb-3">Feltöltött CV</p>
                        <div ref="pdfViewer" class="mx-auto elevation-4"></div>
                        <div v-if="user.cv_file">
                            <v-btn @click="removeCV" class="hover:bg-red-500 hover:text-gray-50 mt-3">Törlés</v-btn>
                        </div>
                        <div>
                            <v-btn
                                v-if="isBlurred && user.cv_file"
                                @click="hasPin"
                                class="mt-4 px-4 py-2 bg-sky-500"
                            >
                                Blur eltávolítása
                            </v-btn>
                        </div>
                        {{ blurMessage }}
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
                            <v-otp-input :length="4" v-model="form.pin_code"></v-otp-input>
                            <div class="flex gap-2 md:gap-4">
                                <v-btn @click="overlay = false" class="hover:bg-red-500 hover:text-gray-50">Mégse</v-btn>
                                <v-btn class="hover:bg-sky-500 hover:text-gray-50" @click="createPinCode">Mentés</v-btn>
                            </div>
                        </div>
                        <div v-else class="flex flex-col pa-5">
                            Adja meg a beállított biztonsági pin kódot!
                            <v-otp-input :length="4" v-model="form.pin_code"></v-otp-input>
                            <div class="flex gap-2 md:gap-4">
                                <v-btn @click="overlay = false" class="hover:bg-red-500 hover:text-gray-50">Mégse</v-btn>
                                <v-btn class="hover:bg-sky-500 hover:text-gray-50" @click="validatePinCode">Küldés</v-btn>
                            </div>
                        </div>
                    </v-card>
                    <v-overlay v-model="deleteOverlay" class="align-center justify-center">
                        <v-card class="pa-4" elevation="20">
                            <v-card-title>
                                Biztosan törölni szeretné?
                            </v-card-title>
                            <div class="mt-2 flex gap-2 md:gap-4 justify-center">
                                <v-btn class="hover:bg-sky-500 hover:text-gray-50" @click="deleteOverlay = false">Nem</v-btn>
                                <v-btn class="hover:bg-red-500 hover:text-gray-50" @click="deleteEducation">Igen</v-btn>
                            </div>
                        </v-card>
                    </v-overlay>
                </v-overlay>
            </v-row>
        </v-container>
    </div>  
    </AuthenticatedLayout>
</template>
<style>
    html{
        scroll-behavior: smooth;
    }
</style>
