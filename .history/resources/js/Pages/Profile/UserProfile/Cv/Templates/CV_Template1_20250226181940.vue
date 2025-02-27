<script setup>
defineProps({
    formData:{
        type:Object,
    }
});
</script>

<template>
  <div >
    {{ formData.firstname }}
  </div>
</template>