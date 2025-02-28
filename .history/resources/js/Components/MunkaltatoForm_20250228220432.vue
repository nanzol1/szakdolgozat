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
        v => /^(?!\s)[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű]+(?<!\s)$/.test(v) || 'Csak betűk, nincs szóköz/speciális karakter az elején/végén',
    ],
    passwordRules:[

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