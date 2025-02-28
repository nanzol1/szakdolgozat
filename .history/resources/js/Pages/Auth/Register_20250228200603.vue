<script setup>
import MunkaltatoForm from '@/Components/MunkaltatoForm.vue';
import MunkavallaloForm from '@/Components/MunkavallaloForm.vue';
import GuestPageLayout from '@/Layouts/GuestPageLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed, defineComponent, ref } from 'vue';


const props = defineProps({
    type: String,
});

const type = ref(props.type || '');

const components = defineComponent({
    MunkaltatoForm,
    MunkavallaloForm,
});

const computeLabel = computed(() => {
    return type === 'munkavallalo' ? 'Munkavállaló' : 'Munkáltató';
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
                        <h1>Regisztráció</h1>
                    </div>
                </v-col>
            </v-row>
            </v-container>
            <div class="w-full md:w-1/2 mx-auto fill-height">
                <v-container class="fill-height">
                    <v-row>
                        <v-col cols="12">
                            <v-card class="bg-gray-500 pa-8 !bg-gray-200" elevation="4" rounded="lg">
                                <v-switch :label="computeLabel" ripple v-model="type" base-color="#0ea5e9" color="green" false-value="munkavallalo" true-value="munkaltato"></v-switch>
                                <transition name="slide" mode="out-in">
                                  <template v-if="type === 'munkavallalo'" key="munkavallalo">
                                    <MunkavallaloForm />
                                  </template>
                                  <template v-else key="munkaltato">
                                    <MunkaltatoForm />
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