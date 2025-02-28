<script setup>
import GuestPageLayout from '@/Layouts/GuestPageLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import MunkavallaloLogin from '@/Components/MunkavallaloLogin.vue';
import MunkaltatoLogin from '@/Components/MunkaltatoLogin.vue';
import { computed, onMounted } from 'vue';


const props = defineProps({
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

const computeLabel = computed(() => {
    return form.logintype === 'munkavallalo' ? 'Munkavállaló' : 'Munkáltató';
});
</script>

<template>
    <GuestPageLayout>
        <Head title="Log in" />
        <div class="w-full relative h-screen flex flex-col">
            <v-container fluid class=" flex bg-gray-300">
            <v-row class="justify-center align-center">
                <v-col cols="12">
                    <div class="w-full text-center py-6">
                        <h1>Bejelentkezés</h1>
                    </div>
                </v-col>
            </v-row>
            </v-container>
            <div class="w-full md:w-1/2 mx-auto fill-height flex h-100">
                <v-container class="h-100">
                    <v-row class="justify-center h-100 align-center">
                        <v-col cols="12">
                            <v-card class="bg-gray-500 pa-8 !bg-gray-200 my-5 my-md-10" elevation="4" rounded="lg">
                                <v-switch :label="computeLabel" ripple v-model="form.logintype" base-color="#0ea5e9" color="green" false-value="munkavallalo" true-value="munkaltato"></v-switch>
                                <transition name="slide" mode="out-in">
                                  <template v-if="form.logintype === 'munkavallalo'" key="munkavallalo">
                                    <MunkavallaloLogin />
                                  </template>
                                  <template v-else key="munkaltato">
                                    <MunkaltatoLogin />
                                  </template>
                                </transition>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
        </div>

    </GuestPageLayout>
</template>
<style>
.slide-enter-active,
.slide-leave-active {
  transition: all .5s ease;
}

.slide-enter-from {
    transform: translateX(100%);
    opacity: 1;
}
.slide-leave-to {
    transform: translateX(-100%);
    
    opacity: 0;

}
</style>