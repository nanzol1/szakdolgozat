<script setup>
import { defineEmits, nextTick, onMounted, ref, watch } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

const emit = defineEmits(['updateForm']);
const quillInstances = ref([]);

const skills = ref([
  { title: '', employer: '', location: '', startdate: null, enddate: null, description: '' }
]);

const form = useForm({
  skills: skills.value,
});

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

const addSkill = () => {
  const newIndex = skills.value.length;
  skills.value.push({ title: '', employer: '', location: '', startdate: null, enddate: null, description: '' });

  nextTick(() => {
    initializeQuill(newIndex);
  });
};

const handleSubmit = () => {
  emit('updateForm', { skills: skills.value });
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
asd
      </template>
      <button type="button" @click="addSkill">Add more</button>
      <button type="submit" @click="nextStep">Next</button>
    </form>
  </div>
</template>