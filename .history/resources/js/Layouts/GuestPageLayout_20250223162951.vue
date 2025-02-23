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
    featured_jobs:{
        type:Object,
    },
    jobs:{
        type:Object,
    },
    filters:{
        type:Array,
    },
    categories:{
        type:Object,
    },
    min_spayment:{
        type:Object,
    },
    max_spayment:{
        type:Object,
    },
});

</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0 dark:bg-gray-900"
    >
    <NavBar :canLogin="canLogin" :canRegister="canRegister"></NavBar>
        <div
            class=""
        >
            <slot />
        </div>
    </div>
</template>
