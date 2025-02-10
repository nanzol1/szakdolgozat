<script setup>

import {Head, useForm} from '@inertiajs/vue3';

const form = useForm({
    company_name:'',
    email:'',
    password:'',
});


const submit = () =>{
    form.post(route('register.store',{type: 'munkaltato'}),{
        onFinish: () => form.reset('password'),
    });
};

</script>


<template>

    <Head title="Munkáltató regisztráció"></Head>
    <form @submit.prevent="submit">

        <div>
            <label for="username">Cég név</label>
            <input type="text" placeholder="Cég név" v-model="form.company_name">
        </div>
        <div>
            <label for="email">E-mail cím</label>
            <input type="email" placeholder="E-mail cím" v-model="form.email">
        </div>
        <div>
            <label for="password">Jelszó</label>
            <input type="password" placeholder="Jelszó" v-model="form.password">
        </div>

        <div>
            <button type="submit">Regisztráció</button>
        </div>

    </form>


</template>