<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const page = usePage();
const isAlert = ref(true);
const alertMessage = ref('');

watch(() => page.props.message,(newMessage) => {
    if(newMessage){
        isAlert.value = true;
        alertMessage.value = newMessage;
        setTimeout(() => {
            isAlert.value = false;
        }, 5000);
    }
},{ immediate: true });
</script>
<template>
    <footer class="w-full h-12 bg-black">
        FOOTER
    </footer>
    <v-alert v-if="isAlert"
      color="success"
      icon="$success"
      title="Alert title"
      text="Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, ratione debitis quis est labore voluptatibus..."
    ></v-alert>
</template>