<script setup>
import MunkaltatoForm from '@/Components/MunkaltatoForm.vue';
import MunkavallaloForm from '@/Components/MunkavallaloForm.vue';
import GuestPageLayout from '@/Layouts/GuestPageLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { defineComponent } from 'vue';


const props = defineProps({
    type: String,
});

const components = defineComponent({
    MunkaltatoForm,
    MunkavallaloForm,
});
</script>

<template>
    <GuestPageLayout class="dark:text-white"> 
        <Head title="Regisztráció" />
        <div class="w-full h-screen flex flex-col">
            <v-container fluid class=" flex bg-gray-300">
            <v-row class="justify-center align-center">
                <v-col cols="12">
                    <div class="w-full text-center py-6">
                        <h1>Bejelentkezés</h1>
                    </div>
                </v-col>
            </v-row>
            </v-container>
            <div class="w-full md:w-1/2 mx-auto fill-height">
                <v-container class="fill-height">
                    <v-row>
                        <v-col cols="12">
                            <div v-if="type !== 'munkavallalo' && type !== 'munkaltato'">
                                <Link class="block" :href="route('register',{type: 'munkavallalo'})">Munkavállaló</Link>
                                <Link class="block" :href="route('register',{type: 'munkaltato'})">Munkáltató</Link>
                            </div>
                        
                            <div v-if="type === 'munkavallalo'">
                                <MunkavallaloForm></MunkavallaloForm>
                            </div>
                            <div v-if="type === 'munkaltato'">
                                <MunkaltatoForm></MunkaltatoForm>
                            </div>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
        </div>
    </GuestPageLayout>

</template>
