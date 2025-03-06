<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { NConfigProvider, NDatePicker } from 'naive-ui';
import { hu } from 'date-fns/locale'
import UpdateProfileCV from './Partials/UpdateProfileCV.vue';
import { onMounted } from 'vue';


const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
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
                <v-col cols="12" md="6">
                    <v-expand-transition>
                        <div
                            class="bg-white p-4 shadow sm:rounded-lg sm:p-8 flex flex-row gap-[2em]"
                        >
                            <UpdateProfileCV/>
                        </div>
                    </v-expand-transition>
                </v-col>
                <v-col cols="12" md="6">
                    <v-expand-transition>
                        <div
                            class="bg-white p-4 shadow sm:rounded-lg sm:p-8 flex flex-row gap-[2em]"
                        >
                            <div v-show="displayCV">

                            </div>
                        </div>
                    </v-expand-transition>
                </v-col>
                <v-overlay v-model="overlay" scroll-strategy="reposition" class="align-center justify-center" width="100%">
                    <v-card class="education-card !border-2 !border-solid !border-stone-950 w-[90%] xl:w-[40%] lg:w-[90%] mx-auto" elevation="20">
                        <div class="absolute right-2 top-2">
                            <v-icon icon="mdi mdi-close" @click="overlay = false; isEditing = false"></v-icon>
                        </div>
                        <div class="flex flex-col pa-5">
                            <div v-if="!isEditing">
                                <div>
                                    Végzettség típusa: {{ selectedEducation.education_type }}
                                </div>
                                <div v-if="selectedEducation.education_level">
                                    Végzettség szintje: {{ selectedEducation.education_level }}
                                </div>
                                <div>
                                    Intézmény neve: {{ selectedEducation.institution_name }}
                                </div>
                                <div>
                                    Végzettség megnevezése: {{ selectedEducation.education_name }}
                                </div>
                                <div>
                                    Időtartam: {{ new Date(selectedEducation.date_from).toLocaleDateString('HU') }} - {{ new Date(selectedEducation.date_to).toLocaleDateString('HU') }}
                                </div>
                                <div>
                                    Megszerzés helye: {{ selectedEducation.education_place }}
                                </div>
                            </div>
                            <div v-else>
                                <v-form @submit.prevent="submit">
                                    <div>
                                        Végzettség típusa:                                 
                                        <v-combobox
                                            clearable
                                            label="Végzettség típusa"
                                            :items="['Általános Iskola', 'Szakiskola Szakmunkás képző', 'Középiskola', 'Felsőoktatási szakképesítés', 
                                            'Főiskola', 'Egyetem','Állami rendszerű egyéb képzés','Állami rendszerű, nem diplomás képzés',
                                            'Coaching','OKJ-rendszerű tanfolyam','Tanfolyam','Tréning']"
                                            v-model="form.education_type"
                                            :error="!!form.errors.education_type"
                                            :error-messages="form.errors.education_type"
                                        ></v-combobox>
                                    </div>
                                    <div v-if="form.education_type === 'Egyetem' || form.education_type === 'Főiskola'">
                                        <v-combobox
                                            clearable
                                            label="Megszerzett fokozat"
                                            :items="['Bsc - Alapdiploma', 'Dimploma (Kiegészítő képzésen)', 'Dimploma (Teljes képzés)', 'Felsőoktatási oktató', 
                                            'MBA', 'Msc - Mesterfokozat','PhD']"
                                            v-model="form.education_level"
                                            :error="!!form.errors.education_level"
                                            :error-messages="form.errors.education_level"
                                        ></v-combobox>
                                    </div>
                                    <div>
                                        Intézmény neve: 
                                        <v-combobox
                                            clearable
                                            label="Intézmény megnevezése"
                                            :items="getInstitution"
                                            v-model="searchQuery"
                                            :error-messages="form.errors.institution_name"
                                            :error="!!form.errors.institution_name"
                                            item-title="title"
                                            item-value="id"
                                        ></v-combobox>
                                    </div>
                                    <div>
                                        Végzettség megnevezése: <v-text-field label="Végzettség megnevezése" v-model="form.education_name"
                                        type="text" :error="!!form.errors.education_name" :error-messages="form.errors.education_name"
                                        ></v-text-field>
                                    </div>
                                    <div>
                                        Időtartam: 
                                        <n-config-provider
                                            :locale="huLocale"
                                            :date-locale="hu"
                                            class="mb-3"
                                            >
                                              <n-date-picker 
                                                v-model:value="form.date_from" 
                                                type="month" 
                                                :update-value-on-close="true" 
                                                placeholder="Oktatás ideje (-től)" 
                                                size="large"
                                                :error="!!form.errors.date_from"
                                                :error-messages="!!form.errors.date_from"
                                              />
                                            </n-config-provider>
                                            <n-config-provider
                                                :locale="huLocale"
                                                :date-locale="hu"
                                                class="mb-3"
                                                >
                                                  <n-date-picker 
                                                    v-model:value="form.date_to" 
                                                    type="month" 
                                                    :update-value-on-close="true" 
                                                    placeholder="Oktatás ideje (-ig)" 
                                                    size="large"
                                                  />
                                            </n-config-provider>
                                    </div>
                                    <div>
                                        Megszerzés helye: <v-text-field label="Végzettség megnevezése" v-model="form.education_place"
                                        type="text" :error="!!form.errors.education_place" :error-messages="form.errors.education_place"
                                        ></v-text-field>
                                    </div>
                                </v-form>
                            </div>
                            <div class="flex justify-between flex-wrap mt-2">
                                <div>
                                    <v-btn class="hover:bg-sky-500 hover:text-gray-50" @click="deleteOverlay = true">Törlés</v-btn>
                                </div>
                                <div class="flex gap-2 md:gap-4 flex-wrap">
                                    <v-btn v-if="isEditing" type="submit" @click="submit" class="hover:bg-sky-500 hover:text-gray-50">Mentés</v-btn>
                                    <v-btn @click="isEditing = !isEditing" v-else class="hover:bg-sky-500 hover:text-gray-50">Szerkeszt</v-btn>
                                    <v-btn class="hover:bg-sky-500 hover:text-gray-50" @click="overlay = false; isEditing = false">Mégsem</v-btn>
                                </div>
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
