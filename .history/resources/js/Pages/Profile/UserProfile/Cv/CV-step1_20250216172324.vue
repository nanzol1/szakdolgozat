<template>
    <div>
      <form @submit.prevent="handleSubmit">
        <div>
          <InputLabel for="phone" value="Telefonszám"></InputLabel>
          <TextInput id="phone" type="text" v-model="formData.phone" autocomplete="phone"></TextInput>
        </div>
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