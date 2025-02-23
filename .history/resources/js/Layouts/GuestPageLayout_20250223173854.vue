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
        class=""
    >
        <v-container>
            <v-row>
                <slot />
            </v-row>
        </v-container>
    </div>
    <Footer></Footer>
</template>
