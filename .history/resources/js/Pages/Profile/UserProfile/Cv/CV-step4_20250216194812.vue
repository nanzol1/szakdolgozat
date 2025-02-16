<script setup>
import { defineEmits, onMounted, ref } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

const emit = defineEmits(['updateForm','submitForm']);

const more_descs = ref([
  { more_desc:'' }
]);

const handleSubmit = () => {
  emit('updateForm', more_descs.value);
  emit('submitForm');
};

onMounted(() => {
    const quill = new Quill('#editor', {
      theme: 'snow'
    });
    quill.on('text-change', () => {
        more_descs.value.more_desc = quill.root.innerHTML;
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