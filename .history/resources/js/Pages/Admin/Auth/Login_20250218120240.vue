<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import MunkavallaloLogin from '@/Components/MunkavallaloLogin.vue';
import MunkaltatoLogin from '@/Components/MunkaltatoLogin.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email:'',
    username:''
});

</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

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

    </GuestLayout>
</template>
