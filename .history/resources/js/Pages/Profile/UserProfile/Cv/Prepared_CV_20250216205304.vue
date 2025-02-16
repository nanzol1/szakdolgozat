<template>
    <div>
      <p>{{ message }}</p>
      <p>CV ID: {{ cv.id }}</p>
      <!-- További CV adatok megjelenítése -->
    </div>
  </template>
  
  <script setup>
  import { defineProps } from 'vue';
  
  const props = defineProps({
    message: String,
    cv: Object,
  });
  </script>