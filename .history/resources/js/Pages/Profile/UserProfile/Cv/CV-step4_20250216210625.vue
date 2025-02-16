<script setup>
import { defineEmits, onMounted, ref } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

const emit = defineEmits(['updateForm','submitForm']);

const more_descs = ref({
    more_desc:'',
    plain_text:'',
});

const handleSubmit = () => {
  emit('updateForm', {more_desc:more_descs.value.more_desc,plain_text:more_descs.value.plain_text});
  emit('submitForm');
};

onMounted(() => {
    const quill = new Quill('#editor', {
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
      <button type="submit">Previous</button>
      <button type="submit">Submit</button>
    </form>
  </div>
</template>