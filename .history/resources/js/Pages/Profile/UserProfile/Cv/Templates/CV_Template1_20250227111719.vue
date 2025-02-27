<script setup>
import { onMounted, onUnmounted, ref } from 'vue';

defineProps({
    formData:{
        type:Object,
    },
    isAddition:{
        type:Boolean,
    },
    imagePreview:{
        type:Object
    }
});
const isAddition = ref(true);
const formData = ref({
    cv_picture:'gBc0XJxkiR6uL5bC6XZZfzrAXVFOsd24LUKk1Qyn.jpg',
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
    <div class="elevation-4 flex flex-col flex-1" style="width: 100%; height: 100%;">
        <div>
            <div style="display: flex; width: 100%;">
                    <div><img :src="imagePreview" alt="Picture"></div>
                    <div style="display: flex; flex-direction: column;">
                        <div style="display: flex; flex-direction: row;">
                            {{ formData.firstname }} 
                            {{ formData.lastname }}
                            {{ formData.phone }}
                            {{ formData.email }}
                        </div>

                        <div v-if="isAddition">
                            {{ formData.zip }}
                            {{ formData.county }}
                            {{ formData.city}}
                            {{ formData.address}}
                        </div>
                    </div>
                </div>
        </div>
      <div class="flex flex-col flex-1" style=" width: 35%; height: 100%; background-color: white;">
          <v-card elevation="0" rounded="0" style="width: 100%; height: 100%; display: flex; flex-direction: column; background-color: gray;">
            <div class="flex flex-col flex-1">
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
          </v-card>
      </div>
    </div>
  </template>
