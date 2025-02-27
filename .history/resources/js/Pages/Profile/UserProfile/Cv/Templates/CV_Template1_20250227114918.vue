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
    description:'description'},
    {title:'title',
    employer:'employer',
    location:'location',
    enddate:'enddate',
    startdate:'startdate',
    description:'description'},
    {title:'title',
    employer:'employer',
    location:'location',
    enddate:'enddate',
    startdate:'startdate',
    description:'description'},
    {title:'title',
    employer:'employer',
    location:'location',
    enddate:'enddate',
    startdate:'startdate',
    description:'description'},
    {title:'title',
    employer:'employer',
    location:'location',
    enddate:'enddate',
    startdate:'startdate',
    description:'description'}],
    educations:[{school_name:'school_name'},{school_name:'school_name'},{school_name:'school_name'},
    {school_name:'school_name'},{school_name:'school_name'},{school_name:'school_name'}
    ],
    skills:[{
        skill_name:'skill_name',
        skill_level:5.
   },{
        skill_name:'skill_name',
        skill_level:5.
   },{
        skill_name:'skill_name',
        skill_level:5.
   },{
        skill_name:'skill_name',
        skill_level:5.
   },{
        skill_name:'skill_name',
        skill_level:5.
   },{
        skill_name:'skill_name',
        skill_level:5.
   },{
        skill_name:'skill_name',
        skill_level:5.
   },{
        skill_name:'skill_name',
        skill_level:5.
   },{
        skill_name:'skill_name',
        skill_level:5.
   }],
    more_descs:[{more_desc:'more_descmore_descmore_descmore_descmore_descmore_descmore_descmore_descmore_descmore_descmore_descmore_descmore_descmore_descmore_descmore_descmore_desc'}],
});
</script>
<template>
    <div class="elevation-4 flex flex-col flex-1" style="width: 100%; height: 100%;">
        <div class="h-screen w-full" style="word-wrap: break-word; max-width: 800px; height: 100% !important; width: 100%; padding: 20px; background-color: #fff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            <!-- Személyes adatok -->
            <div style="text-align: center; margin-bottom: 30px;">
                <h1 style="font-size: 28px; margin: 0;">{{formData.firstname}} {{formData.lastname}}</h1>
                <p style="font-size: 16px; margin: 5px 0;">{{formData.phone}} | {{formData.email}}</p>
                <p style="font-size: 14px; margin: 5px 0;">{{formData.address}}, {{formData.city}}, {{formData.county}} {{ formData.zip }}</p>
            </div>

            <!-- Tapasztalatok -->
            <div style="margin-bottom: 30px;">
                <h2 style="font-size: 22px; border-bottom: 2px solid #333; padding-bottom: 5px;">Tapasztalat</h2>
                <div style="margin-top: 15px;" v-for="exp in formData.exps">
                    <h3 style="font-size: 18px; margin: 0;">{{exp.title}}</h3>
                    <p style="font-size: 14px; margin: 5px 0; color: #555;">{{ exp.employer }} | {{ exp.location }} | {{ exp.startdate }} - {{ exp.enddate }}</p>
                    <p style="font-size: 14px; margin: 5px 0;">{{ exp.description }}</p>
                </div>
                <!-- További tapasztalatok itt -->
            </div>

            <!-- Végzettségek -->
            <div style="margin-bottom: 30px;">
                <h2 style="font-size: 22px; border-bottom: 2px solid #333; padding-bottom: 5px;">Végzettség</h2>
                <div style="margin-top: 15px;" v-for="eds in formData.educations">
                    <h3 style="font-size: 18px; margin: 0;">{{eds.school_name}}</h3>
                    <p style="font-size: 14px; margin: 5px 0; color: #555;">{{ eds.startdate }} - {{ eds.enddate }}</p>
                </div>
                <!-- További végzettségek itt -->
            </div>

            <!-- Készségek -->
            <div style="margin-bottom: 30px;">
                <h2 style="font-size: 22px; border-bottom: 2px solid #333; padding-bottom: 5px;">Készségek</h2>
                <ul style="list-style-type: none; padding: 0; margin: 0;">
                    <li style="font-size: 14px; margin: 5px 0;" v-for="skll in formData.skills">{{skll.skill_name}} - {{ skll.skill_level }}</li>
                    <!-- További készségek itt -->
                </ul>
            </div>

            <!-- Egyéb leírások -->
            <div style="margin-bottom: 30px;">
                <h2 style="font-size: 22px; border-bottom: 2px solid #333; padding-bottom: 5px;">Egyéb</h2>
                <p style=" font-size: 14px; margin: 5px 0;" v-if="formData.more_descs" v-for="desc in formData.more_descs">{{ desc.more_desc }}</p>
            </div>

            </div>
    </div>
  </template>
