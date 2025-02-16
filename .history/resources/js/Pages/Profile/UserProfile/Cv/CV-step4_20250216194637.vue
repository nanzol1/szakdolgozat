<script setup>
import { defineEmits, onMounted, ref } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

const emit = defineEmits(['updateForm','submitForm']);

const more_descs = ref([
  { more_desc:'' }
]);

const handleSubmit = () => {
  emit('updateForm', { more_desc: more_descs.value });
  emit('submitForm');
};

onMounted(() => {
    const quill = new Quill(editorElement, {
      theme: 'snow'
    });
    quill.on('text-change', () => {
        more_desc.value.more_desc = quill.root.innerHTML;
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
            <input type="radio" v-model="skill.skill_level" :value="0">
            <input type="radio" v-model="skill.skill_level" :value="1">
            <input type="radio" v-model="skill.skill_level" :value="2">
            <input type="radio" v-model="skill.skill_level" :value="3">

        </div>
      </template>
      <button type="submit">Next</button>
    </form>
  </div>
</template>