<script setup>
import { defineEmits, nextTick, onMounted, ref, watch } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

const emit = defineEmits(['updateForm']);
const quillInstances = ref([]);

const educations = ref([
  { school_name: '', school_address: '', degree: '', field_study: '', startdate: null,enddate: null, description: '' }
]);

const initializeQuill = (index) => {
  const editorElement = document.getElementById(`editor-${index}`);
  if (editorElement) {
    const quill = new Quill(editorElement, {
      theme: 'snow'
    });

    if (educations.value[index].description) {
      quill.root.innerHTML = educations.value[index].description;
    }

    quill.on('text-change', () => {
        educations.value[index].description = quill.root.innerHTML;
    });

    quillInstances.value[index] = quill;
  }
};

const addEducation = () => {
  const newIndex = educations.value.length;
  educations.value.push({ school_name: '', school_address: '', degree: '', field_study: '', startdate: null,enddate: null, description: '' });

  nextTick(() => {
    initializeQuill(newIndex);
  });
};

const handleSubmit = () => {
  emit('updateForm', { educations: skills.value });
};

onMounted(() => {
    educations.value.forEach((_, index) => {
    initializeQuill(index);
  });
});
</script>

<template>
  <div>
    <form @submit.prevent="handleSubmit">
      <template v-for="(educ, index) in educations" :key="index">
        <div>
          <InputLabel for="school_name" value="Iskola megnevezése"></InputLabel>
          <TextInput id="school_name" type="text" v-model="educ.school_name" autocomplete="name"></TextInput>

          <InputLabel for="school_address" value="Iskola címe"></InputLabel>
          <TextInput id="school_address" type="text" v-model="educ.school_address" autocomplete="address"></TextInput>

          <InputLabel for="degree" value="Végzettség"></InputLabel>
          <select v-model="educ.degree" name="degree" id="degree" class="text-black">
                <option value="0">Végzettség típusa</option>
                <option value="Általános-Iskola">Általános Iskola</option>
                <option value="Szakiskola-Szakmunkás-képző">Szakiskola/Szakmunkás képző</option>
                <option value="Középiskola">Középiskola</option>
                <option value="Felsőoktatási szakképesítés">Felsőoktatási szakképesítés</option>
                <option value="Főiskola">Főiskola</option>
                <option value="Egyetem">Egyetem</option>
                <option value="Állami rendszerű egyéb képzés">Állami rendszerű egyéb képzés</option>
                <option value="Állami rendszerű, nem diplomás képzés">Állami rendszerű, nem diplomás képzés</option>
                <option value="Coaching">Coaching</option>
                <option value="OKJ-rendszerű tanfolyam">OKJ-rendszerű tanfolyam</option>
                <option value="Tanfolyam">Tanfolyam</option>
                <option value="Tréning">Tréning</option>
            </select>

          <InputLabel for="startdate" value="Kezdési idő"></InputLabel>
          <input type="date" v-model="educ.startdate" id="startdate">

          <InputLabel for="enddate" value="Befejezési idő"></InputLabel>
          <input type="date" v-model="educ.enddate" id="enddate">

          <InputLabel :for="`editor-${index}`" value="Leírás"></InputLabel>
          <div :id="`editor-${index}`" class="dark:text-white"></div>
        </div>
      </template>
      <button type="button" @click="addEducation">Add more</button>
    </form>
  </div>
</template>