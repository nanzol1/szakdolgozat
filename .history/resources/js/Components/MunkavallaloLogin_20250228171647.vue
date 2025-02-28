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
</script>

<template>

    <Head title="Munkavállaló"></Head>

    <v-container>
        <v-row>
            <v-col cols="12">
                <v-form @submit.prevent="submit">    
                    <div>
                        <v-text-field label="E-mail cím" v-model="form.email" type="email"
                        ></v-text-field>
                    </div>
                    <div>
                        <label for="password">Jelszó</label>
                        <input type="password" v-model="form.password" class="block">
                    </div>
                    <div>
                        <button type="submit">Bejelentkezés</button>
                    </div>
                </v-form>
            </v-col>
        </v-row>
    </v-container>

</template>