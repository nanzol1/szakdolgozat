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
        <div class="w-full relative h-100 flex flex-col">
            <v-container fluid class="pa-0  shadow-md relative hero_section">
                <v-img position="center" src='storage/images/reg_log.jpg' class="!z-10" aspect-ratio="16/9" absolute cover width="100%" height="100%"></v-img>
                <v-container>
                    <v-row>
                        <v-col>
                            <div class="relative !z-20 flex flex-col items-center py-8 h-auto text-stone-950 w-full">
                                <div class="flex flex-col max-w-7xl items-center">
                                    <div class="mx-auto text-h4">
                                        Bejelentkezés / <v-fade-transition>{{ computeLabel }}</v-fade-transition>
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
  transition: all .2s ease;
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