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

//QUILL Settings
const toolbarOptions = [
  [{ 'header': [1, 2, false] }],
  ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
  ['link'],
  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
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
    <v-form @submit.prevent="handleSubmit">
      <v-contaier>
        <v-row>
          <v-col cols="12" class="mb-15 pl-0">
              <InputLabel for="editor" value="További leírás"></InputLabel>
              <div id="editor"></div>
          </v-col>
        </v-row>
      </v-contaier>
      <v-container>
        <v-row>
          <v-col cols="12">
            <v-btn type="submit" class="hover:bg-sky-500 hover:text-gray-50">Generálás</v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-form>
  </div>
</template>