<script setup>

import {Head, useForm} from '@inertiajs/vue3';

const form = useForm({
    username:'',
    email:'',
    name:'',
    password:'',
    cv:null,
});


const submit = () =>{
    form.post(route('register.store', {type:'munkavallalo'}),{
        onFinish: () => form.reset('password'),
    });
};

</script>


<template>

<div>
    <Head title="Munkavállaló - Regisztráció"></Head>
    <form @submit.prevent="submit" enctype="multipart/form-data">

        <div>
            <v-text-field label="Felhasználó név *" placeholder="peldafelhasznalo" v-model="form.username" type="text"
            :rules="rules.usernameRules"
            ></v-text-field>
        </div>
        <div>
            <v-text-field label="E-mail cím *" placeholder="pelda@pelda.hu" v-model="form.email"
             type="email" autocomplete="email"
            :rules="rules.emailRules"
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
            <v-file-input label="Önéletrajz" @change="form.cv = $event.target.files[0]"></v-file-input>
        </div>

        <div>
            <button type="submit">Regisztráció</button>
        </div>

    </form>
</div>

</template>