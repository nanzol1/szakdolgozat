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
                placeholder="Szakács"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="exp.employer"
                label="Munkáltató"
                placeholder="Példa cég"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="exp.location"
                label="Elhelyezkedés"
                placeholder="Debrecen"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="exp.location"
                label="Elhelyezkedés"
                placeholder="Debrecen"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <n-date-picker v-model:value="timestamp" type="month" clearable />
            </v-col>
            <v-col cols="12" md="6">
              <n-date-picker v-model:value="timestamp" type="month" clearable />
            </v-col>
          </v-row>
        </v-container>
        <div>
          <InputLabel for="jobtitle" value="Munka megnevezése"></InputLabel>
          <TextInput id="jobtitle" type="text" v-model="exp.title" autocomplete="title"></TextInput>

          <InputLabel for="employer" value="Munkáltató"></InputLabel>
          <TextInput id="employer" type="text" v-model="exp.employer" autocomplete="employer"></TextInput>

          <InputLabel for="location" value="Elhelyezkedés"></InputLabel>
          <TextInput id="location" type="text" v-model="exp.location" autocomplete="location"></TextInput>

          <InputLabel for="startdate" value="Kezdési idő"></InputLabel>
          <input type="date" v-model="exp.startdate" id="startdate">

          <InputLabel for="enddate" value="Befejezési idő"></InputLabel>
          <input type="date" v-model="exp.enddate" id="enddate">

          <InputLabel :for="`editor-${index}`" value="Munka leírás"></InputLabel>
          <div :id="`editor-${index}`"></div>
        </div>
      </template>
      <button type="button" @click="addExperience">Add more</button>
      <button
          @click="prevStep"
      >
          Previous
      </button>
      <button type="submit">Next</button>
    </v-form>
  </div>
</template>