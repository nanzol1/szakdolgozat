<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import MunkavallaloLogin from '@/Components/MunkavallaloLogin.vue';
import MunkaltatoLogin from '@/Components/MunkaltatoLogin.vue';
import GuestPageLayout from '@/Layouts/GuestPageLayout.vue';

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
    password:'',
});

const submit = () =>{
    form.post(route('admin.login',{logintype: 'admin'}),{
        onSuccess: () => form.reset('password'),
    });
};

</script>

<template>
    <GuestPageLayout>
        <Head title="Bejelentkezés" />
        <div class="w-full relative h-100 flex flex-col">
            <v-container fluid class="pa-0  shadow-md relative hero_section">
                <v-img position="center" src='storage/images/reg_log.jpg' class="!z-10" aspect-ratio="16/9" absolute cover width="100%" height="100%"></v-img>
                <v-container>
                    <v-row>
                        <v-col>
                            <div class="relative !z-20 flex flex-col items-center py-8 h-auto text-stone-950 w-full">
                                <div class="flex flex-col max-w-7xl items-center">
                                    <div class="mx-auto text-h4">
                                        Bejelentkezés / {{computeLabel}}
                                    </div>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </v-container>
            <div class="w-full md:w-1/2 mx-auto fill-height">
                <v-container class="h-100">
                    <v-row class="justify-center h-100 align-center">
                        <v-col cols="12">
                            <v-card class="bg-gray-500 pa-8 !bg-gray-200 my-5 my-md-10" elevation="4" rounded="lg">
                                <transition name="slide" mode="out-in">
                                    <v-form @submit.prevent="validate" ref="formValidator">    
                                        <div>
                                            <v-text-field label="E-mail cím" v-model="form.email" type="email"
                                            :rules="rules.emailRules"
                                            ></v-text-field>
                                        </div>
                                        <div>
                                            <v-text-field label="Jelszó" v-model="form.password" :type="show1 ? 'text' : 'password'"
                                            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'" @click:append="show1 = !show1"
                                            ></v-text-field>
                                        </div>
                                        <div class="flex md:gap-5 gap-2 flex-wrap">
                                            <v-btn class="!bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105 !text-gray-50" type="submit">Bejelentkezés</v-btn>
                                        </div>
                                        <div class="mt-3 flex flex-col gap-2">
                                            <div>
                                                Még nincs fiókja?
                                            </div>
                                            <div>
                                                <v-btn class="!bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105 !text-gray-50" @click="router.visit(route('register',{type:'munkavallalo'}))">Regisztráció</v-btn>
                                            </div>
                                        </div>
                                        <div class="mt-3 flex flex-col gap-2">
                                            <div>Elfelejtettem a jelszavam!</div> 
                                            <div><v-btn class="!bg-[#A67C52] hover:!bg-[#3A2618] hover:scale-105 !text-gray-50" @click="router.visit(route('password.request'))">Elfelejtett jelszó</v-btn></div>
                                        </div>
                                    </v-form>
                                </transition>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
        </div>

    </GuestPageLayout>
</template>
