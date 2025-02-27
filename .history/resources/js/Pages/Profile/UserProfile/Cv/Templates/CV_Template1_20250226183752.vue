<script setup>
defineProps({
    formData:{
        type:Object,
    },
    isAddition:{
        type:Boolean,
    }
});
</script>

<template>
  <div class="elevation-4 h-screen">
    <div class="bg-sky-500 h-screen w-60">
        <v-card class="!bg-sky-500 h-screen" elevation="0" rounded="0">
            {{ formData.firstname }}
            {{ formData.lastname }}
            {{ formData.phone }}
            {{ formData.email }}
            <div v-if="isAddition">
                {{ formData.zip }}
                {{ formData.county }}
                {{ formData.city }}
                {{ formData.address }}
            </div>
            {{ formData.exps }}
            <div v-for="exp in formData.exps">
                {{ exp.title }}
            </div>

        </v-card>
    </div>
  </div>
</template>