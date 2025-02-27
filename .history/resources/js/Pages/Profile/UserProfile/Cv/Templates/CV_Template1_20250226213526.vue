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

</script>
<template>
  <div class="elevation-4 h-screen">
    <div class="bg-sky-500 h-screen w-60" style="background-color: #38bdf8; height: 100%; width: 30%;">
        <v-card class="!bg-sky-500 h-screen" elevation="0" rounded="0">
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

        </v-card>
    </div>
  </div>
</template>
