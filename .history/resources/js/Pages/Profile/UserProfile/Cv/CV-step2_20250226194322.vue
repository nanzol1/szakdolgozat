<script setup>
import { defineEmits, nextTick, onMounted, ref, watch } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';


const props = defineProps({
  prevStep:{
    type:Function,
  },
  formData{
    type:Object,
  }
});

const emit = defineEmits(['updateForm','nextStep']);
const quillInstances = ref([]);

const educations = ref([
  { school_name: '', school_address: '', degree: '', field_study: '', startdate: null,enddate: null, description: '' }
]);

const initializeQuill = (index) => {
  const editorElement = document.getElementById(`editors-${index}`);
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
  emit('updateForm', { educations: educations.value });
  
};

watch(educations.value,(newVal) => {
  if((props.formData.educations).length > 0){
    emit('updateForm', { educations: newVal });
  }
},{deep:true});

const handleSubmit = () => {
  emit('updateForm', { educations: educations.value });
  emit('nextStep');
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

          <InputLabel :for="`editors-${index}`" value="Leírás"></InputLabel>
          <div :id="`editors-${index}`"></div>
        </div>
      </template>
      <button type="button" @click="addEducation">Add more</button>
      <button
          @click="prevStep"
      >
          Previous
      </button>
      <button type="submit">Next</button>
    </form>
  </div>
</template>