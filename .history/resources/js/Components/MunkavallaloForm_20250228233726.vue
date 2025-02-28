<script setup>

import {Head, useForm} from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    username:'',
    email:'',
    name:'',
    password:'',
    cv:null,
});


const show1 = ref(false);
const formValidator = ref(null);

const validate = async() =>{
    let { valid } = await formValidator.value.validate();

    if(valid){
        submit();
    }
}

const submit = () =>{
    form.post(route('register.store', {type:'munkavallalo'}),{
        onFinish: () => form.reset('password'),
    });
};

const rules = {
    emailRules:[
        v => !!v || 'Kötelező mező',
        v => (v && /^.{1,}@.+\..+$/.test(v)) ||'Érvényes email címet adjon meg!',
    ],
    usernameRules: [
        v => !!v || 'Kötelező mező',
        v => (v && v.length >= 3) || 'Minimum 3 karakter',
        v => /^(?![\s.])[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű0-9]+(\.)?$/.test(v) || 'Érvényes formátumban adja meg a felhasználó nevét',
    ],
    wholenameRules: [
        v => !!v || 'Kötelező mező',
        v => (v && v.length >= 3) || 'Minimum 3 karakter',
        v => /^(?![\s.])(?!.*\s{2})([A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű0-9]+( [A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű0-9]+)*)(?<![\s.])$/.test(v) || 'Érvényes formátumban adja meg a felhasználó nevét',
    ],
    passwordRules: [
        v => !!v || 'A jelszó kötelező',
        v => (v && v.length >= 6) || 'Minimum 6 karakter',
        v => /[A-Z]/.test(v) || 'Tartalmazzon nagybetűt',
        v => /[a-z]/.test(v) || 'Tartalmazzon kisbetűt',
        v => /[0-9]/.test(v) || 'Tartalmazzon számot',
        v => /[\W_]/.test(v) || 'Tartalmazzon speciális karaktert (!@#$% stb.)',
        v => !/(.)\1\1/.test(v) || 'Nem lehet 3 egyforma karakter egymás után',
        v => !/\s/.test(v) || 'Nem lehet szóköz',
        v => !/(password|123456|qwerty|asdfgh|jelszo|secret|admin|login|hello|love|asd|asdasd|gyemge)/i.test(v) || 'Gyenge jelszó',
    ],
};


</script>


<template>

<div>
    <Head title="Munkavállaló - Regisztráció"></Head>
    <v-form @submit.prevent="validate" enctype="multipart/form-data" ref="formValidator">

        <div>
            <v-text-field label="Felhasználó név *" placeholder="peldafelhasznalo" v-model="form.username" type="text"
            :rules="rules.usernameRules"
            :error="!!form.errors.username"
            :error-messages="form.errors.username"
            @input="form.clearErrors('username')"
            ></v-text-field>
        </div>
        <div>
            <v-text-field label="E-mail cím *" placeholder="pelda@pelda.hu" v-model="form.email"
             type="email" autocomplete="email"
             :rules="rules.emailRules"
            :error="!!form.errors.email"
            :error-messages="form.errors.email"
            @input="form.clearErrors('email')"
            ></v-text-field>
        </div>
        <div>
            <v-text-field label="Teljes név *" placeholder="Példa Gábriel" v-model="form.name" type="text"
            :rules="rules.wholenameRules"
            ></v-text-field>
        </div>
        <div>
            <v-text-field label="Jelszó *" v-model="form.password" :type="show1 ? 'text' : 'password'"
            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" @click:append="show1 = !show1"
            :rules="rules.passwordRules"
            ></v-text-field>
        </div>
        <div>
            <v-file-input label="Önéletrajz" @change="form.cv = $event.target.files[0]" 
            show-size accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/png, image/jpeg, image/bmp"
            ></v-file-input>
        </div>

        <div>
            <v-btn class="hover:bg-sky-500 hover:text-gray-50" type="submit">Regisztráció</v-btn>
        </div>
        <div>
            <p>Már van fiókom szeretnék bejelentkezni.</p>
            <v-btn class="hover:bg-sky-500 hover:text-gray-50" type="submit">Regisztráció</v-btn>
        </div>

    </v-form>
</div>

</template>