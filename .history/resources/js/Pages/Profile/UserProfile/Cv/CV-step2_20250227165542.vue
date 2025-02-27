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
  formData:{
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
  emit('updateForm', { educations: newVal });
},{deep:true});

const handleSubmit = () => {
  emit('updateForm', { educations: educations.value });
  emit('nextStep');
};

onMounted(() => {
  if((props.formData.educations).length > 0){
    educations.value = Object.values(props.formData.educations).map(objs => objs);
    nextTick(() => {
      educations.value.forEach((_, index) => {
        initializeQuill(index);
      });
    });
  }else{
    educations.value.forEach((_, index) => {
        initializeQuill(index);
    });
  }
});
</script>

<template>
  <div>
    <v-form @submit.prevent="handleSubmit">
      <template v-for="(educ, index) in educations" :key="index">
        <v-container>
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="eds.school_name"
                label="Iskola megnevezése"
                placeholder="Példa iskola"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="eds.school_address"
                label="Iskola címe"
                placeholder="Példa utca 44."
              ></v-text-field>
            </v-col>
            
            <v-col cols="12">
              <v-text-field
                v-model="exp.location"
                label="Elhelyezkedés"
                placeholder="Debrecen"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <n-date-picker v-model:value="exp.startdate" type="month" clearable size="large"/>
            </v-col>
            <v-col cols="12" md="6">
              <n-date-picker v-model:value="exp.enddate" type="month" clearable size="large"/>
            </v-col>
            <v-col cols="12" class="mb-sm-15">
              <InputLabel :for="`editor-${index}`" value="Munka leírás"></InputLabel>
              <div :id="`editor-${index}`"></div>
            </v-col>
            <v-col cols="12">
              <v-btn type="button" @click="addExperience"  class="hover:bg-sky-500 hover:text-gray-50">Hozzáadás</v-btn>
            </v-col>
            <v-col cols="12" md="6" class="flex gap-4 flex-sm-wrap">
              <v-btn @click="prevStep" class="hover:bg-red-500 hover:text-gray-50">Vissza</v-btn>
              <v-btn type="submit" class="hover:bg-sky-500 hover:text-gray-50">Tovább</v-btn>
            </v-col>
          </v-row>
        </v-container>
      </template>
    </v-form>
        <div>
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
      <button type="button" @click="addEducation">Add more</button>
      <button
          @click="prevStep"
      >
          Previous
      </button>
      <button type="submit">Next</button>
  </div>
</template>