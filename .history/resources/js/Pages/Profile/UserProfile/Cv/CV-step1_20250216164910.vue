<template>
    <div>
      <form @submit.prevent="handleSubmit">
        <input v-model="form.name" placeholder="Name" />
        <input v-model="form.email" placeholder="Email" />
        <button type="submit">Next</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { reactive, defineEmits } from 'vue';
  
  const emit = defineEmits(['updateForm']);
  
  const form = reactive({
    name: '',
    email: '',
  });
  
  const handleSubmit = () => {
    emit('updateForm', form);
  };
  </script>