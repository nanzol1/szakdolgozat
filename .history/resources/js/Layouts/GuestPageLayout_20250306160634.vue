<script setup>
import NavBar from '@/Components/NavBar.vue';
import Footer from '@/Components/Footer.vue';
</script>

<template>
    <NavBar></NavBar>
    <main
        class="flex flex-1 bg-gray-100 sm:justify-center sm:pt-0 dark:bg-gray-900 asd"
    >
        <slot />
    </main>
    <Footer></Footer>
</template>
