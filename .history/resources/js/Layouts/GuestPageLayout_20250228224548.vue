<script setup>
import NavBar from '@/Components/NavBar.vue';
import Footer from '@/Components/Footer.vue';
</script>

<template>
    <NavBar></NavBar>
    <div
        class="flex min-h-screen flex-1 bg-gray-100 sm:justify-center sm:pt-0 dark:bg-gray-900"
    >
        <slot />
    </div>
    <Footer></Footer>
</template>
