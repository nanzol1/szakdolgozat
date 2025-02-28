<script setup>
import GuestPageLayout from '@/Layouts/GuestPageLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import MunkavallaloLogin from '@/Components/MunkavallaloLogin.vue';
import MunkaltatoLogin from '@/Components/MunkaltatoLogin.vue';
import { computed, ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const switchLabel = ref('');

const form = useForm({
    logintype: 'munkavallalo',
});

const computeLabel = computed(() => {
    return form.logintype === 'munkavallalo' ? 'Munkavállaló' : 'Munkáltató';
});

</script>

<template>
    <GuestPageLayout>
        <Head title="Log in" />

        <div class="w-full content-center">
            <v-container fluid class=" flex bg-gray-300">
            <v-row class="justify-center align-center">
                <v-col cols="12">
                    <div class="w-full text-center py-6">
                        <h1>Bejelentkezés</h1>
                    </div>
                </v-col>
            </v-row>
            </v-container>
            <div class="w-full md:w-1/2 mx-auto">
                <v-container>
                    <v-row class="justify-center">
                        <v-col cols="12" lg="10" md="10">
                            <v-card class="bg-gray-500 pa-8 !bg-gray-200" elevation="4" rounded="lg">
                                <v-switch :label="computeLabel" ripple v-model="form.logintype" base-color="#0ea5e9" color="green" false-value="munkavallalo" true-value="munkaltato"></v-switch>
                                <template v-if="form.logintype === 'munkavallalo'">
                                        <MunkavallaloLogin></MunkavallaloLogin> 
                                </template>                            
                                <template v-else>
                                    <MunkaltatoLogin></MunkaltatoLogin> 
                                </template>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
        </div>

    </GuestPageLayout>
</template>
