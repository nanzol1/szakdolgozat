<script setup>
import { defineEmits, nextTick, onMounted, ref, watch } from 'vue';
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

const skills = ref([
  { skill_name: '', skill_level: []}
]);

const addSkills = () => {
  skills.value.push({ skill_name: '', skill_level: [1,2,3,4,5]});

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
  if((props.formData.skills).length > 0){
    skills.value = Object.values(props.formData.skills).map(objs => objs);
  }
});
</script>

<template>
  <div>
    <v-form @submit.prevent="handleSubmit">
        <v-container>
          <v-row>
            <template v-for="(skill, index) in skills" :key="index">
            <v-col cols="12" md="6">
              <v-text-field
                v-model="skill.skill_name"
                label="Erősségek"
                placeholder="Vezetői kézség"
              ></v-text-field>
              <v-radio-group v-model="skill.skill_level" inline label="Szint">
                <v-radio
                  color="#00e2f4"
                  label="-"
                  :value="1"
                  true-icon="mdi mdi-numeric-1-circle"
                  false-icon="mdi mdi-numeric-1-circle"
                ></v-radio>
                <v-radio
                  color="#00cef4"
                  :value="2"
                  label="-"
                  true-icon="mdi mdi-numeric-2-circle"
                  false-icon="mdi mdi-numeric-2-circle"
                ></v-radio>
                <v-radio
                  color="#00baf4"
                  label="-"
                  :value="3"
                  true-icon="mdi mdi-numeric-3-circle"
                  false-icon="mdi mdi-numeric-3-circle"
                ></v-radio>
                <v-radio
                  color="#00b0f4"
                  label="-"
                  :value="4"
                  true-icon="mdi mdi-numeric-4-circle"
                  false-icon="mdi mdi-numeric-4-circle"
                ></v-radio>
                <v-radio
                  :value="5"
                  color="#00a6f4"
                  true-icon="mdi mdi-numeric-5-circle"
                  false-icon="mdi mdi-numeric-5-circle"
                ></v-radio>
              </v-radio-group>
            </v-col>
            </template>
          </v-row>
        </v-container>
      <v-container>
        <v-row>
          <v-col cols="12">
              <v-btn type="button" @click="addSkills" class="!bg-[#A67C52] hover:!bg-[#3A2618] !text-gray-50 hover:text-gray-50">Hozzáadás</v-btn>
            </v-col>
            <v-col cols="12" md="6" class="flex gap-4 flex-sm-wrap">
              <v-btn @click="prevStep" type="button" class="!bg-[#A67C52] hover:!bg-[#3A2618] !text-gray-50 hover:text-gray-50">Vissza</v-btn>
              <v-btn type="submit" class="!bg-[#A67C52] hover:!bg-[#3A2618] !text-gray-50 hover:text-gray-50">Tovább</v-btn>
            </v-col>
        </v-row>
      </v-container>
    </v-form>
  </div>
</template>