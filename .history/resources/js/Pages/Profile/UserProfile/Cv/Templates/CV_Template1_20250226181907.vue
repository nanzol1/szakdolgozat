<script setup>
defineProps({
    formData:{
        type:Object,
    }
});
</script>

<template>
  <div>
    {{ formData }}
  </div>
</template>