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
    logintype: 'munkavallalo',
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

    </GuestLayout>
</template>
