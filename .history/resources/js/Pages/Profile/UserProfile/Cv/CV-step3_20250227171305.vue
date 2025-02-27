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

const skills = ref([
  { skill_name: '', skill_level: []}
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
  skills.value.push({ skill_name: '', skill_level: [1,2,3,4,5]});

  nextTick(() => {
    initializeQuill(newIndex);
  });

  emit('updateForm', { skills: skills.value });

};

watch(skills.value,(newVal) => {
  emit('updateForm', { skills: newVal });
},{deep:true});

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
    <v-form @submit.prevent="handleSubmit">
      <template v-for="(skill, index) in skills" :key="index">
        <v-container>
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="educ.school_name"
                label="Iskola megnevezése"
                placeholder="Példa iskola"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="educ.school_address"
                label="Iskola címe"
                placeholder="Példa utca 44."
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <n-date-picker v-model:value="educ.startdate" type="month" clearable size="large" placeholder="Kezdés ideje" update-value-on-close :actions="false"/>
            </v-col>
            <v-col cols="12" md="6">
              <n-date-picker v-model:value="educ.enddate" type="month" clearable size="large" placeholder="Végzés ideje" update-value-on-close :actions="false"/>
            </v-col>

            <v-col cols="12">
              <v-select
                label="Végzettség típusa"
                :items="['Általános-Iskola', 'Szakiskola-Szakmunkás-képző', 
                'Középiskola', 'Felsőoktatási szakképesítés', 'Főiskola', 'Egyetem',
                'Állami rendszerű egyéb képzés','Állami rendszerű, nem diplomás képzés','Coaching','OKJ-rendszerű tanfolyam',
                'Tanfolyam','Tréning']"
                v-model="educ.degree"
                
              ></v-select>
            </v-col>

            <v-col cols="12" class="mb-sm-15">
              <InputLabel :for="`editors-${index}`" value="Megjegyzés"></InputLabel>
              <div :id="`editors-${index}`"></div>
            </v-col>
            <v-col cols="12">
              <v-btn type="button" @click="addSkills" class="hover:bg-sky-500 hover:text-gray-50">Hozzáadás</v-btn>
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
          <InputLabel for="skill_name" value="Képesség"></InputLabel>
          <TextInput id="skill_name" type="text" v-model="skill.skill_name" autocomplete="skill_name"></TextInput>

          <InputLabel for="skill_level" value="Képesség szintje"></InputLabel>
            <input type="radio" v-model="skill.skill_level" :value="1">
            <input type="radio" v-model="skill.skill_level" :value="2">
            <input type="radio" v-model="skill.skill_level" :value="3">
            <input type="radio" v-model="skill.skill_level" :value="4">
            <input type="radio" v-model="skill.skill_level" :value="5">

        </div>
  </div>
</template>