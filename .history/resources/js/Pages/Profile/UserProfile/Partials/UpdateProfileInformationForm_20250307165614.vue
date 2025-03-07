<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref, watch} from 'vue';
import { enUS, NDatePicker,NConfigProvider  } from 'naive-ui';
import { hu } from 'date-fns/locale'
import { useAlertStore } from '@/store';
import _ from 'lodash';

const alertStore = useAlertStore();

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
});

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

const fromZip = ref(false);

watch(() => form.zip, async (data) => {
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
                    form.county = data.county;
                    form.city = data.place_name;
                    form.zip = data.postal_code;
                });
            }
        }catch (error){
            console.error(error);
            form.county = null;
            form.city = null;
        } finally{
            fromZip.value = false;
        }
    }else{
        form.county = null;
        form.city = null;
        form.zip = null;
    }
},1000);


const formValidator = ref(null);

const validate = async() =>{
    let { valid } = await formValidator.value.validate();

    if(valid){
        submit();
    }else{
        alertStore.showAlert('Hiba történt! Ellenőrízze az adatok helyességét!','error')
    }
}

const submit = () =>{
    form.post(route('profile.update'),{preserveScroll:true});
}

const rules = {
    usernameRules: [
        v => (v && v.length >= 3) || 'Minimum 3 karakter',
        v => /^(?![\s.])[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű0-9]+(\.)?$/.test(v) || 'Érvényes formátumban adja meg a felhasználó nevét',
    ],
    nameRule: [
        v => (v && v.length >= 3) || 'Minimum 3 karakter',
        v => /^(?![\s.])(?!.*\s{2})([A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű0-9]+( [A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű0-9]+)*)(?<![\s.])$/.test(v) || 'Érvényes formátumban adja meg a saját nevét',
    ],
    phoneRule:[
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
    fileRules: [
        v => (!v || (v instanceof File && /image\/(jpeg|png|jpg)/.test(v.type))) || 'Csak képfájlok (.jpg, .jpeg, .png) engedélyezettek',
    ],
};
</script>

<template>
    <v-container>
        <v-row>
            <v-col cols="12">
                <section class="w-full">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Profil adatok
                        </h2>
                    
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Ezen a fülön van lehetőség a profil adatainak frissítésére!
                        </p>
                    </header>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-form
                                    @submit.prevent="validate"
                                    class="mt-6 space-y-6"
                                    enctype="multipart/form-data"
                                    ref="formValidator"
                                    >
                                <v-row>
                                    <v-col cols="12" lg="6">
                                        <div>
                                            <v-text-field label="Felhasználónév" :rules="rules.usernameRules" type="text" v-model="form.username" :error="!!form.errors.username" :error-messages="form.errors.username"></v-text-field>
                                        </div>
                                    </v-col>
                                    <v-col cols="12" lg="6">
                                        <div>
                                            <v-text-field label="Vezetéknév Keresztnév" type="text" :rules="rules.nameRule" v-model="form.name" :error="!!form.errors.name" :error-messages="form.errors.name"></v-text-field>
                                        </div>
                                    </v-col>
                                    <v-col cols="12">
                                        <div>
                                            <v-text-field label="E-mail cím" type="email" :rules="rules.emailRules" v-model="form.email" :error="!!form.errors.email" :error-messages="form.errors.email" disabled></v-text-field>
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
                                                :rules="rules.sexRules"
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
                                            <v-text-field label="Telefonszám" type="text" v-model="form.phone_number" :rules="rules.phoneRule" :error="!!form.errors.phone_number" :error-messages="form.errors.phone_number"></v-text-field>
                                        </div>
                                    </v-col>
                                    <v-col cols="12" lg="6">
                                        <div>
                                            <v-text-field label="Irányítószám" type="text" v-model="form.zip" :rules="rules.zipRules" :error="!!form.errors.zip" :error-messages="form.errors.zip"></v-text-field>
                                        </div>
                                    </v-col>
                                    <v-col cols="12" lg="6">
                                        <div>
                                            <v-text-field label="Megye" type="text" v-model="form.county" :rules="rules.countyRules" :error="!!form.errors.county" :error-messages="form.errors.county"></v-text-field>
                                        </div>
                                    </v-col>
                                    <v-col cols="12" lg="6">
                                        <div>
                                            <v-text-field label="Város" type="text" v-model="form.city" :rules="rules.cityRules" :error="!!form.errors.city" :error-messages="form.errors.city"></v-text-field>
                                        </div>
                                    </v-col>
                                    <v-col cols="12">
                                        <div>
                                            <v-text-field label="Cím" type="text" v-model="form.address" :error="!!form.errors.address" :error-messages="form.errors.address"></v-text-field>
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
                                            :error-messages="form.errors.cv_file" accept="image/png, image/jpeg, image/bmp" :rules="rules.fileRules" @change="renderImage" v-on:click:clear="renderImage"></v-file-input>
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
                                    <v-btn type="submit" :disabled="form.processing" class="hover:bg-sky-500 hover:text-white">
                                        {{form.recentlySuccessful ? 'Mentve' : 'Mentés'}}
                                    </v-btn>
                                </div>
                            </v-form>
                            </v-col>
                        </v-row>
                    </v-container>
                </section>
            </v-col>
        </v-row>
    </v-container>
</template>
