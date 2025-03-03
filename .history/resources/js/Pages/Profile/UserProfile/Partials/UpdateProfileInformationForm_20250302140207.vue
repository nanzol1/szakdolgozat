<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref, watch} from 'vue';
import { enUS, NDatePicker,NConfigProvider  } from 'naive-ui';
import { hu } from 'date-fns/locale'


const huLocale = {
  ...enUS,
  DatePicker: {
    ...enUS.DatePicker,
    firstDayOfWeek: 1,
    now: "Mai dátum"
  }
}

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    }
});

const user = usePage().props.auth.user;

const pdfViewer = ref(null);
const isBlurred = ref(true);
const canvasRef = ref(null);
const trigger = ref(false);
const timer = ref(60);
const blurMessage = ref('');
const driverLicenses = ref([]);
const isPickerOpen = ref(false)
const imagePreview = ref(null);


const handlePickerOpen = (isOpen) => {
  isPickerOpen.value = isOpen
}

watch(isPickerOpen, (isOpen) => {
  if (!isOpen) return
  
  setTimeout(() => {
    const weekdays = document.querySelectorAll('.n-date-panel-weekdays__day') 
    
    const huShortMap = {
      'Su': 'V',
      'Mo': 'H',
      'Tu': 'K',
      'We': 'Sz',
      'Th': 'Cs',
      'Fr': 'P',
      'Sa': 'Szo'
    }
    
    if (weekdays.length === 7) {
      weekdays.forEach((el, index) => {
        const engShort = el.textContent.trim()
        el.textContent = huShortMap[engShort] || engShort
      })
    }
  }, 50)
})


onMounted(() => {
    if(user.driverLicense && user.driverLicense.length > 0){
        driverLicenses.value = user.driverLicense;
    }
    //displayCV();
});
/*function displayCV() {
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
}*/

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


const form = useForm({
    username: user.username,
    name: user.name,
    email: user.email,
    sex: user.sex,
    birth_date: user.birth_date,
    phone_number: user.phone_number,
    county: user.county,
    zip: user.zip,
    city: user.city,
    address: user.address,
    profile_photo_path: null,
    driverLicense: [],
});

const renderImage = (event) => {
    let file = null;
    if(event.target.files){
        file = event.target.files[0];
    }
    if(file && file.type.startsWith('image/')){
        form.profile_photo_path = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }else{
        imagePreview.value = null;
        form.profile_photo_path = null;
    }
};

watch(driverLicenses,(newValue) => {
    form.driverLicense = newValue;
});

  const removeCV = () => {
    if(pdfViewer.value){
        pdfViewer.value.innerHTML = '';
    }
    form.post(route('profile.destroycv'),{
        onFinish: () => {location.reload();}
    });
  };
  const submit = () =>{
    form.post(route('profile.update'));
  }

  watch(trigger,() => {
        timer.value = 60;
        blurMessage.value = 'A blur sikeresen eltávolítva '+timer.value+' másodpercig';
        const interval = setInterval(() =>{
            timer.value--;
            blurMessage.value = 'A blur sikeresen eltávolítva '+timer.value+' másodpercig';
            if(timer.value <= 0){
                clearInterval(interval);
                blurMessage.value = '';
                generateBlur();
            }
        },1000);
    });

</script>

<template>
    <v-container>
        <v-row>
            <v-col cols="12">
                <section class="w-full">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Profile Information
                        </h2>
                    
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Update your account's profile information and email address.
                        </p>
                    </header>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-form
                                    @submit.prevent="submit"
                                    class="mt-6 space-y-6"
                                    enctype="multipart/form-data">
                                <v-row>
                                    <v-col cols="12" lg="6">
                                        <div>
                                            <v-text-field label="Felhasználónév" type="text" v-model="form.username" :error="!!form.errors.username" :error-messages="form.errors.username"></v-text-field>
                                        </div>
                                    </v-col>
                                    <v-col cols="12" lg="6">
                                        <div>
                                            <v-text-field label="Vezetéknév Keresztnév" type="text" v-model="form.name" :error="!!form.errors.name" :error-messages="form.errors.name"></v-text-field>
                                        </div>
                                    </v-col>
                                    <v-col cols="12">
                                        <div>
                                            <v-text-field label="E-mail cím" type="email" v-model="form.email" :error="!!form.errors.email" :error-messages="form.errors.email" disabled></v-text-field>
                                        </div>
                                    </v-col>
                                    <v-col cols="12" lg="6">
                                        <div>
                                            <v-select
                                                label="Neme"
                                                :items="['Férfi', 'Nő']"
                                                v-model="form.sex"
                                                :error="!!form.errors.sex"
                                                :error-messages="form.errors.sex"
                                                clearable
                                            ></v-select>
                                        </div>
                                    </v-col>
                                    <v-col cols="12" lg="6">
                                        <div class="h-[56px] flex align-center">
                                            <NConfigProvider 
                                            :locale="huLocale"
                                            :date-locale="hu"
                                            >
                                              <n-date-picker 
                                                v-model:value="form.birth_date" 
                                                type="date" 
                                                :default-value="Date.now()"
                                                :update-value-on-close="true" 
                                                placeholder="Születési dátum" 
                                                size="large"
                                                @update:show="handlePickerOpen"
                                              />
                                            </NConfigProvider>
                                        </div>
                                    </v-col>
                                    <v-col cols="12" lg="6">
                                        <div>
                                            <v-text-field label="Telefonszám" type="text" v-model="form.phone_number" :error="!!form.phone_number" :error-messages="form.phone_number"></v-text-field>
                                        </div>
                                    </v-col>
                                    <v-col cols="12" lg="6">
                                        <div>
                                            <v-text-field label="Irányítószám" type="text" v-model="form.zip" :error="!!form.zip" :error-messages="form.zip"></v-text-field>
                                        </div>
                                    </v-col>
                                    <v-col cols="12" lg="6">
                                        <div>
                                            <v-text-field label="Megye" type="text" v-model="form.county" :error="!!form.county" :error-messages="form.county"></v-text-field>
                                        </div>
                                    </v-col>
                                    <v-col cols="12" lg="6">
                                        <div>
                                            <v-text-field label="Város" type="text" v-model="form.city" :error="!!form.city" :error-messages="form.city"></v-text-field>
                                        </div>
                                    </v-col>
                                    <v-col cols="12">
                                        <div>
                                            <v-text-field label="Cím" type="text" v-model="form.address" :error="!!form.address" :error-messages="form.address"></v-text-field>
                                        </div>
                                    </v-col>
                                    <v-col cols="12">
                                        <div>
                                            <v-select
                                                clearable
                                                chips
                                                label="Jogosítvány"
                                                :items="['A', 'B', 'C', 'D', 'B+E', 'C+E','D+E']"
                                                v-model="driverLicenses"
                                                :error="!!form.errors.driverlicense"
                                                :error-messages="form.errors.driverlicense"
                                                multiple
                                            ></v-select>
                                        </div>
                                    </v-col>
                                    <v-col cols="12">
                                        <div>
                                            <v-file-input label="Profilkép" :error="!!form.errors.cv_file"
                                            :error-messages="form.errors.cv_file" @change="renderImage" v-on:click:clear="renderImage"></v-file-input>
                                        </div>
                                        <v-expand-transition>
                                            <div class="bg-gray-200 pa-2 rounded-lg" v-if="imagePreview">
                                                <div class="font-weight-black text-xl">Előnézet</div>
                                                <div>
                                                    <div class="w-[100px] h-[100px] my-2">
                                                        <v-img
                                                          :width="100"
                                                          :height="100"
                                                          aspect-ratio="16/9"
                                                          cover
                                                          :src="imagePreview"
                                                        >
                                                    </v-img>
                                                    </div>
                                                </div>
                                            </div>
                                        </v-expand-transition>
                                    </v-col>
                                </v-row>
                            
                                <div v-if="mustVerifyEmail && user.email_verified_at === null">
                                    <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                                        Your email address is unverified.
                                        <Link
                                            :href="route('verification.send')"
                                            method="post"
                                            as="button"
                                            class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                                        >
                                            Click here to re-send the verification email.
                                        </Link>
                                    </p>
                                
                                    <div
                                        v-show="status === 'verification-link-sent'"
                                        class="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
                                    >
                                        A new verification link has been sent to your email address.
                                    </div>
                                </div>
                            
                                <div class="flex items-center gap-4">
                                    <v-btn type="submit" :disabled="form.processing" position="absolute">
                                        <v-fade-transition>
                                            <span v-if="form.recentlySuccessful">
                                                Mentve
                                            </span>
                                            <span v-else>
                                                Mentés
                                            </span>
                                        </v-fade-transition>
                                    </v-btn>
                                </div>
                            </v-form>
                            </v-col>
                        </v-row>
                    </v-container>
                </section>
            </v-col>
            <v-col cols="12" lg="6">
                <section class="w-full">
                    <p class="text-white mb-3">Feltöltött CV</p>
                    <div ref="pdfViewer"></div>
                    <div v-if="user.cv_file">
                        <PrimaryButton @click="removeCV" class="mt-3">Törlés</PrimaryButton>
                    </div>
                    <button
                        v-if="isBlurred && user.cv_file"
                        @click="removeBlur"
                        class="mt-4 px-4 py-2 bg-blue-500 text-white rounded"
                    >
                        Blur eltávolítása
                    </button>
                    {{ blurMessage }}
                </section>
            </v-col>
        </v-row>
    </v-container>
</template>
