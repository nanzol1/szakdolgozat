<script setup>
import { defineEmits, onMounted, ref, watch } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

const emit = defineEmits(['updateForm']);
const quillInstance = ref([]);

const skills = ref([
  {title:'',employer:'',location:'',startdate:null,enddate:null,description:''},
]);

const form = useForm({
  skills: skills.value,
});

const addSkill = () => {
  skills.value.push({title:'',employer:'',location:'',startdate:null,enddate:null,description:''});
};

const handleSubmit = () => {
  emit('updateForm', {skills: skills.value});
};

const initializeQuill = () => {
  skills.value.forEach((skill,index) => {
    const editorElement = document.getElementById(`editor-${index}`);

    if(editorElement){
      const quill = new Quill(editorElement, {
        theme: 'snow'
      });
      if(skill.description){
        quill.root.innerHTML = skill.description;
      }

      quill.on('text-change', () => {
        skills.value[index].description = quill.root.innerHTML;
      });

      quillInstance.value.push(quill);
    }
  });
};

onMounted(() => {

});
</script>
<template>
  <div>
    {{ form }}
    <form @submit.prevent="handleSubmit">
      <template v-for="(skill, index) in skills" :key="index">
        <div>
          <InputLabel for="jobtitle" value="Munka megnevezése"></InputLabel>
          <TextInput id="jobtitle" type="text" v-model="skill.title" autocomplete="title"></TextInput>

          <InputLabel for="employer" value="Munkáltató"></InputLabel>
          <TextInput id="employer" type="text" v-model="skill.employer" autocomplete="employer"></TextInput>
          
          <InputLabel for="location" value="Elhelyezkedés"></InputLabel>
          <TextInput id="location" type="text" v-model="skill.location" autocomplete="location"></TextInput> 

          <InputLabel for="startdate" value="Kezdési idő"></InputLabel>
          <input type="date" v-model="skill.startdate" id="startdate">

          <InputLabel for="enddate" value="Befejezési idő"></InputLabel>
          <input type="date" v-model="skill.enddate" id="enddate">

          <InputLabel for="editor" value="Munka leírás"></InputLabel>
          <div :id="`editor-${index}`"></div>
        </div>
      </template>
      <button type="button" @click="addSkill">Add more</button>
      <button type="submit">Next</button>
    </form>
  </div>
</template>
  