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

console.log(props.canLogin);

</script>

<template>

    <NavBar :canLogin="props.canLogin" :canRegister="props.canRegister"></NavBar>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0 dark:bg-gray-900"
    >
    
        <div
            class=""
        >
            <slot />
        </div>
    </div>
</template>
