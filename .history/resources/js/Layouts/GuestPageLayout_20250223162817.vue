<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavBar from '@/Components/NavBar.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0 dark:bg-gray-900"
    >
    <div>
            <NavBar></NavBar>
    </div>
        <div
            class=""
        >
            <slot />
        </div>
    </div>
</template>
