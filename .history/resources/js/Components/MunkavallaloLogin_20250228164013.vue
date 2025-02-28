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
                
            </v-col>
        </v-row>
    </v-container>

</template>