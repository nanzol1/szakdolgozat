<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const alertStore = useAlertStore();
const { visible, message, type, closeAlert } = alertStore;

const page = usePage();

console.log(page.props.flash);
</script>
<template>
    <footer class="w-full h-12 bg-black">
        FOOTER
    </footer>
    <v-alert v-if="page.props.flash.message"
      color="success"
      icon="$success"
      title="Alert title"
      text="Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, ratione debitis quis est labore voluptatibus..."
    ></v-alert>
</template>