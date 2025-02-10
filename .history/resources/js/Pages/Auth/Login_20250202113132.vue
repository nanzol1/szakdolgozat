<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

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

        <div>
            <label for="munkavallalo">Munkavállaló</label>
            <input type="radio" v-model="form.logintype" value="munkavallalo" id="munkavallalo">

            <label for="munkaltato">Munkáltató</label>
            <input type="radio" v-model="form.logintype" value="munkaltato" id="munkaltato">
        </div>

        <div v-if="form.logintype === 'munkavallalo'">
            <MunkavallaloLogin></MunkavallaloLogin> 
        </div>
        <div v-if="form.logintype === 'munkaltato'">
            <MunkaltatoLogin></MunkaltatoLogin> 
        </div>

    </GuestLayout>
</template>
