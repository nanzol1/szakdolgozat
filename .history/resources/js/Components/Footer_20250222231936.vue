<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useAlertStore } from '@/store';

const alertStore = useAlertStore();
const { visible, message, type, closeAlert } = alertStore;

const page = usePage();

</script>
<template>
    <footer class="w-full h-12 bg-black">
        FOOTER
    </footer>
    <v-alert v-if="visible"
      color="success"
      icon="$success"
      title="Alert title"
      text="Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, ratione debitis quis est labore voluptatibus..."
    ></v-alert>
</template>