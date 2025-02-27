<script setup>
import { defineEmits, nextTick, onMounted, ref, watch } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';
import { NDatePicker } from 'naive-ui';


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
              <n-date-picker v-model:value="educ.startdate" type="month" clearable size="large" placeholder="Kezdés ideje" update-value-on-close/>
            </v-col>
            <v-col cols="12" md="6">
              <n-date-picker v-model:value="educ.enddate" type="month" clearable size="large" placeholder="Végzés ideje" update-value-on-close/>
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
  </div>
</template>