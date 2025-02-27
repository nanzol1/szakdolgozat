<script setup>
defineProps({
    formData:{
        type:Object,
    }
});
</script>

<template>
  <div class="elevation">
    {{ formData.firstname }}
  </div>
</template>