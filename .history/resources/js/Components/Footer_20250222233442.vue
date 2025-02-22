<script setup>
import { useAlertStore } from '@/store';
import { usePage } from '@inertiajs/vue3';
import { watch } from 'vue';

const alertStore = useAlertStore();
const { visible, message, type, closeAlert } = alertStore;

watch(visible, (newVisible) => {
  console.log('Alert látható:', newVisible);
});
</script>
<template>
    <div>
        <v-alert v-if="visible"
      color="success"
      icon="$success"
      title="Alert title"
      text="Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, ratione debitis quis est labore voluptatibus..."
    ></v-alert>
    </div>
    <footer class="w-full h-12 bg-black">
        FOOTER
    </footer>
</template>