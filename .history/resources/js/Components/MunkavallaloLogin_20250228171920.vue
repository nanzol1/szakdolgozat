<script setup>

import {Head, useForm} from '@inertiajs/vue3';
import { rules } from 'eslint-plugin-vue';

const form = useForm({
    email:'',
    password:'',
});

const submit = () =>{
    form.post(route('login',{logintype: 'munkavallalo'}),{
        onFinish: () => form.reset('password'),
    });
};

const rules = [
    emailRule:[
    
    ],
];
</script>

<template>

    <Head title="Munkavállaló"></Head>

    <v-container>
        <v-row>
            <v-col cols="12">
                <v-form @submit.prevent="submit">    
                    <div>
                        <v-text-field label="E-mail cím" hint="A belépéshez írd be az e-mail címed" v-model="form.email" type="email"
                        :rules="rules.emailRule"
                        ></v-text-field>
                    </div>
                    <div>
                        <v-text-field label="Jelszó" hint="A belépéshez írd be a jelszavad" v-model="form.password" type="password"
                        :rules="rules.passwordRule"
                        ></v-text-field>
                    </div>
                    <div>
                        <button type="submit">Bejelentkezés</button>
                    </div>
                </v-form>
            </v-col>
        </v-row>
    </v-container>

</template>