<script setup>

import {Head, router, useForm} from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    company_name:'',
    email:'',
    password:'',
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
    form.post(route('register.store',{type: 'munkaltato'}),{
        onFinish: () => form.reset('password'),
    });
};

const rules = {
    emailRules:[
        v => !!v || 'Kötelező mező',
        v => (v && /^.{1,}@.+\..+$/.test(v)) ||'Érvényes email címet adjon meg!',
    ],
    companyRules: [
        v => !!v || 'Kötelező mező',
        v => (v && v.length >= 3) || 'Minimum 3 karakter',
        v => /^(?![\s.])[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű0-9 ]+(\.)?$/.test(v) || 'Érvényes formátumban adja meg a cég nevét',
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
    <Head title="Munkáltató - Regisztráció"></Head>
    <v-form @submit.prevent="validate" ref="formValidator">    
        <div>
            <v-text-field label="Cégnév *" placeholder="Tesztcég" v-model="form.company_name" type="text"
            :rules="rules.companyRules" :error="!!form.errors.company_name" :error-messages="form.errors.company_name"
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
            <v-text-field label="Jelszó *" v-model="form.password" :type="show1 ? 'text' : 'password'"
            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" @click:append="show1 = !show1"
            :rules="rules.passwordRules"
            ></v-text-field>
        </div>
        <div class="flex md:gap-5 flex-wrap gap-2 mt-3">
            <v-btn class="hover:bg-sky-500 hover:text-gray-50" type="submit">Regisztráció</v-btn>
        </div>
        <div class="flex flex-col gap-1 md:gap-2 mt-2 mt-md-4">
            <p>Már van fiókom szeretnék bejelentkezni.</p>
            <div><v-btn class="hover:bg-sky-500 hover:text-gray-50" @click="router.visit(route('login'))">Bejelentkezés</v-btn></div>
        </div>
    </v-form>
</div>

</template>