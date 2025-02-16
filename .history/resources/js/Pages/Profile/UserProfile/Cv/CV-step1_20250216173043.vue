<script setup>
import { reactive, defineEmits, ref } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

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
        <InputLabel for="name" value="Skill"></InputLabel>
        <TextInput id="name" type="text" v-model="form.name" autocomplete="name"></TextInput>
      </div>
      <button type="button" @click="addSkill">Add more</button>
      <button type="submit">Next</button>
    </form>
  </div>
</template>
  