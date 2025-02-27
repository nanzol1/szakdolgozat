<script setup>
defineProps({
    formData:{
        type:Object,
    }
});
</script>

<template>
  <div class="elevation-2">
    {{ formData.firstname }}
  </div>
</template>