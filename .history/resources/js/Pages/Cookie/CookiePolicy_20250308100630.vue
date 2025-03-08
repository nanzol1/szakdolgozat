<script setup>
import GuestPageLayout from '@/Layouts/GuestPageLayout.vue';

</script>
<template>
    <GuestPageLayout>
        <div class="flex w-full h-100 flex-col">
            <v-container fluid class="flex bg-gray-300">
                <v-row class="justify-center align-center">
                    <v-col cols="12">
                        <div class="w-full text-center py-6">
                            <div class="text-h3">Sütik</div>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
            <div class="w-full md:w-1/2 mx-auto fill-height">
                <v-container class="fill-height">
                    <v-row>
                        <v-col cols="12">
                            <v-card class="bg-gray-500 pa-8 !bg-gray-200 my-5 my-md-10" elevation="4" rounded="lg">
                                <v-switch :label="computeLabel" ripple v-model="type" base-color="#0ea5e9" color="green"  false-value="munkavallalo" true-value="munkaltato"></v-switch>
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