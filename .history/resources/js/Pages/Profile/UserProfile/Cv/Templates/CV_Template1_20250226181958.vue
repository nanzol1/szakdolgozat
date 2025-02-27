<script setup>
defineProps({
    formData:{
        type:Object,
    }
});
</script>

<template>
  <div class="elevation-4 h-screen">
    {{ formData.firstname }}
  </div>
</template>