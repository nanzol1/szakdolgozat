<script setup>

import {Head, router, useForm} from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
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

        
        <v-form @submit.prevent="validate" ref="formValidator">    
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
                <v-btn class="!bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105 !text-gray-50" type="submit">Bejelentkezés</v-btn>
            </div>
            <div class="mt-3 flex flex-col gap-2">
                <div>
                    Még nincs fiókja?
                </div>
                <div>
                    <v-btn class="!bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105 !text-gray-50" @click="router.visit(route('register',{type:'munkaltato'}))">Regisztráció</v-btn>
                </div>
            </div>
            <div class="mt-3 flex flex-col gap-2">
                <div>Elfelejtettem a jelszavam!</div> 
                <div><v-btn class="!bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105 !text-gray-50" @click="router.visit(route('password.request'))">Elfelejtett jelszó</v-btn></div>
            </div>
        </v-form>
    </div>
</template>