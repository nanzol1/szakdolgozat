<script setup>
import { onMounted, onUnmounted, ref } from 'vue';

defineProps({
    formData:{
        type:Object,
    },
    isAddition:{
        type:Boolean,
    }
});
const styleElement = ref(null);
const style = document.createElement('style');

onMounted(() => {
    style.innerHTML = `
        .bg-sky-500 {
            background-color:!red;
        }
    `;
    document.head.appendChild(style);
    styleElement.value = style;
});
onUnmounted(() => {
    if(styleElement.value){
        document.head.removeChild(styleElement.value);
    }
});
</script>
<template>
  <div style="box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); height: 100vh;">
    <div style="background-color: #38bdf8; height: 100%; width: 30%;">
      <div style="background-color: #38bdf8; height: 100%;" elevation="0" rounded="0">
        {{ formData.firstname }}
        {{ formData.lastname }}
        {{ formData.phone }}
        {{ formData.email }}
        <div v-if="isAddition">
          {{ formData.zip }}
          {{ formData.county }}
          {{ formData.city }}
          {{ formData.address }}
        </div>
        <div v-if="formData.exps" v-for="exp in formData.exps">
          {{ exp.title }}
          {{ exp.employer }}
          {{ exp.location }}
          {{ exp.startdate }}
          {{ exp.enddate }}
          {{ exp.description }}
        </div>
        <div v-if="formData.educations" v-for="eds in formData.educations">
          {{ eds.school_name }}
        </div>
        <div v-if="formData.skills" v-for="sklls in formData.skills">
          {{ sklls.skill_name }}
          {{ sklls.skill_level }}
        </div>
        <div v-if="formData.more_descs">
          {{ formData.more_descs.more_desc }}
        </div>
      </div>
    </div>
  </div>
</template>
