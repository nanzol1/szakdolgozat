<script setup>
import { defineEmits, nextTick, onMounted, ref, watch  } from 'vue';
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
const quillCount = ref(0);

const exps = ref([
  { title: '', employer: '', location: '', startdate: null, enddate: null, description: '' }
]);

const initializeQuill = (index) => {
  quillCount.value = index;
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

};

watch(exps.value,(newVal) => {
  console.log(newVal);
  emit('updateForm', { exps: newVal });
},{deep:true});

const handleSubmit = () => {
  emit('updateForm', { exps: exps.value });
  emit('nextStep');
};

onMounted(() => {
  exps.value.forEach((_, index) => {
      initializeQuill(index);
    });
  if((props.formData.exps).length > 0){
    exps.value = Object.values(props.formData.exps).map(asd => asd);
    nextTick(() => {
      exps.value.forEach((_, index) => {
        initializeQuill(index);
      });
    });
  }
});

</script>

<template>
  <div>
    <form @submit.prevent="handleSubmit">
      <template v-for="(exp, index) in exps" :key="index">
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
    </form>
  </div>
</template>