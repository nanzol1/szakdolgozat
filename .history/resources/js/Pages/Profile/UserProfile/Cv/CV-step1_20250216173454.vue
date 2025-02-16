<script setup>
import { reactive, defineEmits, ref } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

const emit = defineEmits(['updateForm']);

const skills = ref([
  { name:'' },
]);

const form = useForm({
  skills: skills.value,
});

const addSkill = () => {
  skills.value.push({name:''});
};

const handleSubmit = () => {
  emit('updateForm', {skills: skills.value});
};
</script>
<template>
  <div>
    {{ form }}
    <form @submit.prevent="handleSubmit">
      <div v-for="(skill, index) in skills">
        <InputLabel for="name" value="Skill"></InputLabel>
        <TextInput id="name" type="text" v-model="skill.name" autocomplete="name"></TextInput>
      </div>
      <button type="button" @click="addSkill">Add more</button>
      <button type="submit">Next</button>
    </form>
  </div>
</template>
  