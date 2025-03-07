<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;
const imagePreview = ref(null);

const form = useForm({
    company_name: user.company_name,
    email: user.email,
    company_pnumber: user.company_pnumber,
    company_address: user.company_address,
    company_desc: user.company_desc,
    profile_picture: user.profile_pict,
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
    form.post(route('cprofile.update'),{preserveScroll:true});
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
                            Profil információk
                        </h2>
                    
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Ezen a fülön tudja szükség esetén frissíteni profil adatait
                        </p>
                    </header>
                
                    <v-form
                        @submit.prevent="validate"
                        class="mt-6 space-y-6"
                        enctype="multipart/form-data"
                        ref="formValidator"
                    >
                        <v-row>
                            <v-col cols="12">
                                <v-text-field type="text" label="Cégnév" v-model="form.company_name"
                                :error="!!form.errors.namcompany_name" :error-messages="form.errors.company_name"></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field type="email" label="E-mail cím" v-model="form.email"
                                :error="!!form.errors.email" :error-messages="form.errors.email" disabled></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field type="text" label="Telefonszám" v-model="form.company_pnumber" :rules="rules.phoneRule"
                                :error="!!form.errors.company_pnumber" :error-messages="form.errors.company_pnumber"></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field type="text" label="Teljes cím" v-model="form.company_address"
                                :error="!!form.errors.company_address" :error-messages="form.errors.company_address"></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-textarea label="Cég leírás" v-model="form.company_desc" auto-grow
                                :error="!!form.errors.company_desc" :error-messages="form.errors.company_desc"></v-textarea>
                            </v-col>
                            <v-col cols="12">
                                <div>
                                    <v-file-input label="Cég profilkép" :error="!!form.errors.profile_picture"
                                    :error-messages="form.errors.profile_picture" accept="image/png, image/jpeg, image/bmp" :rules="rules.fileRules" @change="renderImage" v-on:click:clear="renderImage"></v-file-input>
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
                            <div class="flex items-center gap-4">
                                <v-btn type="submit" :disabled="form.processing" class="hover:bg-sky-500 hover:text-white">
                                    {{form.recentlySuccessful ? 'Mentve' : 'Mentés'}}
                                </v-btn>
                            </div>
                    </v-form>
                </section>
            </v-col>
        </v-row>
    </v-container>
</template>
