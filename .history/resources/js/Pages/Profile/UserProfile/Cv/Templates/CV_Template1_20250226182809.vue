<script setup>
defineProps({
    formData:{
        type:Object,
    }
});
</script>

<template>
  <div class="elevation-4 h-screen">
    <div class="bg-sky-500 h-screen w-60">
        <v-card class="!bg-sky-500 h-screen" elevation="0" rounded="0">
            {{ formData.firstname }}
        </v-card>
    </div>
  </div>
</template>