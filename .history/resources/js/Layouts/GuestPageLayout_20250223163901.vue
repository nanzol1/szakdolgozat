<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavBar from '@/Components/NavBar.vue';
import { Link } from '@inertiajs/vue3';

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
    <v-container>
        <v-row>
            <v-col>
                <slot />
            </v-col>
        </v-row>
    </v-container>
</template>
