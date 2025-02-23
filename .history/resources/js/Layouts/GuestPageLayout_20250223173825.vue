<script setup>
import NavBar from '@/Components/NavBar.vue';
import Footer from '@/Components/Footer.vue';

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});


</script>

<template>
    <NavBar :canLogin="canLogin" :canRegister="canRegister"></NavBar>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0 dark:bg-gray-900"
    >
        <v-container>
            <v-row class="break-words">
                <slot />
            </v-row>
        </v-container>
    </div>
    <Footer></Footer>
</template>
