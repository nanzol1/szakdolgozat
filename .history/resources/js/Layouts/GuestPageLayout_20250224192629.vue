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
    flexCol:{
        type:String,
        default:'',
    }
});


</script>

<template>
    <NavBar :canLogin="canLogin" :canRegister="canRegister"></NavBar>
    <div
        :class="['flex min-h-screen bg-gray-100 pt-6 sm:justify-center sm:pt-0 dark:bg-gray-900',flexCol]"
    >
        <slot />
    </div>
    <Footer></Footer>
</template>
