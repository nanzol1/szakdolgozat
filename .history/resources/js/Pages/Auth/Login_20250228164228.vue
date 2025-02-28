<script setup>
import GuestPageLayout from '@/Layouts/GuestPageLayout.vue';
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
    <GuestPageLayout>
        <Head title="Log in" />

        <div class="w-full md:w-1/2">
            <v-container class="fill-height">
                <v-row>
                    <v-col cols="12">
                        <div class="w-fit mx-auto">
                            <div>
                            <v-switch false-label="Munkavállaló" true-label="Munkáltató" v-model="form.logintype" base-color="black" false-value="munkaltato" true-value="munkavallalo" inset></v-switch>
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
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </div>

    </GuestPageLayout>
</template>
