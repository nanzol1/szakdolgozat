<script setup>
defineProps({
    formData:{
        type:Object,
    }
});
</script>

<template>
  <div class="elevation-3">
    {{ formData.firstname }}
  </div>
</template>