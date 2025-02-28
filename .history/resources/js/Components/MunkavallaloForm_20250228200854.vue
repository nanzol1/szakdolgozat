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