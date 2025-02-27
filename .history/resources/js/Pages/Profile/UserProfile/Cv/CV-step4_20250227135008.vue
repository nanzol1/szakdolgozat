<script setup>
import { defineEmits, onMounted, ref, watch } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

const emit = defineEmits(['updateForm','submitForm','generatePDF']);
const props = defineProps({
  prevStep:{
    type:Function,
  },
  formData:{
    type:Object,
  }
});
const more_descs = ref({
    more_desc:'',
    plain_text:'',
});

const handleSubmit = () => {
  emit('updateForm', {more_desc:more_descs.value.more_desc,plain_text:more_descs.value.plain_text});
  emit('submitForm');
  emit('generatePDF');
};

watch(more_descs.value,(newVal) => {
  emit('updateForm', { more_descs: newVal });
},{deep:true});

  const toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
    ['blockquote', 'code-block'],
    ['link', 'image'],

    [{ 'header': 1 }, { 'header': 2 }],               // custom button values
    [{ 'list': 'bullet' },],
    [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript

    [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme

    ['clean']                                         // remove formatting button
  ];

onMounted(() => {
    const quill = new Quill('#editor', {
      modules:{
      toolbar: toolbarOptions
      },
      theme: 'snow'
    });
    quill.on('text-change', () => {
        more_descs.value.more_desc = quill.root.innerHTML;
        more_descs.value.plain_text = quill.getText();
    });
});
</script>

<template>
  <div>
    <form @submit.prevent="handleSubmit">
        <div>
          <InputLabel for="editor" value="További leírás"></InputLabel>
          <div id="editor"></div>
        </div>
      <button type="submit">Submit</button>
    </form>
  </div>
</template>