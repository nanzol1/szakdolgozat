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
        <div class="flex md:gap-5 flex-wrap gap-2 mt-3">
            <v-btn class="hover:bg-sky-500 hover:text-gray-50">Regisztráció</v-btn>
        </div>
    </v-form>
    <form @submit.prevent="submit" enctype="multipart/form-data">

        <div>
            <v-text-field label="Felhasználó név" placeholder="peldafelhasznalo" v-model="form.username" type="text"
            :rules="rules.companyRules"
            ></v-text-field>
            <label for="username">Felhasználó név</label>
            <input type="text" placeholder="Felhasználó név" v-model="form.username">
        </div>
        <div>
            <label for="email">E-mail cím</label>
            <input type="email" placeholder="E-mail cím" v-model="form.email">
        </div>
        <div>
            <label for="name">Teljes név</label>
            <input type="text" placeholder="Teljes név" v-model="form.name">
        </div>
        <div>
            <label for="password">Jelszó</label>
            <input type="password" placeholder="Jelszó" v-model="form.password">
        </div>
        <div>
            <label for="cv">CV</label>
            <input type="file" @change="form.cv = $event.target.files[0]" class="block">
        </div>

        <div>
            <button type="submit">Regisztráció</button>
        </div>

    </form>
</div>

</template>