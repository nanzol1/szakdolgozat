<script setup>

import GuestPageLayout from '@/Layouts/GuestPageLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';

const form = useForm({
    email:'',
    password:'',
});

const submit = () =>{
    form.post(route('login',{logintype: 'munkaltato'}),{
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>

    <Head title="Munkavállaló"></Head>

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
        <div class="flex md:gap-5">
            <v-btn class="hover:bg-sky-500 hover:text-gray-50">Bejelentkezés</v-btn>
            <v-btn class="hover:bg-sky-500 hover:text-gray-50" @click="router.visit(route('register',{type:'munkavallalo'}))">Regisztráció</v-btn>
        </div>
    </v-form>
</template>