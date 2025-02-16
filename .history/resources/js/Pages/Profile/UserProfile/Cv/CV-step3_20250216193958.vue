<script setup>
import { defineEmits, nextTick, onMounted, ref, watch } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

const emit = defineEmits(['updateForm','nextStep']);
const quillInstances = ref([]);

const skills = ref([
  { skill_name: '', skill_level: 0}
]);

const initializeQuill = (index) => {
  const editorElement = document.getElementById(`editor-${index}`);
  if (editorElement) {
    const quill = new Quill(editorElement, {
      theme: 'snow'
    });

    if (skills.value[index].description) {
      quill.root.innerHTML = skills.value[index].description;
    }

    quill.on('text-change', () => {
        skills.value[index].description = quill.root.innerHTML;
    });

    quillInstances.value[index] = quill;
  }
};

const addSkills = () => {
  const newIndex = skills.value.length;
  skills.value.push({ skill_name: '', skill_level: [0,1,2,3]});

  nextTick(() => {
    initializeQuill(newIndex);
  });
};

const handleSubmit = () => {
  emit('updateForm', { skills: skills.value });
  emit('nextStep');
};

onMounted(() => {
    skills.value.forEach((_, index) => {
    initializeQuill(index);
  });
});
</script>

<template>
  <div>
    <form @submit.prevent="handleSubmit">
      <template v-for="(skill, index) in skills" :key="index">
        <div>
          <InputLabel for="skill_name" value="Képesség"></InputLabel>
          <TextInput id="skill_name" type="text" v-model="skill.skill_name" autocomplete="skill_name"></TextInput>

          <InputLabel for="skill_level" value="Képesség szintje"></InputLabel>
            <input type="radio" v-model="skill.skill_level" :value="skill.skill_level">

        </div>
      </template>
      <button type="button" @click="addEducation">Add more</button>
      <button type="submit">Next</button>
    </form>
  </div>
</template>