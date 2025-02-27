<script setup>
defineProps({
    formData:{
        type:Object,
    }
});
</script>

<template>
  <div class="elevation-4">
    {{ formData.firstname }}
  </div>
</template>