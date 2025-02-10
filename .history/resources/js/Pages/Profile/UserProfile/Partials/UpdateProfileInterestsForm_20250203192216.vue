<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';
    import Checkbox from '@/Components/Checkbox.vue';

    const checkboxes = ref([]);


    defineProps({
        category:{
            type:Array,
        },
    });

    const toggleSubcategories = (category) => {
        console.log(checkboxes.value);
    if (checkboxes.value.includes(category.name)) {
        category.subcategories.forEach((subcategory) => {
            console.log(subcategory);
          if (!checkboxes.value.includes(subcategory.name)) {
            checkboxes.value.push(subcategory.name);
          }
        });
    } else {
    category.subcategories.forEach((subcategory) => {
      const index = checkboxes.value.indexOf(subcategory.name);
      if (index !== -1) {
        checkboxes.value.splice(index, 1);
      }
    });
  }
};
    
</script>
<template>
    <section class="w-full">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Milyen munkák érdekelnek?
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Itt tudod frissíteni az érdekeltségeidet.
            </p>
        </header>
        <form @prevent.submit="form.post(route('profile.update.interests'))">
            <div>Érdekeltségeim: {{ checkboxes }}</div>
            <div v-for="categories in category" :key="category.id">
                {{ categories.name }}
                <Checkbox :value="categories.name" v-model:checked="checkboxes" :id="categories.id" @change="toggleSubcategories(categories)">{{ categories.name }}</Checkbox>
                <div v-for="subcategories in categories.subcategories">
                    {{ subcategories.name }}
                    <Checkbox :value="subcategories.name" v-model:checked="checkboxes" :id="subcategories.id">{{ subcategories.name }}</Checkbox>
                </div>
            </div>

        </form>

    </section>
</template>