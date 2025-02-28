<script setup>

import {Head, useForm} from '@inertiajs/vue3';

const form = useForm({
    email:'',
    password:'',
});

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
            :rules="rules.emailRules"
            ></v-text-field>
        </div>
        <div>
            <v-text-field label="Jelszó" hint="A belépéshez írd be a jelszavad" v-model="form.password" type="password"
            :append-icon="false ? 'mdi-eye' : 'mdi-eye-off'"
            ></v-text-field>
        </div>
        <div>
            <button type="submit">Bejelentkezés</button>
        </div>
    </v-form>

</template>