<script setup>
import { ref } from 'vue';

const isAlert = ref(false);

</script>
<template>
    <footer class="w-full h-12 bg-black">
        FOOTER
    </footer>
    <v-alert v-if="isAlert"
      color="success"
      icon="$success"
      title="Alert title"
      text="Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, ratione debitis quis est labore voluptatibus..."
    ></v-alert>
</template>