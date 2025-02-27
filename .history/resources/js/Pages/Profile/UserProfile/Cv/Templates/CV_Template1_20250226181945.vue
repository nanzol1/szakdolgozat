<script setup>
defineProps({
    formData:{
        type:Object,
    }
});
</script>

<template>
  <div class="">
    {{ formData.firstname }}
  </div>
</template>