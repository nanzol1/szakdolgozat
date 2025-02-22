<script setup>
import { useAlertStore } from '@/store';
import { usePage } from '@inertiajs/vue3';
import { storeToRefs } from 'pinia';
import { watch } from 'vue';

const alertStore = useAlertStore();
const { visible, message, type, closeAlert } = storeToRefs(alertStore);


</script>
<template>
    <v-alert v-if="alertStore.visible"
      :color="alertStore.type"
      :icon="'$'+alertStore.type"
      :title="alertStore.message"
      text="Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, ratione debitis quis est labore voluptatibus..."
    ></v-alert>
    <footer class="w-full h-12 bg-black">
        FOOTER
    </footer>
</template>