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
                            Sütik                       
                        </v-col>
                    </v-row>
                </v-container>
            </div>
        </div>
    </GuestPageLayout>
</template>