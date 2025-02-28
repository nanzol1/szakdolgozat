<script setup>

import {Head, useForm} from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    email:'',
    password:'',
});

const show1 = ref(false);

const submit = () =>{
    form.post(route('login',{logintype: 'munkavallalo'}),{
        onFinish: () => form.reset('password'),
    });
};

const rules = {
    emailRules:[
        v => (v && /^.{1,}@.+\..+$/.test(v)) ||'Érvényes email címet adjon meg!',
    ],
};
</script>

<template>

    <Head title="Munkavállaló"></Head>

    <v-form @submit.prevent="submit">    
        <div>
            <v-text-field label="E-mail cím" hint="A belépéshez írd be az e-mail címed" v-model="form.email" type="email"
            :rules="rules.emailRules" validate-on="blur"
            ></v-text-field>
        </div>
        <div>
            <v-text-field label="Jelszó" hint="A belépéshez írd be a jelszavad" v-model="form.password" :type="show1 ? 'text' : 'password'"
            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" @click:append="show1 = !show1"
            ></v-text-field>
        </div>
        <div>
            <button type="submit">Bejelentkezés</button>
        </div>
    </v-form>

</template>