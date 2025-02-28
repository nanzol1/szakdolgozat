<script setup>

import {Head, useForm} from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    email:'',
    password:'',
});

const show1 = ref(false);

const submit = () =>{
    form.post(route('login',{logintype: 'munkaltato'}),{
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
    <div>
        <Head title="Munkáltató - Bejelentkezés"></Head>

        <v-form @submit.prevent="submit">    
            <div>
                <v-text-field label="E-mail cím" v-model="form.email" type="email"
                :rules="rules.emailRules"
                ></v-text-field>
            </div>
            <div>
                <v-text-field label="Jelszó" v-model="form.password" :type="show1 ? 'text' : 'password'"
                :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" @click:append="show1 = !show1"
                ></v-text-field>
            </div>
            <div class="flex md:gap-5 flex-wrap gap-2">
                <v-btn class="hover:bg-sky-500 hover:text-gray-50" type="submit">Bejelentkezés</v-btn>
                Még nem regisztráltál?
                <v-btn class="hover:bg-sky-500 hover:text-gray-50" @click="router.visit(route('register',{type:'munkaltato'}))">Regisztráció</v-btn>
            </div>
        </v-form>
    </div>
</template>