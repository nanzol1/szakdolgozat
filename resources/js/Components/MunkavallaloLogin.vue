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

    <form @submit.prevent="submit">
        <div>
            <label for="email">E-mail cím</label>
            <input type="email" v-model="form.email" class="block">
        </div>
        <div>
            <label for="password">Jelszó</label>
            <input type="password" v-model="form.password" class="block">
        </div>
        <div>
            <button type="submit">Bejelentkezés</button>
        </div>
    </form>

</template>