<template>
    <form @submit.prevent="submitForm">
      <div>Érdekeltségeim: {{ checkboxes }}</div>
  
      <!-- Kategóriák megjelenítése -->
      <div v-for="category in category" :key="category.id">
        <div>
          <label :for="category.id">{{ category.name }}</label>
          <!-- Checkbox a kategóriához -->
          <Checkbox 
            :value="category.id" 
            v-model="checkboxes" 
            :id="category.id"
            @change="toggleSubcategories(category)"
          ></Checkbox>
  
          <!-- Alkategóriák, csak ha van kapcsolódó subcategory -->
          <div v-for="subcategory in category.subcategories" :key="subcategory.id" v-if="subcategory.maincateg_id === category.id">
            <label :for="subcategory.id">{{ subcategory.name }}</label>
            <Checkbox 
              :value="subcategory.id" 
              v-model="checkboxes" 
              :id="subcategory.id"
            ></Checkbox>
          </div>
        </div>
      </div>
  
      <button type="submit">Mentés</button>
    </form>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import Checkbox from '@/Components/Checkbox.vue';
  
  // A checkboxok állapotának tárolása
  const checkboxes = ref([]);
  
  // A kategóriák, amelyeket a szülő komponens küldött
  defineProps({
    category: {
      type: Array,
    },
  });
  
  // A form elküldésére használt useForm hook
  const { post } = useForm();
  
  // Alkategóriák bejelölése a fő kategória alapján
  const toggleSubcategories = (category) => {
    if (checkboxes.value.includes(category.id)) {
      // Ha a fő kategóriát bejelöltük, akkor az összes alkategóriát is bejelöljük
      category.subcategories.forEach((subcategory) => {
        if (!checkboxes.value.includes(subcategory.id)) {
          checkboxes.value.push(subcategory.id);
        }
      });
    } else {
      // Ha a fő kategóriát eltávolítjuk, akkor az összes alkategóriát is eltávolítjuk
      category.subcategories.forEach((subcategory) => {
        const index = checkboxes.value.indexOf(subcategory.id);
        if (index !== -1) {
          checkboxes.value.splice(index, 1);
        }
      });
    }
  };
  
  // A form elküldése
  const submitForm = () => {
    post(route('profile.update.interests'), {
      interests: checkboxes.value,
    });
  };
  </script>
  