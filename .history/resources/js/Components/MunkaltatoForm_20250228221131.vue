<script setup>

import {Head, useForm} from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    company_name:'',
    email:'',
    password:'',
});


const submit = () =>{
    form.post(route('register.store',{type: 'munkaltato'}),{
        onFinish: () => form.reset('password'),
    });
};

const show1 = ref(false);

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
        v => (v && v.length >= 12) || 'Minimum 12 karakter',
        v => /[A-Z]/.test(v) || 'Tartalmazzon nagybetűt',
        v => /[a-z]/.test(v) || 'Tartalmazzon kisbetűt',
        v => /[0-9]/.test(v) || 'Tartalmazzon számot',
        v => /[\W_]/.test(v) || 'Tartalmazzon speciális karaktert (!@#$% stb.)',
        v => !/(.)\1\1/.test(v) || 'Nem lehet 3 egyforma karakter egymás után',
        v => !/(012|123|234|345|456|567|678|789|890|abc|bcd|cde|def|efg|fgh|ghi|hij|ijk|jkl|klm|lmn|mno|nop|opq|pqr|qrs|rst|stu|tuv|uvw|vwx|wxy|xyz)/i.test(v) || 'Egymás után következő karakterek tiltottak',
        v => !/\s/.test(v) || 'Nem lehet szóköz',
        v => !/(password|123456|qwerty|asdfgh|jelszo|secret|admin|login|hello|love|asd|asdasd|gyemge)/i.test(v) || 'Gyenge jelszó',
    ],
};

</script>


<template>

<div>
    <Head title="Munkáltató - Regisztráció"></Head>
    <v-form @submit.prevent="submit">    
        <div>
            <v-text-field label="Cég név" placeholder="Tesztcég" v-model="form.company_name" type="text"
            :rules="rules.companyRules"
            ></v-text-field>
        </div>
        <div>
            <v-text-field label="E-mail cím" placeholder="pelda@pelda.hu" v-model="form.email"
             type="email" autocomplete="email"
            :rules="rules.emailRules"
            ></v-text-field>
        </div>
        <div>
            <v-text-field label="Jelszó" v-model="form.password" :type="show1 ? 'text' : 'password'"
            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" @click:append="show1 = !show1"
            :rules="rules.passwordRules"
            ></v-text-field>
        </div>
        <div class="flex md:gap-5 flex-wrap gap-2">
            <v-btn class="hover:bg-sky-500 hover:text-gray-50">Regisztráció</v-btn>
        </div>
    </v-form>
</div>

</template>