<script setup>
import { defineEmits, nextTick, onMounted, ref, watch  } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
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

const exps = ref([
  { title: '', employer: '', location: '', startdate: null, enddate: null, description: '' }
]);

const initializeQuill = (index) => {
  const editorElement = document.getElementById(`editor-${index}`);
  if (editorElement) {
    const quill = new Quill(editorElement, {
      theme: 'snow'
    });

    if (exps.value[index].description) {
      quill.root.innerHTML = exps.value[index].description;
    }

    quill.on('text-change', () => {
      exps.value[index].description = quill.root.innerHTML;
    });

    quillInstances.value[index] = quill;
  }
};

const addExperience = () => {
  const newIndex = exps.value.length;
  exps.value.push({ title: '', employer: '', location: '', startdate: null, enddate: null, description: '' });

  nextTick(() => {
    initializeQuill(newIndex);
  });
  emit('updateForm', { exps: exps.value });

};

watch(exps.value,(newVal) => {
  emit('updateForm', { exps: newVal });
},{deep:true});

const handleSubmit = () => {
  emit('updateForm', { exps: exps.value });
  emit('nextStep');
};

onMounted(() => {
  if((props.formData.exps).length > 0){
    exps.value = Object.values(props.formData.exps).map(objs => objs);
    nextTick(() => {
      exps.value.forEach((_, index) => {
        initializeQuill(index);
      });
    });
  }else{
    exps.value.forEach((_, index) => {
        initializeQuill(index);
    });
  }
});
</script>

<template>
  <div>
    <v-form @submit.prevent="handleSubmit">
      <template v-for="(exp, index) in exps" :key="index">
        <v-container>
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="exp.title"
                label="Munka megnevezése"
                placeholder="Értékesítő"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="exp.employer"
                label="Munkáltató"
                placeholder="Példa cég"
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
              <n-date-picker v-model:value="exp.startdate" type="month" clearable size="large" placeholder="Kezdés ideje" :actions="false"/>
            </v-col>
            <v-col cols="12" md="6">
              <n-date-picker v-model:value="exp.enddate" type="month" clearable size="large" placeholder="Végzés ideje"  :actions="false"/>
            </v-col>
            <v-col cols="12" class="mb-sm-15">
              <InputLabel :for="`editor-${index}`" value="Munka leírás"></InputLabel>
              <div :id="`editor-${index}`"></div>
            </v-col>
          </v-row>
        </v-container>
      </template>
      <v-container>
        <v-row>
          <v-col cols="12">
              <v-btn type="button" @click="addExperience"  class="hover:bg-sky-500 hover:text-gray-50">Hozzáadás</v-btn>
            </v-col>
            <v-col cols="12" md="6" class="flex gap-4 flex-sm-wrap">
              <v-btn @click="prevStep" class="hover:bg-red-500 hover:text-gray-50">Vissza</v-btn>
              <v-btn type="submit" class="hover:bg-sky-500 hover:text-gray-50">Tovább</v-btn>
            </v-col>
        </v-row>
      </v-container>
    </v-form>
  </div>
</template>