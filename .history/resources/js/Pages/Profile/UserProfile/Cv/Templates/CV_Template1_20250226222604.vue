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
const isAddition = ref(true);
const formData = ref({
    firstname:'firstname',
    lastname:'lastname',
    phone:'phone',
    email:'email',
    zip:'zip',
    county:'county',
    city:'city',
    address:'address',
    exps:[{title:'title',
    employer:'employer',
    location:'location',
    enddate:'enddate',
    startdate:'startdate',
    description:'description'}],
    educations:[{school_name:'school_name'}],
    skills:[{
        skill_name:'skill_name',
        skill_level:5.
   }],
    more_descs:[{more_desc:'more_desc',}],
});
</script>
<template>
  <div class="elevation-4 h-screen">
    <div class="bg-sky-500 h-screen w-60" style="background-color: #0ea5e9; width: 30%; height: 100%;">
        <v-card class="asd bg-sky-500 h-screen" elevation="0" rounded="0">
            {{ formData.firstname }}
            {{ formData.lastname }}
            {{ formData.phone }}
            {{ formData.email }}
            <div v-if="isAddition">
                {{ formData.zip }}
                {{ formData.county }}
                {{ formData.city}}
                {{ formData.address}}
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
