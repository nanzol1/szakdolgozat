<script setup>
import { onMounted, onUnmounted, ref, watch } from 'vue';

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

    tempDiv.querySelectorAll('[data-list]').forEach(li => console.log(li.getAttribute('data-list') === 'bullet' ? li.setAttribute('style','list-style:inside disc; padding-left:1em;') : li.setAttribute('style','list-style:inside; padding-left:1em;')));

    //tempDiv.querySelectorAll('[data-list]').forEach(li => li.removeAttribute('data-list'));

    /*tempDiv.querySelectorAll('ol').forEach(li => li.setAttribute('style','padding-left:1em;'));

    tempDiv.querySelectorAll('ul').forEach(li => li.setAttribute('style','padding-left:1em;'));

    tempDiv.querySelectorAll('ol li').forEach(li => li.setAttribute('style','list-style:inside;'));*/



    return tempDiv.innerHTML;
}

watch(props.formData.more_descs, (e) => {
    cleanQuillHTML(e.more_desc);
});
</script>
<template>
    <div class="elevation-4 flex flex-col flex-1" style="width: 100%; height: 100%;">
        <div class="h-screen w-full" style="word-wrap: break-word; min-height: 297mm; max-width: 800px; height: 100% !important; width: 100%; padding: 20px; background-color: #fff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            <div style="text-align: center; margin-bottom: 30px;">
                <h1 style="font-size: 28px; margin: 0;"><span v-if="formData.firstname">{{formData.firstname}}</span> <span v-if="formData.lastname">{{formData.lastname}}</span></h1>
                <p style="font-size: 16px; margin: 5px 0;"><span v-if="formData.phone">{{formData.phone}} |</span> <span v-if="formData.email">{{formData.email}}</span></p>
                <p style="font-size: 14px; margin: 5px 0;" v-if="props.isAddition"><span v-if="formData.address">{{formData.address}},</span> <span v-if="formData.city">{{formData.city}},</span> <span v-if="formData.county">{{formData.county}}</span> <span v-if="formData.zip">{{ formData.zip }}</span></p>
            </div>
            <div style="margin-bottom: 30px;" v-if="formData.exps.map(e => e.title !== '')">
                <h2 style="font-size: 22px; border-bottom: 2px solid #333; padding-bottom: 5px;">Tapasztalat</h2>
                <div style="margin-top: 15px;" v-for="(exp,index) in formData.exps" :key="index">
                    <h3 style="font-size: 18px; margin: 0;" v-if="exp.title">{{exp.title}}</h3>
                    <p style="font-size: 14px; margin: 5px 0; color: #555;"><span v-if="exp.employer">{{ exp.employer }} |</span> <span v-if="exp.location">{{ exp.location }} |</span> <span v-if="exp.startdate">{{ exp.startdate }}</span> <span v-if="exp.enddate">- {{ exp.enddate }}</span></p>
                    <p style="font-size: 14px; margin: 5px 0;" v-if="exp.description">{{ exp.description }}</p>
                </div>
            </div>

            <div style="margin-bottom: 30px;" v-if="formData.educations.length > 0">
                <h2 style="font-size: 22px; border-bottom: 2px solid #333; padding-bottom: 5px;">Végzettség</h2>
                <div style="margin-top: 15px;" v-for="(eds,index) in formData.educations" :key="index">
                    <h3 style="font-size: 18px; margin: 0;" v-if="eds.school_name">{{eds.school_name}}</h3>
                    <p style="font-size: 14px; margin: 5px 0; color: #555;"><span v-if="eds.school_address">{{ eds.school_address }} |</span> <span v-if="eds.degree">{{ eds.degree }} |</span> <span v-if="eds.field_study">{{ eds.field_study }}</span></p>
                    <p style="font-size: 14px; margin: 5px 0; color: #555;"><span v-if="eds.startdate">{{ eds.startdate }}</span> - <span v-if="eds.enddate">{{ eds.enddate }}</span></p>
                    <p style="font-size: 14px; margin: 5px 0; color: #555;"><span v-if="eds.description">{{ eds.description }}</span></p>
                </div>
            </div>

            <div style="margin-bottom: 30px;" v-if="formData.skills.length > 0">
                <h2 style="font-size: 22px; border-bottom: 2px solid #333; padding-bottom: 5px;">Készségek</h2>
                <ul style="list-style-type: none; padding: 0; margin: 0;">
                    <li style="font-size: 14px; margin: 5px 0;" v-for="(skll,index) in formData.skills" :key="index"><span v-if="skll.skill_name">{{skll.skill_name}}</span> - <span v-if="skll.skill_level">{{ skll.skill_level }}</span></li>
                </ul>
            </div>

            <div style="margin-bottom: 30px;" v-if="formData.more_descs">
                <h2 style="font-size: 22px; border-bottom: 2px solid #333; padding-bottom: 5px;">Egyéb</h2>
                <div style="margin: 5px 0;" v-if="formData.more_descs.plain_text" v-html="formData.more_descs.more_desc"></div>
            </div>

            </div>
    </div>
  </template>
