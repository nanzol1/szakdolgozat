<script setup>
import { reactive, defineEmits, ref } from 'vue';

const emit = defineEmits(['updateForm']);

const form = ref([
  { name:'' },
]);

const addSkill = () => {
  form.value.push({name:''});
};

const handleSubmit = () => {
  emit('updateForm', form);
};
</script>
<template>
  <div>
    <form @submit.prevent="handleSubmit">
      <div>
        <InputLabel for="phone" value="Telefonszám"></InputLabel>
        <TextInput id="phone" type="text" v-model="formData.phone" autocomplete="phone"></TextInput>
      </div>
      <button type="button" @click="addSkill">Add more</button>
      <button type="submit">Next</button>
    </form>
  </div>
</template>
  