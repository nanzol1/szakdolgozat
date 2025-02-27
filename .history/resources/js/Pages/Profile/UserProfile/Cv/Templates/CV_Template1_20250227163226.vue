<script setup>
import { watch } from 'vue';

const props = defineProps({
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

function cleanQuillHTML(html) {
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = html;

    tempDiv.querySelectorAll('span.ql-ui').forEach(span => span.remove());

    tempDiv.querySelectorAll('h1').forEach(h1 => {
        h1.setAttribute('class','text-2xl');
    });
    tempDiv.querySelectorAll('h2').forEach(h2 => {
        h2.setAttribute('class','text-xl');
    });


    tempDiv.querySelectorAll('ol').forEach(ol => {
        ol.setAttribute('class','pl-10');
    });

    tempDiv.querySelectorAll('ol li').forEach(li => {
        if(li.getAttribute('data-list') === 'bullet'){
            li.setAttribute('class','list-disc');
        }else{
            li.setAttribute('class','list-decimal');
        }
    });

    //tempDiv.querySelectorAll('[data-list]').forEach(li => console.log(li.getAttribute('data-list') === 'bullet' ? li.setAttribute('style','list-style:inside disc; padding-left:1em;') : li.setAttribute('style','list-style:inside; padding-left:1em;')));

    //tempDiv.querySelectorAll('[data-list]').forEach(li => li.removeAttribute('data-list'));

    /*tempDiv.querySelectorAll('ol').forEach(li => li.setAttribute('style','padding-left:1em;'));

    tempDiv.querySelectorAll('ul').forEach(li => li.setAttribute('style','padding-left:1em;'));

    tempDiv.querySelectorAll('ol li').forEach(li => li.setAttribute('style','list-style:inside;'));*/



    return tempDiv.innerHTML;
}

watch(() => props.formData.more_descs, (e) => {
    cleanQuillHTML(e.more_desc);
});
</script>
<template>
    <div class="elevation-4" style="width: 100%; height: 100%; background-color: #fff;">
        <div class="w-full" style="word-wrap: break-word;  max-width: 800px; width: 100%; padding: 20px; background-color: #fff;">
            <div style="text-align: center; margin-bottom: 15px;">
                <h1 style="font-size: 28px; margin: 0;"><span>{{formData.firstname === '' ? 'Vezetéknév' : formData.firstname}}</span> <span>{{formData.lastname === '' ? 'Keresztnév' : formData.lastname}}</span></h1>
                <p style="font-size: 16px; margin: 5px 0;"><span>{{formData.phone === '' ? 'Telefonszám' : formData.phone}} |</span> <span>{{formData.email === '' ? 'E-mail cím' : formData.email}}</span></p>
                <p style="font-size: 14px; margin: 5px 0;" v-if="props.isAddition"><span v-if="formData.address">{{formData.address}},</span> <span v-if="formData.city">{{formData.city}},</span> <span v-if="formData.county">{{formData.county}}</span> <span v-if="formData.zip">{{ formData.zip }}</span></p>
            </div>
            <div style="margin-bottom: 15px;" v-if="formData.exps.length > 0 && formData.exps.every(e => e.title.trim() !== '')">
                <h2 style="font-size: 22px; border-bottom: 2px solid #333; padding-bottom: 5px;">Tapasztalat</h2>
                <div style="margin-top: 15px;" v-for="(exp,index) in formData.exps" :key="index">
                    <h3 style="font-size: 18px; margin: 0;" v-if="exp.title">{{exp.title}}</h3>
                    <p style="font-size: 14px; margin: 5px 0; color: #555;"><span v-if="exp.employer">{{ exp.employer }} |</span> <span v-if="exp.location">{{ exp.location }} |</span> <span v-if="exp.startdate">{{ new Date(exp.startdate).getFullYear()}}-{{ new Date(exp.startdate).getMonth() }}</span> <span v-if="exp.enddate">- {{ exp.enddate }}</span></p>
                    <div style="margin: 5px 0;" v-if="exp.description" v-html="cleanQuillHTML(exp.description)"></div>
                </div>
            </div>

            <div style="margin-bottom: 15px;" v-if="formData.educations.length > 0 && formData.educations.every(e => e.school_name.trim() !== '')">
                <h2 style="font-size: 22px; border-bottom: 2px solid #333; padding-bottom: 5px;">Végzettség</h2>
                <div style="margin-top: 15px;" v-for="(eds,index) in formData.educations" :key="index">
                    <h3 style="font-size: 18px; margin: 0;" v-if="eds.school_name">{{eds.school_name}}</h3>
                    <p style="font-size: 14px; margin: 5px 0; color: #555;"><span v-if="eds.school_address">{{ eds.school_address }} |</span> <span v-if="eds.degree">{{ eds.degree }} |</span> <span v-if="eds.field_study">{{ eds.field_study }}</span></p>
                    <p style="font-size: 14px; margin: 5px 0; color: #555;"><span v-if="eds.startdate">{{ eds.startdate }}</span><span v-if="eds.enddate">- {{ eds.enddate }}</span></p>
                    <div style="margin: 5px 0;" v-if="eds.description" v-html="cleanQuillHTML(eds.description)"></div>
                </div>
            </div>

            <div style="margin-bottom: 15px;" v-if="formData.skills.length > 0 && formData.skills.every(e => e.skill_name.trim() !== '')">
                <h2 style="font-size: 22px; border-bottom: 2px solid #333; padding-bottom: 5px;">Készségek</h2>
                <ul style="list-style-type: none; padding: 0; margin: 0;">
                    <li style="font-size: 14px; margin: 5px 0;" v-for="(skll,index) in formData.skills" :key="index"><span v-if="skll.skill_name">{{skll.skill_name}}</span> - <span v-if="skll.skill_level">{{ skll.skill_level }}</span></li>
                </ul>
            </div>
            <div style="margin-bottom: 15px;" v-if="formData.more_descs && formData.more_descs.more_desc.length > 0">
                <h2 style="font-size: 22px; border-bottom: 2px solid #333; padding-bottom: 5px;">Egyéb</h2>
                <div style="margin: 5px 0;" v-if="formData.more_descs.plain_text" v-html="cleanQuillHTML(formData.more_descs.more_desc)"></div>
            </div>

            </div>
    </div>
  </template>
