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
                v-model="skill.skill_name"
                label="Képesség"
                placeholder="Példa készség"
              ></v-text-field>
            </v-col>
            <v-col cols="12">
              <v-radio-group v-model="skill.skill_level" inline defaults-target="1" label="Képesítési szint">
                <v-radio
                  color="red"
                  label="red"
                  :value="1"
                  true-icon="mdi mdi-numeric-1-circle"
                ></v-radio>
                <v-radio
                  color="yellow"
                  label="red"
                  :value="2"
                  true-icon="mdi mdi-numeric-2-circle"
                ></v-radio>
                <v-radio
                  color="blue"
                  label="red"
                  :value="3"
                  true-icon="mdi mdi-numeric-3-circle"
                ></v-radio>
                <v-radio
                  color="red"
                  label="red"
                  :value="4"
                  true-icon="mdi mdi-numeric-4-circle"
                ></v-radio>
                <v-radio
                  color="red"
                  label="red"
                  :value="5"
                  true-icon="mdi mdi-numeric-5-circle"
                ></v-radio>
              </v-radio-group>
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
  </div>
</template>